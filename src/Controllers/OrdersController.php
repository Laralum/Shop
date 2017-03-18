<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Models\User;
use Auth;

class OrdersController extends Controller
{
    /**
     * Show all the user orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::shop.orders', ['orders' => User::findOrFail(Auth::id())->orders]);
    }

    /**
     * Show the order details.
     *
     * @param \Laralum\Shop\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('laralum_shop::shop.order', ['order' => $order]);
    }

    /**
     * Create an item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate([
            'name' => 'required|min:5',
            'description' => 'required|min:15',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer',
        ]);

        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => ($request->stock || $request->stock === 0) ? $request->stock : null,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.created'));
    }

    /**
     * Shows the update form to update an item.
     *
     * @param Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('laralum_shop::item.update', ['item' => $item]);
    }

    /**
     * Update a category.
     *
     * @param \Illuminate\Http\Request $request
     * @param Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->validate([
            'name' => 'required|min:5',
            'description' => 'required|min:15',
            'price' => 'required|numeric|min:0',
            'stock' => 'integer',
        ]);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => ($request->stock || $request->stock === 0) ? $request->stock : null,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.updated'));
    }

    /**
     * Delete an item.
     *
     * @param \Illuminate\Http\Request $request
     * @param Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        $item->delete();

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.deleted'));
    }
}
