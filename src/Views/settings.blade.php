@php
    $settings = Laralum\Shop\Models\Settings::first();
    $status = \Laralum\Shop\Models\Status::all();
@endphp
<div uk-grid>
    @can('update', \Laralum\Shop\Models\Settings::class)
    <div class="uk-width-1-1@s uk-width-1-5@l"></div>
    <div class="uk-width-1-1@s uk-width-3-5@l">
        <form class="uk-form-horizontal" method="POST" action="{{ route('laralum::shop.settings.update') }}">
            {{ csrf_field() }}
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.currency')</label>
                    <div class="uk-form-controls">
                        <select required name="currency" class="uk-select">
                            @foreach($settings->currencies() as $currency)
                                <option @if ($settings->currency == $currency['code']) selected @endif value="{{ $currency['code'] }}">
                                    {{ $currency['name'] }} ({{  $currency['symbol'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.default_status')</label>
                    <div class="uk-form-controls">
                        <select required name="default_status" class="uk-select">
                            @foreach($status as $s)
                                <option @if ($s->id == $settings->default_status) selected @endif value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.paid_status')</label>
                    <div class="uk-form-controls">
                        <select required name="paid_status" class="uk-select">
                            @foreach($status as $s)
                                <option @if ($s->id == $settings->paid_status) selected @endif value="{{ $s->id }}">
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.public_prefix')</label>
                    <div class="uk-form-controls">
                        <input value="{{ old('public_prefix', $settings->public_prefix) }}" name="public_prefix" class="uk-input" type="text" placeholder="@lang('laralum_shop::settings.public_prefix_ph')">
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.tax_percentage')</label>
                    <div class="uk-form-controls">
                        <input value="{{ old('tax_percentage', $settings->tax_percentage) }}" name="tax_percentage" class="uk-input" type="number" min="0" max="100" step="0.01" placeholder="@lang('laralum_shop::settings.tax_percentage_ph')">
                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label">@lang('laralum_shop::settings.emergency')</label>
                    <div class="uk-form-controls">
                        <label><input name="emergency" @if($settings->emergency) checked @endif class="uk-checkbox" type="checkbox"> @lang('laralum_shop::settings.enabled')</label><br />
                        <small class="uk-text-meta">@lang('laralum_shop::settings.emergency_hp')</small>
                    </div>
                </div>

                <div class="uk-margin uk-align-right">
                    <button type="submit" class="uk-button uk-button-primary">
                        <span class="ion-forward"></span>&nbsp; @lang('laralum_shop::settings.save')
                    </button>
                </div>

            </fieldset>
        </form>
    </div>
    <div class="uk-width-1-1@s uk-width-1-5@l"></div>
    @else
        <div class="uk-width-1-1">
            <div class="content-background">
                <div class="uk-section uk-section-small uk-section-default">
                    <div class="uk-container uk-text-center">
                        <h3>
                            <span class="ion-minus-circled"></span>
                            @lang('laralum_shop::settings.unauthorized_action')
                        </h3>
                        <p>
                            @lang('laralum_shop::settings.unauthorized_desc')
                        </p>
                        <p class="uk-text-meta">
                            @lang('laralum_shop::settings.contact_webmaster')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endcan
</div>
