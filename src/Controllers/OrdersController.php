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

}
