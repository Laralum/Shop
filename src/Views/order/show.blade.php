@extends('laralum::layouts.master')
@section('icon', 'ion-pricetag')
@section('title', __('laralum_shop::orders.show_title', ['id' => $order->id]))
@section('subtitle', __('laralum_shop::orders.show_subtitle', ['id' => $order->id]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><a href="{{ route('laralum::shop.order.index') }}">@lang('laralum_shop::orders.title')</a></li>
        <li><span href="">@lang('laralum_shop::orders.show_title', ['id' => $order->id])</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default" uk-sticky="media: 640; bottom: true; offset: 120;">
                    <div class="uk-card-header">
                        @lang('laralum_shop::orders.order_details')
                    </div>
                    <div class="uk-card-body">
                        <div uk-grid>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.id')</h4>
                                <span>#{{ $order->id }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.status')</h4>
                                <span>{{ $order->status->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.user_email')</h4>
                                <span>{{ $order->user->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.earnings')</h4>
                                <span class="uk-label uk-label-success money">{{ $order->price() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.items')</h4>
                                <span>{{ $order->items->count() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.units')</h4>
                                <span>{{ $order->units() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.creation_date')</h4>
                                <span>{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.updated_date')</h4>
                                <span>{{ $order->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@m uk-width-2-3@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::orders.order_items')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <dl class="uk-description-list uk-description-list-divider">
                                @foreach($order->items as $item)
                                    <dt>{{ $item->name }}</dt>
                                    <dd>
                                        @lang('laralum_shop::orders.price_exp', [
                                            'units' => $item->pivot->units,
                                            'price' => unserialize($item->pivot->item_on_buy)['price'],
                                            'total' => bcmul(unserialize($item->pivot->item_on_buy)['price'], $item->pivot->units, 2),
                                        ])
                                    </dd>
                                @endforeach
                            </dl>
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
