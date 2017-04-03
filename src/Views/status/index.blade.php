@extends('laralum::layouts.master')
@section('icon', 'ion-flag')
@section('title', __('laralum_shop::status.title'))
@section('subtitle', __('laralum_shop::status.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::status.title')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::status.title')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('laralum_shop::status.name')</th>
                                        <th>@lang('laralum_shop::status.color')</th>
                                        <th>@lang('laralum_shop::status.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($status as $s)
                                        <tr>
                                            <td>{{ $s->id }}</td>
                                            <td><span style="color: {{ $s->color }};">{{ $s->name }}</span></td>
                                            <td>{{ $s->color }}</td>
                                            <td class="uk-table-shrink">
                                                <div class="uk-button-group">
                                                    @can('update', $s)
                                                        <a href="{{ route('laralum::shop.status.edit', ['status' => $s->id]) }}" class="uk-button uk-button-small uk-button-default">
                                                            @lang('laralum_shop::status.edit')
                                                        </a>
                                                    @else
                                                        <button disabled class="uk-button uk-button-small uk-button-default">
                                                            @lang('laralum_shop::status.edit')
                                                        </button>
                                                    @endcan
                                                    @can('delete', $s)
                                                        <a href="{{ route('laralum::shop.status.delete', ['status' => $s->id]) }}" class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::status.delete')
                                                        </a>
                                                    @else
                                                        <button disabled class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::status.delete')
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
