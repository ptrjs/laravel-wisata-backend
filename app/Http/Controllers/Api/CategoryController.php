<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     //index
     public function index(Request $request)
     {
        $query = Category::when($request->keyword, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->keyword}%")
                ->orWhere('description', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc');

        // Eager load 'products' relationship
        $categories = $query->with('products')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $categories->items(), // Ambil items() untuk mengambil data aktual
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'next_page_url' => $categories->nextPageUrl(), // URL halaman berikutnya
                'prev_page_url' => $categories->previousPageUrl(), // URL halaman sebelumnya
            ],
        ], 200);
     }

      //store
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;

        $category->save();

        return response()->json(['status' => 'success', 'data' => $category], 200);

    }


     //show
    public function show($id){
        $category = Category::find($id);
        if(!$category){
            return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $category], 200);
    }

       //update
       public function update(Request $request, $id){
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
        }

        $category->name = $request->name;
        $category->description = $request->description;

        $category->save();

        return response()->json(['status' => 'success', 'data' => $category], 200);

    }

    //destroy
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['status' => 'success', 'message' => 'Category deleted'], 200);
    }
}
