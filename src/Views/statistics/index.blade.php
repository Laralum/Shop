@extends('laralum::layouts.master')
@section('icon', 'ion-stats-bars')
@section('title', __('laralum_shop::statistics.title'))
@section('subtitle', __('laralum_shop::statistics.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::statistics.title')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>

            {{-- Basic Statistics --}}

            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default uk-card-body">
                    <span class="statistics-text">@lang('laralum_shop::statistics.total_items')</span><br />
                    <span class="statistics-number">
                        {{ $statistics['items']->count() }}
                    </span>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default uk-card-body">
                    <span class="statistics-text">@lang('laralum_shop::statistics.total_earnings')</span><br />
                    <span class="statistics-number money">
                        {{ $statistics['earnings'] }}
                    </span>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default uk-card-body">
                    <span class="statistics-text">@lang('laralum_shop::statistics.total_sales')</span><br />
                    <span class="statistics-number">
                        {{ $statistics['orders']->count() }}
                    </span>
                </div>
            </div>

            {{-- Last x Days --}}

            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default uk-card-body">
                    <form method="POST" action="{{ route('laralum::shop.index.filter') }}">
                        {{ csrf_field() }}
                        <div uk-grid>
                            <div class="uk-width-2-5@l uk-width-1-5@xl">
                                <label class="uk-form-label">@lang('laralum_shop::statistics.show_days')</label>
                                <div class="uk-form-controls">
                                    <div class="uk-form-controls">
                                        <input required value="{{ old('number') }}" name="number" class="uk-input" type="text" placeholder="@lang('laralum_shop::statistics.show_days_ph')">
                                    </div>
                                </div>
                            </div>
                            <div class="uk-width-3-5@l uk-width-4-5@xl"><br />
                                <button type="submit" class="uk-button uk-button-primary" type="button">@lang('laralum_shop::statistics.filter')</button>
                                @if ($number != 7)
                                    <a href="{{ route('laralum::shop.index') }}" class="uk-button uk-button-danger" type="button">@lang('laralum_shop::statistics.remove_filter')</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-2@l">
                <div class="uk-card uk-card-default uk-card-body">
                    <span class="statistics-text">@lang('laralum_shop::statistics.last_earnings', ['number' => $number])</span><br />
                    <span class="statistics-number money">
                        {{ $statistics['last_earnings']->sum() }}
                    </span>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-2@l">
                <div class="uk-card uk-card-default uk-card-body">
                    <span class="statistics-text">@lang('laralum_shop::statistics.last_sales', ['number' => $number])</span><br />
                    <span class="statistics-number">
                        {{ $statistics['last_orders']->count() }}
                    </span>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-2@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::statistics.last_sales', ['number' => $number])
                    </div>
                    <div class="uk-card-body">
                        {!! ConsoleTVs\Charts\Facades\Charts::multiDatabase('bar', 'highcharts')
                            ->dataset(__('laralum_shop::statistics.sales'), $statistics['orders'])->elementLabel(__('laralum_shop::statistics.sales'))
                            ->title(' ')->dimensions(0, 400)->lastByDay($number, true)->render()
                        !!}
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1@m uk-width-1-2@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::statistics.last_earnings', ['number' => $number])
                    </div>
                    <div class="uk-card-body">
                        {!! ConsoleTVs\Charts\Facades\Charts::multi('bar', 'highcharts')
                            ->labels($statistics['last_earnings']->keys()->map(function($date){ return date('l dS M, Y', strtotime($date)); })->all())
                            ->dataset(__('laralum_shop::statistics.earnings'), $statistics['last_earnings'])
                            ->elementLabel(__('laralum_shop::statistics.earnings'))
                            ->title(' ')->dimensions(0, 400)->render()
                        !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.bootcss.com/currencyformatter.js/1.0.4/currencyFormatter.min.js"></script>
<script>
    OSREC.CurrencyFormatter.formatAll({
        selector: '.money',
        currency: 'EUR'
    });
</script>
@endsection
