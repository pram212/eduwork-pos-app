<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Activity;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('lihat kategori');

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
        $this->authorize('tambah kategori');

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
        $this->authorize('edit kategori');

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
        $this->authorize('hapus kategori');

        Activity::create([
            'user_id' =>  Auth::id(),
            'activity' => "Menghapus Kategori (" . $category->name. ")",
        ]);

        $category->delete();
        return response()->json($category);

    }
}
