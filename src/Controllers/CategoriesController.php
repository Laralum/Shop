<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Category;

class CategoriesController extends Controller
{
    /**
     * Shows all the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('laralum_shop::category.index', ['categories' => Category::all()]);
    }

    /**
     * Shows the create form to create a category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return view('laralum_shop::category.create');
    }

    /**
     * Create a category.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $this->validate($request, [
            'name' => 'required|unique:laralum_shop_categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.category.index')->with('success', __('laralum_shop::categories.created'));
    }

    /**
     * Shows the update form to update a category.
     *
     * @param \Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('laralum_shop::category.edit', ['category' => $category]);
    }

    /**
     * Update a category.
     *
     * @param \Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('laralum_shop_categories')->ignore($category->id),
            ],
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.category.index')->with('success', __('laralum_shop::categories.updated'));
    }

    /**
     * Show the delete confirmation page to delete an item.
     *
     * @param \Laralum\Shop\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function confirmDelete(Category $category)
    {
        $this->authorize('delete', $category);

        return view('laralum::pages.confirmation', [
            'method' => 'DELETE',
            'action' => route('laralum::shop.category.destroy', ['category' => $category]),
        ]);
    }

    /**
     * Delete a category.
     *
     * @param \Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $this->authorize('delete', $category);

        $category->items->each(function($item) {
            $item->update(['category_id' => Category::first()->id]);
        });

        $category->delete();

        return redirect()->route('laralum::shop.category.index')->with('success', __('laralum_shop::categories.deleted'));
    }
}
