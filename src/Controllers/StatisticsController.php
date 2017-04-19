<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Models\Order;
use Laralum\Shop\Models\Settings;
use Laralum\Users\Models\User;

class StatisticsController extends Controller
{
    /**
     * Show all the shop statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        if (!($user->superAdmin() || $user->hasPermission('laralum::shop.statistics'))) {
            abort(403, 'This action is unauthorized');
        }
        $orders = Order::where('status_id', Settings::first()->paid_status)->get();
        $number = 7;

        $statistics = [
            'items'             => Item::all(),
            'orders'            => $orders,
            'earnings'          => $orders->map(function ($order) {
                return $order->totalPrice();
            })->sum(),
            'last_earnings'     => static::lastEarningsByDay($number),
            'last_orders'       => Order::whereDate('created_at', '>', date('Y-m-d', strtotime('-'.$number.' days')))->where('status_id', Settings::first()->paid_status)->get(),
        ];

        return view('laralum_shop::statistics.index', ['statistics' => $statistics, 'number' => $number]);
    }

    /**
     * Show all the shop statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request, $number = 7)
    {
        $user = User::findOrFail(Auth::id());
        if (!($user->superAdmin() || $user->hasPermission('laralum::shop.statistics'))) {
            abort(403, 'This action is unauthorized');
        }
        $orders = Order::where('status_id', Settings::first()->paid_status)->get();

        if ($request->number) {
            $number = $request->number;
        }

        $statistics = [
            'items'             => Item::all(),
            'orders'            => $orders,
            'earnings'          => $orders->map(function ($order) {
                return $order->totalPrice();
            })->sum(),
            'last_earnings'     => static::lastEarningsByDay($number),
            'last_orders'       => Order::whereDate('created_at', '>', date('Y-m-d', strtotime('-'.$number.' days')))->where('status_id', Settings::first()->paid_status)->get(),
        ];

        return view('laralum_shop::statistics.index', ['statistics' => $statistics, 'number' => $number]);
    }

    /**
     * Return the latest earnings by day.
     *
     * @param int $d
     *
     * @return \Illuminate\Http\Response
     */
    public static function lastEarningsByDay($d = 7)
    {
        for ($days = []; count($days) < $d; array_push($days, (date('Y-m-d', strtotime('-'.count($days).' days')))));

        return collect($days)->reverse()->mapWithKeys(function ($date) {
            return [$date => Order::whereDate('created_at', '=', date('Y-m-d', strtotime($date)))
                ->where('status_id', Settings::first()->paid_status)->get()->map(function ($order) {
                    return $order->totalPrice();
                })->sum(), ];
        });
    }
}
