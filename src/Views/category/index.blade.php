@extends('laralum::layouts.master')
@section('icon', 'ion-android-star')
@section('title', __('laralum_shop::categories.title'))
@section('subtitle', __('laralum_shop::categories.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::categories.title')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-5@l uk-width-1-1@m"></div>
            <div class="uk-width-3-5@l uk-width-1-1@m">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-header">
                        @lang('laralum_shop::categories.category_list')
                    </div>
                    <div class="uk-card-body">
                        <div class="uk-overflow-auto">
                            <table class="uk-table uk-table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('laralum_shop::categories.name')</th>
                                        <th>@lang('laralum_shop::categories.options')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td class="uk-table-shrink">
                                                <div class="uk-button-group">
                                                    @can('update', $category)
                                                        <a href="{{ route('laralum::shop.category.edit', ['category' => $category->id]) }}" class="uk-button uk-button-small uk-button-default">
                                                            @lang('laralum_shop::categories.edit')
                                                        </a>
                                                    @else
                                                        <button disabled class="uk-button uk-button-small uk-button-default">
                                                            @lang('laralum_shop::categories.edit')
                                                        </button>
                                                    @endcan
                                                    @can('delete', $category)
                                                        <a href="{{ route('laralum::shop.category.delete', ['category' => $category->id]) }}" class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::categories.delete')
                                                        </a>
                                                    @else
                                                        <button disabled class="uk-button uk-button-small uk-button-danger">
                                                            @lang('laralum_shop::categories.delete')
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
            <div class="uk-width-1-5@l uk-width-1-1@m"></div>
        </div>
    </div>
@endsection
