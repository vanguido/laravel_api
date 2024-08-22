<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;;

class ProductController extends Controller
{
    public function index() 
    {

        $products = Product::get();
        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['message' => 'No products found'], 200);
        }
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->messages(),
            ], 400);
        }         

        $product = Product::create($request->all());

        return response()->json(['message' => 'Product created successfully', 
                                'data' => new ProductResource($product)], 200);
    }

    public function show(Product $product) 
    {
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->messages(),
            ], 400);
        }      

        $product->update($request->all());

        return response()->json(['message' => 'Product updated successfully', 
                                'data' => new ProductResource($product)], 200);

    }

    public function destroy(Product $product) 
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
