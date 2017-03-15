<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Status;

class StatusController extends Controller
{
    /**
     * Shows the create form to create a status.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laralum_shop::status.create');
    }

    /**
     * Create a status.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate([
            'name' => 'required|unique:laralum_shop_status,name',
        ]);

        Status::create([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::status.created'));
    }

    /**
     * Shows the update form to update a status.
     *
     * @param Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        return view('laralum_shop::status.update', ['status' => $status]);
    }

    /**
     * Update a status.
     *
     * @param \Illuminate\Http\Request $request
     * @param Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('laralum_shop_status')->ignore($status->id),
            ],
        ]);

        $status->update([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::status.updated'));
    }

    /**
     * Delete a status.
     *
     * @param \Illuminate\Http\Request $request
     * @param Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Status $status)
    {
        $status->orders->each(function($order) {
            $order->update(['status_id' => 0]);
        });

        $status->delete();

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::status.deleted'));
    }
}
