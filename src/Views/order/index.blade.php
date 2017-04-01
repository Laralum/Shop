@extends('laralum::layouts.master')
@section('icon', 'ion-android-cart')
@section('title', __('laralum_shop::orders.title'))
@section('subtitle', __('laralum_shop::orders.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::orders.title')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::orders.order_list')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('laralum_shop::orders.user_email')</th>
                                        <th>@lang('laralum_shop::orders.items')</th>
                                        <th>@lang('laralum_shop::orders.units')</th>
                                        <th>@lang('laralum_shop::orders.earnings')</th>
                                        <th>@lang('laralum_shop::orders.status')</th>
                                        <th>@lang('laralum_shop::orders.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->email }}</td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>{{ $order->units() }}</td>
                                            <td><span class="money">{{ $order->price() }}</span></td>
                                            <td>{{ $order->status->name }}</td>
                                            <td class="uk-table-shrink">
                                                <div class="uk-button-group">
                                                    <a href="{{ route('laralum::shop.order.show', ['order' => $order->id]) }}" class="uk-button uk-button-small uk-button-default">
                                                        @lang('laralum_shop::orders.show')
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
