<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('category/index');
    }

    public function create()
    {
        return view('category/create');
    }

    public function store(Request $request)
    {
        $category = Category::where(['name' => $request->name])->first();
        if (!empty($category)) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori sudah ada!'
            ]);
        }
        $category = new Category;
        $category->name = $request->name;
        return response()->json([
            'success' => $category->save()
        ]);
    }
    public function show($id)
    {
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        return response()->json([
            'success' => $category->save()
        ]);
    }

    public function destroy($id)
    {
        $delete = Category::find($id)->delete();
        return response()->json(['success' => $delete]);
    }

    public function category_datatables()
    {
        $categories = Category::orderBy("created_at", "DESC")->get();
        return DataTables::of($categories)->make(true);
    }
}
