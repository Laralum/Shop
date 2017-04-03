@extends('laralum::layouts.master')
@section('icon', 'ion-edit')
@section('title', __('laralum_shop::status.edit_title', ['id' => $status->id]))
@section('subtitle', __('laralum_shop::status.edit_subtitle', ['id' => $status->id]))
@section('breadcrumb')
    <ul class="uk-breadcrumb">
        <li><a href="{{ route('laralum::index') }}">@lang('laralum_shop::general.home')</a></li>
        <li><a href="{{ route('laralum::shop.status.index') }}">@lang('laralum_shop::status.title')</a></li>
        <li><span href="">@lang('laralum_shop::status.edit_title', ['id' => $status->id])</span></li>
    </ul>
@endsection
@section('content')
    <div class="uk-container uk-container-large">
        <div uk-grid>
            <div class="uk-width-1-1@s uk-width-1-3@l"></div>
            <div class="uk-width-1-1@s uk-width-1-3@l">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-body">
                        <form action="{{ route('laralum::shop.status.update', ['status' => $status->id]) }}" class="uk-form-stacked" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="uk-margin">
                                <div uk-grid class="uk-grid-small">
                                    <div class="uk-width-1-1">
                                        <label class="uk-form-label">@lang('laralum_shop::status.name')</label>
                                        <div class="uk-form-controls">
                                            <input required value="{{ old('name', $status->name) }}" name="name" class="uk-input" type="text" placeholder="@lang('laralum_shop::status.name_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <label class="uk-form-label">@lang('laralum_shop::status.color')</label>
                                        <div class="uk-form-controls">
                                            <input required value="{{ old('color', $status->color) }}" name="color" class="uk-input" type="text" placeholder="@lang('laralum_shop::status.color_ph')">
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1">
                                        <button type="submit" class="uk-button uk-button-primary">
                                            <span class="ion-forward"></span>&nbsp; @lang('laralum_shop::status.save_status')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-1@s uk-width-1-3@l"></div>
        </div>
    </div>
@endsection
