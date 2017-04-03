@extends('laralum::layouts.master')
@section('icon', 'ion-cube')
@section('title', $item->name)
@section('subtitle', __('laralum_shop::items.show_subtitle', ['id' => $item->id]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><a href="{{ route('laralum::shop.item.index') }}">@lang('laralum_shop::items.title')</a></li>
        <li><span href="">{{ $item->name }}</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@m uk-width-1-3@l">
                <div class="uk-card uk-card-default" uk-sticky="media: 640; bottom: true; offset: 120;">
                    <div class="uk-card-header">
                        @lang('laralum_shop::items.item_details')
                    </div>
                    <div class="uk-card-body">
                        <div uk-grid>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.name')</h4>
                                <span>{{ $item->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.price')</h4>
                                <span class="money">{{ $item->price }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.stock')</h4>
                                @if ($item->available())
                                    <span class="uk-label uk-label-success">{{ $item->showStock() }}</span>
                                @else
                                    <span class="uk-label uk-label-danger">{{ $item->showStock() }}</span>
                                @endif
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.category')</h4>
                                <span>{{ $item->category->name }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.sales')</h4>
                                <span>{{ $item->sales() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.earnings')</h4>
                                <span class="money">{{ $item->earnings() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.creation_date')</h4>
                                <span>{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="uk-width-1-2">
                                <h4>@lang('laralum_shop::items.updated_date')</h4>
                                <span>{{ $item->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="uk-width-1-1">
                                @can('update', $item)
                                    <a href="{{ route('laralum::shop.item.edit', ['item' => $item->id]) }}" class="uk-button uk-button-primary uk-width-1-1">@lang('laralum_shop::items.edit')</a>
                                @else
                                    <button disabled class="uk-button uk-button-primary uk-width-1-1">@lang('laralum_shop::items.edit')</button>
                                @endcan
                                <br /><br />
                                @can('update', $item)
                                    <a href="{{ route('laralum::shop.item.delete', ['item' => $item->id]) }}" class="uk-button uk-button-danger uk-width-1-1">@lang('laralum_shop::items.delete')</a>
                                @else
                                    <button disabled class="uk-button uk-button-primary uk-width-1-1">@lang('laralum_shop::items.delete')</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@m uk-width-2-3@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::items.item_details')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            {{ $item->description }}
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
