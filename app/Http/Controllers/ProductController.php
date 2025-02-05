<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Product;

class ProductController extends Controller
{
    // function index to get all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products, 200);
    }
    // function to store product
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $product = Product::create($input);
        return response()->json($product, 200);
    }
    // show product by id
    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }
    // update product
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
        return response()->json($product, 200);
    }
    // delete product
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }
}
