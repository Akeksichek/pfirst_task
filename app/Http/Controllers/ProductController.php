<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\basket;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::all();
        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function product($id)
    {
        $product = Product::find($id);
        return response()->json([
            'title' => $product->title,
            'body' => $product->body,
            'price' => $product->price,
        ]);
    }

    public function addProduct(Request $request, $id)
    {
        $request->validate([
            "product" => [
                "title" => $request->title,
                "body" => $request->body,
                "price" => $request->price,
            ]
        ]);

        $user = User::find($id);
        if ($user->role === "admin") {
            $product = new Product;
            $product->title = $request->title;
            $product->body = $request->body;
            $product->price = $request->price;
            $product->save();
            return response()->json([
                "content" => [
                    "data" => "товар добавлен"
                ]
            ]);
        } else {
            return response()->json([
                "warn" => [
                    "code" => 403,
                    "text" => "Вашей группе запрещено это действие!!!",
                ]
            ])->setStatusCode(403);
        }
    }

    public function delProduct(Request $request, $user_id)
    {
        
        $request->validate([
            "delete" => [
                "id" => $request->id, // id товара
            ]
        ]);

        $user = User::find($user_id);
        if($user->role === "admin")
        {
            $basket = basket::where('products_id', $request->id)->delete();
            $product = Product::where('id', $request->id)->delete();
            
            return response()->json([
                "data" => [
                    "info" => "Товар успешно удален"
                ]
            ]);
        }
    }




    
    //-----------------------------Не доделал-----------------------------------\\
    
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            "data" => [
                "product_id" => $request->product_id,
                "title" => $request->title,
                "body" => $request->body,
                "price" => $request->price,
                ]
        ]);

        $product = Product::find($request->product_id)->update([
            "title" => $request->title,
            "body" => $request->body,
            "price" => $request->price,
        ]);

        return $product;

    }
    //-----------------------------Не доделал-----------------------------------\\
        



        /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
