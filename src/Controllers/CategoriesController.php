<?php

namespace Laralum\Shop\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laralum\Shop\Models\Category;

class CategoriesController extends Controller
{
    /**
     * Shows the create form to create a category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laralum_shop::category.create');
    }

    /**
     * Create a category.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate([
            'name' => 'required|unique:laralum_shop_categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::categories.created'));
    }

    /**
     * Shows the update form to update a category.
     *
     * @param Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('laralum_shop::category.update', ['category' => $category]);
    }

    /**
     * Update a category.
     *
     * @param Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate([
            'name' => [
                'required',
                Rule::unique('laralum_shop_categories')->ignore($category->id),
            ],
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::categories.updated'));
    }

    /**
     * Delete a category.
     *
     * @param Laralum\Shop\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category)
    {
        $category->items->each(function($item) {
            $item->update(['category_id' => 0]);
        });

        $category->delete();

        return redirect()->route('laralum::shop.index')->with('success', __('laralum_shop::categories.deleted'));
    }
}
