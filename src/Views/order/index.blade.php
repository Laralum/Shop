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
    <div class="uk-container uk-container-large" id='orders_app'>
        <div id="status_modal" uk-modal="center: true">
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    <h2 class="uk-modal-title">@lang('laralum_shop::orders.order') #<span id='order_id'></span></h2>
                </div>
                <div class="uk-modal-body">
                    <form id='status_form' method="POST" action="">
                        {{ csrf_field() }}
                        <center>
                            <div class="uk-width-1-1@l uk-width-3-5@xl">
                                <label class="uk-form-label">@lang('laralum_shop::orders.status')</label>
                                <div class="uk-form-controls">
                                    <select required name="status" id="status_select" class="uk-select">
                                        @foreach($status as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
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
            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default uk-card-body">
                    <form method="POST" action="{{ route('laralum::shop.order.filter') }}">
                        {{ csrf_field() }}
                        <div uk-grid>
                            <div class="uk-width-2-5@l uk-width-1-5@xl">
                                <label class="uk-form-label">@lang('laralum_shop::orders.status')</label>
                                <div class="uk-form-controls">
                                    <select required name="status" class="uk-select">
                                        @foreach($status as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="uk-width-3-5@l uk-width-4-5@xl"><br />
                                <button type="submit" class="uk-button uk-button-primary" type="button">@lang('laralum_shop::orders.filter')</button>
                                @if($filtered)
                                    <a href="{{ route('laralum::shop.order.index') }}" class="uk-button uk-button-danger" type="button">@lang('laralum_shop::orders.remove_filter')</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <br />
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
                                            <td><span style="color: {{ $order->status->color }};">{{ $order->status->name }}</span></td>
                                            <td class="uk-table-shrink">
                                                <div class="uk-button-group">
                                                    @can('status', $order)
                                                        <a href="#status_modal" data-id="{{ $order->id }}" data-status="{{ $order->status->id }}" data-url="{{ route('laralum::shop.order.status', ['order' => $order->id]) }}" uk-toggle class="change_status uk-button uk-button-small uk-button-default">
                                                            @lang('laralum_shop::orders.status')
                                                        </a>
                                                    @else
                                                    <button disabled class="uk-button uk-button-small uk-button-default">
                                                        @lang('laralum_shop::orders.status')
                                                    </button>
                                                    @endcan
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
    $(function() {
        $('.change_status').click(function() {
            $('#order_id').text($(this).data('id'));
            $('#status_form').attr('action', $(this).data('url'));
            $('#status_select').val($(this).data('status'));
        });
        $('#change_status').click(function() {
            $('#status_form').submit();
        });
    });
</script>
@endsection
