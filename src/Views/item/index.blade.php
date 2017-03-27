@extends('laralum::layouts.master')
@section('icon', 'ion-android-cart')
@section('title', __('laralum_shop::items.title'))
@section('subtitle', __('laralum_shop::items.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::items.title')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::items.item_list')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('laralum_shop::items.name')</th>
                                        <th>@lang('laralum_shop::items.price')</th>
                                        <th>@lang('laralum_shop::items.stock')</th>
                                        <th>@lang('laralum_shop::items.category')</th>
                                        <th>@lang('laralum_shop::items.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td><span class="money">{{ $item->price }}</span></td>
                                            <td>{{ $item->showStock() }}</td>
                                            <td>{{ $item->category->name }}</td>
                                            <td class="uk-table-shrink">
                                                <div class="uk-button-group">
                                                    <a href="{{ route('laralum::shop.item.show', ['item' => $item->id]) }}" class="uk-button uk-button-small uk-button-default">
                                                        @lang('laralum_shop::items.show')
                                                    </a>
                                                    @can('delete', $item)
                                                        <a href="{{ route('laralum::shop.item.delete', ['item' => $item->id]) }}" class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::items.delete')
                                                        </a>
                                                    @else
                                                        <button disabled class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::items.delete')
                                                        </button>
                                                    @endcan
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
