<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Category;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Models\Status;
use Laralum\Settings\Models\Settings;
use Laralum\Payments\Models\Settings as PaymentsSettings;
use Stripe\Stripe;
use Stripe\Charge;
use Auth;

class ShopController extends Controller
{
    /**
     * Set the Stripe API Key and the cart.
     *
     * @return void
     */
     public function __construct()
     {
         Stripe::setApiKey(decrypt(PaymentsSettings::first()->stripe_secret));
     }
    /**
     * Shows all the items (can be filtered by a category).
     *
     * @param int $category
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
            'items' => $items
        ]);
    }

    /**
     * Shows the item details.
     *
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function item(Item $item)
    {
        return view('laralum_shop::shop.item', [
            'item' => $item,
            'payments' => PaymentsSettings::first(),
            'settings' => Settings::first(),
        ]);
    }

    /**
     * Shows the current cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function cart()
    {
        $items = self::currentItems();

        return view('laralum_shop::shop.cart', [
            'items' => $items,
            'payments' => PaymentsSettings::first(),
            'settings' => Settings::first(),
        ]);
    }

    /**
     * Add an item to the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request, Item $item)
    {
        foreach (session('laralum_shop_cart', []) as $i) {
            if ($item->stock && $i['amount'] >= $item->stock) {
                return redirect()->back()->with('success', __('laralum_shop::cart.stock_error'));
            }
        }

        $stock = $item->stock ? "|max:$item->stock" : "";

        $this->validate($request, [
            'amount' => 'sometimes|required|integer|min:1'.$stock,
        ]);

        $cart = session('laralum_shop_cart', []);
        $added = false;
        foreach($cart as $key => $c_item) {
            if ($c_item['id'] == $item->id) {
                $added = true;
                $cart[$key]['amount'] += $request->amount ? $request->amount : 1;
                break;
            }
        }

        if (!$added) {
            array_push($cart, [
                'id' => $item->id,
                'amount' => $request->amount ? $request->amount : 1,
            ]);
        }

        session(['laralum_shop_cart' => $cart]);

        return redirect()->back()->with('success', __('laralum_shop::cart.added'));
    }

    /**
     * Add an item to the cart.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request, Item $item)
    {
        $cart = session('laralum_shop_cart', []);
        foreach($cart as $key => $c_item) {
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
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $this->validate($request, [
            'stripeToken' => 'required',
            'stripeEmail' => 'required',
        ]);

        foreach (collect(self::currentItems()) as $item) {
            if ($item['item']->stock && $item['amount'] > $item['item']->stock) {
                return redirect()->route('laralum_public::shop.orders')->with('error', __('laralum_shop::shop.stock_error'));
            }
        }

        $order = new Order;
        $order->user()->associate(Auth::user());
        $order->status()->associate(Status::findOrFail(1)); // HARD CODED; PLEASE KILL ME
        $order->save();
        $order->items()->attach(collect(self::currentItems())->mapWithKeys(function($item) {
            return [
                $item['item']->id => [
                    'units' => $item['amount'],
                    'item_on_buy' => serialize($item['item']->toArray())
                ]
            ];
        }));

        try {
            Charge::create([
                "amount" => self::currentItems()->sum('price') * 100,
                "currency" => "eur",
                "source" => $request->stripeToken,
                "description" => "Charge for order: #$order->id",
            ]);
            foreach ($order->items as $item) {
                if ($item->stock) {
                    $item->update(['stock' => $item->stock - $item->pivot->units]);
                }
            }
        } catch (Exception $e) {
            return redirect()->route('laralum_public::shop.orders')->with('error', __('laralum_shop::shop.buy_error'));
        }



        $order->status()->associate(Status::findOrFail(2)); // HARD CODED; PLEASE KILL ME
        $order->save();

        session(['laralum_shop_cart' => []]);

        return redirect()->route('laralum_public::shop.orders')->with('success', __('laralum_shop::shop.buy_success'));
    }

    /**
     * Format the current cart items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function currentItems()
    {
        return collect(session('laralum_shop_cart', []))->map(function($c_item) {
            $item = Item::findOrFail($c_item['id']);
            return [
                'item' => $item,
                'amount' => $c_item['amount'],
                'price' => bcmul($c_item['amount'], $item->price, 2),
            ];
        });
    }
}
