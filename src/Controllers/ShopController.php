<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Laralum\Payments\Models\Settings as PaymentsSettings;
use Laralum\Settings\Models\Settings as AppSettings;
use Laralum\Shop\Models\Category;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Models\Settings;
use Laralum\Shop\Models\Status;
use Laralum\Shop\Models\User as ShopUser;
use Laralum\Shop\Notifications\ReciptNotification;
use Laralum\Users\Models\User;
use Stripe\Charge;
use Stripe\Stripe;

class ShopController extends Controller
{
    /**
      * Set the Stripe API Key and the cart.
      *
      * @return void
      */
     public function __construct()
     {
         $payments = PaymentsSettings::first();

         if (!$payments->ready()) {
             abort(404, 'Payments module is not setup');
         }

         Stripe::setApiKey(decrypt($payments->stripe_secret));
     }

    /**
     * Shows all the items (can be filtered by a category).
     *
     * @param int $category
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category = null)
    {
        $items = Item::all();

        if ($category) {
            $items = Category::findOrFail($category)->items;
        }

        return view('laralum_shop::shop.index', [
            'category' => $category,
            'items'    => $items,
        ]);
    }

    /**
     * Shows the item details.
     *
     * @param \Laralum\Shop\Models\Item $item
     *
     * @return \Illuminate\Http\Response
     */
    public function item(Item $item)
    {
        return view('laralum_shop::shop.item', [
            'item'     => $item,
            'payments' => PaymentsSettings::first(),
            'settings' => AppSettings::first(),
        ]);
    }

    /**
     * Show all the user orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        return view('laralum_shop::shop.orders', ['orders' => ShopUser::findOrFail(Auth::id())->orders]);
    }

    /**
     * Show the order details.
     *
     * @param \Laralum\Shop\Models\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function order(Order $order)
    {
        $this->authorize('publicView', $order);

        return view('laralum_shop::shop.order', ['order' => $order]);
    }

    /**
     * Shows the current cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function cart()
    {
        $items = self::currentItems();
        $settings = Settings::first();

        $price = bcmul($items->sum('price'), 100, 2);
        $tax = bcmul($settings->tax_percentage, $items->sum('price'), 2);

        return view('laralum_shop::shop.cart', [
            'items'        => $items,
            'tax'          => $tax,
            'price'        => $price,
            'totalPrice'   => bcadd($tax, $price, 2),
            'payments'     => PaymentsSettings::first(),
            'app_settings' => AppSettings::first(),
            'settings'     => Settings::first(),
        ]);
    }

    /**
     * Add an item to the cart.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Laralum\Shop\Models\Item $item
     *
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request, Item $item)
    {
        foreach (session('laralum_shop_cart', []) as $i) {
            if ($item->stock && $i['amount'] >= $item->stock) {
                return redirect()->back()->with('success', __('laralum_shop::cart.stock_error'));
            }
        }

        $stock = $item->stock ? "|max:$item->stock" : '';

        $this->validate($request, [
            'amount' => 'sometimes|required|integer|min:1'.$stock,
        ]);

        $cart = session('laralum_shop_cart', []);
        $added = false;
        foreach ($cart as $key => $c_item) {
            if ($c_item['id'] == $item->id) {
                $added = true;
                $cart[$key]['amount'] += $request->amount ? $request->amount : 1;
                break;
            }
        }

        if (!$added) {
            array_push($cart, [
                'id'     => $item->id,
                'amount' => $request->amount ? $request->amount : 1,
            ]);
        }

        session(['laralum_shop_cart' => $cart]);

        return redirect()->back()->with('success', __('laralum_shop::cart.added'));
    }

    /**
     * Add an item to the cart.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Laralum\Shop\Models\Item $item
     *
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request, Item $item)
    {
        $cart = session('laralum_shop_cart', []);
        foreach ($cart as $key => $c_item) {
            if ($c_item['id'] == $item->id) {
                unset($cart[$key]);
                break;
            }
        }

        array_values($cart);

        session(['laralum_shop_cart' => $cart]);

        return redirect()->back()->with('success', __('laralum_shop::cart.removed'));
    }

    /**
     * Proccess the payment for the current shopping cart items.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Laralum\Shop\Models\Item $item
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $settings = Settings::first();

        if ($settings->emergency) {
            abort(403, __('laralum_shop::general.emergency_on'));
        }

        $this->validate($request, [
            'stripeToken' => 'required',
            'stripeEmail' => 'required',
        ]);

        foreach (collect(self::currentItems()) as $item) {
            if ($item['item']->stock && $item['amount'] > $item['item']->stock) {
                session(['laralum_shop_cart' => []]);

                return redirect()->route('laralum_public::shop.orders')->with('error', __('laralum_shop::shop.stock_error'));
            }
        }

        $order = Order::create([
            'user_id'               => Auth::user()->id,
            'status_id'             => Status::findOrFail(1)->id,
            'tax_percentage_on_buy' => $settings->tax_percentage,
            'shipping_name'         => $request->stripeShippingName,
            'shipping_adress'       => $request->stripeShippingAddressLine1,
            'shipping_zip'          => $request->stripeShippingAddressZip,
            'shipping_state'        => $request->stripeShippingAddressState,
            'shipping_city'         => $request->stripeShippingAddressCity,
            'shipping_country'      => $request->stripeShippingAddressCountry,
        ]);

        $order->items()->attach(collect(self::currentItems())->mapWithKeys(function ($item) {
            return [
                $item['item']->id => [
                    'units'       => $item['amount'],
                    'item_on_buy' => serialize($item['item']->toArray()),
                ],
            ];
        }));

        User::findOrFail(Auth::id())->notify(new ReciptNotification($order));

        try {
            if ($order->price() > 0) {
                Charge::create([
                    'amount'      => $order->totalPrice() * 100,
                    'currency'    => $settings->currency,
                    'source'      => $request->stripeToken,
                    'description' => "Charge for order: #$order->id",
                ]);
            }
            foreach ($order->items as $item) {
                if ($item->stock) {
                    $item->update(['stock' => $item->stock - $item->pivot->units]);
                }
            }
        } catch (Exception $e) {
            return redirect()->route('laralum_public::shop.orders')->with('error', __('laralum_shop::shop.buy_error'));
        }

        $order->update([
            'status_id' => $settings->paid_status,
        ]);

        session(['laralum_shop_cart' => []]);

        return redirect()->route('laralum_public::shop.orders')->with('success', __('laralum_shop::shop.buy_success', [
            'id' => $order->id,
        ]));
    }

    /**
     * Format the current cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function currentItems()
    {
        return collect(session('laralum_shop_cart', []))->map(function ($c_item) {
            $item = Item::findOrFail($c_item['id']);

            return [
                'item'   => $item,
                'amount' => $c_item['amount'],
                'price'  => bcmul($c_item['amount'], $item->price, 2),
            ];
        });
    }
}
