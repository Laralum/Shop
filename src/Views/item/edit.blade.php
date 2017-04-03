@extends('laralum::layouts.master')
@section('icon', 'ion-edit')
@section('title', __('laralum_shop::items.edit_title', ['id' => $item->id]))
@section('subtitle', __('laralum_shop::items.edit_subtitle', ['id' => $item->id]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><a href="{{ route('laralum::shop.item.index') }}">@lang('laralum_shop::items.title')</a></li>
        <li><a href="{{ route('laralum::shop.item.show', ['item' => $item->id]) }}">{{ $item->name }}</a></li>
        <li><span href="">@lang('laralum_shop::items.edit_title', ['id' => $item->id])</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-5@l"></div>
            <div class="uk-width-1-1@s uk-width-3-5@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-body">
                        <form action="{{ route('laralum::shop.item.update', ['item' => $item->id]) }}" class="uk-form-stacked" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="uk-margin">
                                <div uk-grid class="uk-grid-small">
                                    <div class="uk-width-1-1@l uk-width-3-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.name')</label>
                                        <div class="uk-form-controls">
                                            <input required value="{{ old('name', $item->name) }}" name="name" class="uk-input" type="text" placeholder="@lang('laralum_shop::items.name_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-2-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.price')</label>
                                        <div class="uk-form-controls">
                                            <input required value="{{ old('price', $item->price) }}" name="price" class="uk-input" type="number" min="0" step="0.01" placeholder="@lang('laralum_shop::items.price_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-3-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.category')</label>
                                        <div class="uk-form-controls">
                                            <select required name="category" class="uk-select">
                                                @foreach($categories as $category)
                                                    <option @if($item->category_id == $category->id) selected @endif value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@l uk-width-2-5@xl">
                                        <label class="uk-form-label">@lang('laralum_shop::items.stock')</label>
                                        <div class="uk-form-controls">
                                            <input value="{{ old('stock', $item->stock) }}" name="stock" class="uk-input" type="number" min="0" placeholder="@lang('laralum_shop::items.stock_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <label class="uk-form-label">@lang('laralum_shop::items.description')</label>
                                        <div class="uk-form-controls">
                                            <textarea required name="description" rows="10" class="uk-textarea" placeholder="@lang('laralum_shop::items.description_ph')">{{ old('description', $item->description) }}</textarea>
                                            <small class="uk-text-meta">@lang('laralum_shop::general.markdown_accepted')</small>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="uk-button uk-button-primary">
                                            <span class="ion-forward"></span>&nbsp; @lang('laralum_shop::items.save_item')
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
