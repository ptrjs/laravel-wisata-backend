<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //index
    public function index(Request $request){
        $products = Product::with('category')
        ->when($request->status, function ($query) use ($request){
            $query->where('status', 'like', "%{$request->status}%");
        })
        ->when($request->category, function ($query) use ($request) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->category}%");
            });
        })
        ->orderBy('favorite', 'desc')->get();

        return response()->json([
            'status'=>'success',
            'data'=>$products
        ],200);
    }

    //store
    public function store(Request $request){
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required',
            //'image' => 'required',
            'criteria' => 'required',
           // 'favorite' => 'required',
           // 'status' => 'required',
            //'stock' => 'required',
        ]);

        $product = new Product;
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->criteria = $request->criteria;
        $product->favorite = false;
        $product->status = 'published';
        $product->stock = 0;
        $product->save();

        //image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = 'products/' . $product->id . '.' . $image->extension();
            $image->storeAs('products', $imagePath);
            $product->image = $imagePath;
            $product->save();
        }

        $product = Product::with('category')->find($product->id);
         return response()->json(['status' => 'success', 'data' => $product], 200);

    }

    //show
    public function show($id){
        $product = Product::find($id);
        if(!$product){
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    //update
     public function update(Request $request, $id)
     {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        // $product->category_id = $request->category_id;
         $product->name = $request->name;
        // $product->description = $request->description;
         $product->price = $request->price;
        // $product->criteria = $request->criteria;
        // $product->favorite = $request->favorite;
        // $product->status = $request->status;
        // $product->stock = $request->stock;
         $product->save();


         //check if image is not empty
        //  if ($request->hasFile('image')) {
        //     // Delete the old image if it exists
        //     if ($product->image) {
        //         Storage::delete('products/' . $product->image);
        //     }
        //  $image = $request->file('image');
        //  $image->storeAs('products/', $product->id . '.' . $image->extension());
        //  $product->image = 'products/' . $product->id . '.' . $image->extension();
        //  $product->save();

        // }
        $product = Product::with('category')->find($product->id);
         return response()->json(['status' => 'success', 'data' => $product], 200);
     }

        //destroy
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product->image) {
            Storage::delete('products/' . $product->image);
        }
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Product deleted'], 200);
    }
}
