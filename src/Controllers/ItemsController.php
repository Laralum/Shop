<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Item;
use Laralum\Shop\Models\Category;

class ItemsController extends Controller
{

    /**
     * Shows all the items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::item.index', ['items' => Item::all()]);
    }

    /**
     * Shows all the items in a specific category.
     *
     * @param \Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function category(Category $category)
    {
        return view('laralum_shop::item.index', ['items' => $category->items]);
    }

    /**
     * Shows the item details.
     *
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('laralum_shop::item.show', ['item' => $item]);
    }

    /**
     * Shows the create form to create an item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Item::class);

        return view('laralum_shop::item.create', ['categories' => Category::all()]);
    }

    /**
     * Create an item.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $this->validate($request, [
            'name' => 'required|min:5',
            'description' => 'required|min:15',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:laralum_shop_categories,id',
            'stock' => ($request->stock || $request->stock === 0) ? 'integer' : '',
        ]);

        Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category,
            'stock' => ($request->stock || $request->stock === 0) ? $request->stock : null,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.created'));
    }

    /**
     * Shows the update form to update an item.
     *
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        return view('laralum_shop::item.edit', ['item' => $item]);
    }

    /**
     * Update a category.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $this->validate([
            'name' => 'required|min:5',
            'description' => 'required|min:15',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:laralum_shop_categories,id',
            'stock' => 'integer',
        ]);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category,
            'stock' => ($request->stock || $request->stock === 0) ? $request->stock : null,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.updated'));
    }

    /**
     * Show the delete confirmation page to delete an item.
     *
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function confirmDelete(Item $item)
    {
        $this->authorize('delete', $item);

        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'action' => route('laralum::shop.item.destroy', ['item' => $item]),
        ]);
    }

    /**
     * Delete an item.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::items.deleted'));
    }
}
