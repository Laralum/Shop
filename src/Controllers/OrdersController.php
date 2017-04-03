<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Models\Status;
use Auth;

class OrdersController extends Controller
{
    /**
     * Show all the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::order.index', ['filtered' => false, 'orders' => Order::all(), 'status' => Status::all()]);
    }

    /**
     * Show all the orders filtered by a status.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $status
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request, $status = null)
    {
        $orders = Order::all();

        if ($status) {
            $orders = Order::where('status_id', $status)->get();
        } elseif ($request->status) {
            $orders = Order::where('status_id', $request->status)->get();
        }

        return view('laralum_shop::order.index', ['filtered' => true, 'orders' => $orders, 'status' => Status::all()]);
    }

    /**
     * Show the order details.
     *
     * @param \Laralum\Shop\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('laralum_shop::order.show', ['order' => $order]);
    }

    /**
     * Set the order status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, Order $order)
    {
        $this->authorize('status', $order);

        $this->validate($request, [
            'status' => 'required|exists:laralum_shop_status,id',
        ]);

        $order->update([
            'status_id' => $request->status,
        ]);

        return redirect()->route('laralum::shop.order.index')->with('success', __('laralum_shop::orders.status_changed'));
    }

}
