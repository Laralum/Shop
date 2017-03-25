@extends('laralum::layouts.master')
@section('icon', 'ion-plus-round')
@section('title', __('laralum_shop::items.title'))
@section('subtitle', __('laralum_shop::items.subtitle'))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><span href="">@lang('laralum_shop::items.create')</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-5@l"></div>
            <div class="uk-width-1-1@s uk-width-3-5@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-body">
                        <form action="{{ route('laralum::shop.item.store') }}" class="uk-form-stacked" method="POST">
                            {{ csrf_field() }}
                            <div class="uk-margin">
                                <div uk-grid class="uk-grid-small">
                                    <div class="uk-width-1-1@l uk-width-3-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.name')</label>
                                        <div class="uk-form-controls">
                                            <input value="{{ old('name') }}" name="name" class="uk-input" type="text" placeholder="@lang('laralum_shop::items.name_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-2-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.price')</label>
                                        <div class="uk-form-controls">
                                            <input value="{{ old('price') }}" name="price" class="uk-input" type="number" min="0" step="0.01" placeholder="@lang('laralum_shop::items.price_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-3-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.category')</label>
                                        <div class="uk-form-controls">
                                            <select name="anti_ad_block_method" id="anti_ad_block_method" class="uk-select">
                                                <option value="0">@lang('laralum_shop::categories.uncategorized')</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="uk-text-meta">@lang('laralum_shop::items.category_ph')</small>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-2-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.stock')</label>
                                        <div class="uk-form-controls">
                                            <input value="{{ old('stock') }}" name="stock" class="uk-input" type="number" min="0" placeholder="@lang('laralum_shop::items.stock_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <label class="uk-form-label">@lang('laralum_shop::items.description')</label>
                                        <div class="uk-form-controls">
                                            <textarea name="description" rows="10" class="uk-textarea" placeholder="@lang('laralum_shop::items.description_ph')">{{ old('description') }}</textarea>
                                            <small class="uk-text-meta">@lang('laralum_shop::general.markdown_accepted')</small>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="uk-button uk-button-primary">
                                            <span class="ion-forward"></span>&nbsp; @lang('laralum_shop::items.create')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-1-5@l"></div>
        </div>
    </div>
@endsection
