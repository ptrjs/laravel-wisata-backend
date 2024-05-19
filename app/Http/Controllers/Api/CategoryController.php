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
         $categories = Category::when($request->keyword, function ($query) use ($request) {
             $query->where('name', 'like', "%{$request->keyword}%")
                 ->orWhere('description', 'like', "%{$request->keyword}%");
         })->orderBy('id', 'desc')->paginate(10);

         return response()->json([
            'status'=>'success',
            'data'=>$categories
        ],200);
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
