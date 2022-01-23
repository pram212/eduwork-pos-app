<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menambahkan Kategori Baru (" . $category->name. ")",
        ]);

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
        ]);

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Mengubah Kategori (" . $category->name. ")",
        ]);
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menghapus Kategori (" . $category->name. ")",
        ]);

        $category->delete();
        return response()->json($category);
    }
}
