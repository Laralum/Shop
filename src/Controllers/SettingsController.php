<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Shop\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Update the shop settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('update', Settings::class);

        $this->validate($request, [
            'currency'          => 'required',
            'default_status'    => 'required|exists:laralum_shop_status,id',
            'paid_status'       => 'required|exists:laralum_shop_status,id|different:default_status',
            'public_prefix'     => 'required|string|min:1',
            'tax_percentage'    => 'required|numeric|min:0|max:100',
        ]);

        Settings::first()->update([
            'currency'          => $request->currency,
            'default_status'    => $request->default_status,
            'paid_status'       => $request->paid_status,
            'public_prefix'     => $request->public_prefix,
            'tax_percentage'    => $request->tax_percentage,
            'emergency'         => $request->emergency ? true : false,
        ]);

        return redirect()->route('laralum::settings.index', ['p' => 'Shop'])->with('success', __('laralum_shop::settings.updated'));
    }
}
