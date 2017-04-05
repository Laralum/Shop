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
        <div id="status_modal" uk-modal="center: true">
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">@lang('laralum_shop::orders.order') #<span id='order_id'>{{ $order->id }}</span></h2>
                </div>
                <div class="uk-modal-body">
                    <form id='status_form' method="POST" action="{{ route('laralum::shop.order.status', ['order' => $order->id]) }}">
                        {{ csrf_field() }}
                        <center>
                            <div class="uk-width-1-1@l uk-width-3-5@xl">
                                <label class="uk-form-label">@lang('laralum_shop::orders.status')</label>
                                <div class="uk-form-controls">
                                    <select required name="status" id="status_select" class="uk-select">
                                        @foreach($status as $s)
                                            <option @if ($order->status->id == $s->id) selected @endif value="{{ $s->id }}">
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </center>
                    </form>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">@lang('laralum_shop::orders.cancel')</button>
                    <button class="uk-button uk-button-primary" id="change_status" type="button">@lang('laralum_shop::orders.save')</button>
                </div>
            </div>
        </div>
        <div uk-grid>
            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default" uk-sticky="media: 640; bottom: true; offset: 120;">
                    <div class="uk-card-header">
                        @lang('laralum_shop::orders.order_details')
                    </div>
                    <div class="uk-card-body">
                        <div uk-grid>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.status')</h4>
                                <span style="color: {{ $order->status->color }};">{{ $order->status->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.user_email')</h4>
                                <span>{{ $order->user->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.taxes')</h4>
                                <span><span class="money">{{$order->tax() }}</span> ({{ $order->tax_percentage_on_buy }}%)</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::orders.earnings')</h4>
                                <span class="uk-label uk-label-success money">{{ $order->totalPrice() }}</span>
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
                            <div class="uk-width-1-1">
                                @can('status', $order)
                                    <a href="#status_modal" uk-toggle class="change_status uk-width-1-1 uk-button  uk-button-primary">
                                        @lang('laralum_shop::orders.status')
                                    </a>
                                @else
                                <button disabled class="uk-width-1-1 uk-button uk-button-small uk-button-default">
                                    @lang('laralum_shop::orders.status')
                                </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@m uk-width-2-3@l">
                @if ($order->shipping_adress)
                    <div class="uk-card uk-card-default">
                        <div class="uk-card-header">
                            @lang('laralum_shop::orders.shipping_adress')
                        </div>
                        <div class="uk-card-body">
                            <div class="uk-overflow-auto">
                                <dl class="uk-description-list uk-description-list-divider">
                                    <dt>{{ $order->shipping_adress }}</dt>
                                    <dd>
                                        {{ $order->shipping_zip }},
                                        {{ $order->shipping_city }},
                                        {{ $order->shipping_country }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <br />
                @endif
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
        currency: '{{ \Laralum\Shop\Models\Settings::first()->currency }}'
    });
    $(function() {
        $('#change_status').click(function() {
            $('#status_form').submit();
        });
    });
</script>
@endsection
