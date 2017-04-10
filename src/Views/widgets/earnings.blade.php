@php
    $last_earnings = \Laralum\Shop\Controllers\StatisticsController::lastEarningsByDay(7);
    $settings = \Laralum\Shop\Models\Settings::first();
@endphp
{!! ConsoleTVs\Charts\Facades\Charts::multi('bar', 'highcharts')
    ->labels($last_earnings->keys()->map(function($date){ return date('l dS M, Y', strtotime($date)); })->all())
    ->dataset(__('laralum_shop::statistics.earnings', ['currency' => $settings->currency]), $last_earnings)
    ->elementLabel(__('laralum_shop::statistics.earnings', ['currency' => $settings->currency]))
    ->title(' ')->dimensions(0, 400)->render()
!!}
