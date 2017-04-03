<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Status;

class StatusController extends Controller
{
    /**
     * Show all the status.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::status.index', ['status' => Status::all()]);
    }

    /**
     * Shows the create form to create a status.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Status::class);

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
        $this->authorize('create', Status::class);

        $this->validate($request, [
            'name' => 'required|unique:laralum_shop_status,name',
            'color' => 'required',
        ]);

        Status::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('laralum::shop.status.index')->with('success', __('laralum_shop::status.created'));
    }

    /**
     * Shows the update form to update a status.
     *
     * @param \Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        $this->authorize('update', $status);

        return view('laralum_shop::status.edit', ['status' => $status]);
    }

    /**
     * Update a status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $this->authorize('update', $status);

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('laralum_shop_status')->ignore($status->id),
            ],
            'color' => 'required',
        ]);

        $status->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('laralum::shop.status.index')->with('success', __('laralum_shop::status.updated'));
    }

    /**
     * Show the delete confirmation page to delete a status.
     *
     * @param \Laralum\Shop\Models\Item $status
     * @return \Illuminate\Http\Response
     */
    public function confirmDelete(Status $status)
    {
        $this->authorize('delete', $status);

        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'action' => route('laralum::shop.status.destroy', ['status' => $status]),
        ]);
    }

    /**
     * Delete a status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Status $status)
    {
        $this->authorize('delete', $status);

        $status->orders->each(function($order) {
            $order->update(['status_id' => 0]);
        });

        $status->delete();

        return redirect()->route('laralum::shop.status.index')->with('success', __('laralum_shop::status.deleted'));
    }
}
