@php
    $orders = \Laralum\Shop\Models\Order::where('status_id', \Laralum\Shop\Models\Settings::first()->paid_status)->get();
@endphp
{!! \ConsoleTVs\Charts\Facades\Charts::multiDatabase('bar', 'highcharts')
    ->dataset(__('laralum_shop::statistics.sales'), $orders)->elementLabel(__('laralum_shop::statistics.sales'))
    ->title(__('laralum_shop::statistics.last_sales', ['number' => 7]))->dimensions(0, 400)->lastByDay(7, true)->render()
!!}
