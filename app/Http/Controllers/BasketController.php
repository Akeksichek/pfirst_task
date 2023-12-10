<?php

namespace App\Http\Controllers;

use App\Models\basket;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\String\b;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id) 
{ 
    $product_id = Product::join('baskets', 'products.id', '=', 'baskets.products_id')
    ->where('baskets.user_id', $id)
    ->pluck('products.id');
    $product_title = Product::join('baskets', 'products.id', '=', 'baskets.products_id')
    ->where('baskets.user_id', $id)
    ->pluck('products.title');
    $product_price = Product::join('baskets', 'products.id', '=', 'baskets.products_id')
    ->where('baskets.user_id', $id)
    ->pluck('products.price');
    $fullprice = 0;
    for($i = 0; $i < sizeof($product_price); $i++)
    {
        $fullprice += $product_price[$i];
    }

    $products = array();
    for($j = 0; $j < sizeof($product_id); $j++) 
    { 
        array_push($products, array( 
            "id" => $product_id[$j] , 
            "title" => $product_title[$j], 
            "price" => $product_price[$j] 
        ));
    }
    return response()->json([
        "cart" => [
            $products
        ],
        "full_price" => $fullprice
    ]);
}

    public function delBasket(Request $request, $id)
    {
        $request->validate([
            "data" => [
                "products_id" => $request->products_id, // 3 столбец
            ]
        ]);
        $user = User::find($id);
        if($user->role === "user")
        {
            $basket = basket::where('id', $request->products_id)-delete();
            return response()->json([
                "content" => [
                    "info" => "Товар успешно удален",
                    "user_id" => $id,
                ]
            ]);
        }
        else
        {
            return response()->json([
                "warning" => [
                    "code" => 403,
                    "message" => "Невозможно",
                ]
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addBasket(Request $request, $id)
    {
        $request->validate([
            "basket" => [
                "products_id" => $request->products_id,
            ]
        ]);
        $userData = User::find($id);
        if($userData->role === "user")
        {
            $basket = new basket;
            $basket->products_id = $request->products_id;
            $basket->user_id = $id;
            $basket->status = "not_issued"; // не оформлен
            $basket->save();
            return response()->json($basket);
        }
        else
        {
            return response()->json([
                "warning" => [
                    "code" => 403,
                    "message" => "Гостевой доступ запрещен",

                ]-setStatusCode(403)
                ]);
        }
    }

    public function addPurchase(Request $request, $id) 
    { 
        $request->validate([ 
            "products_id" => "required" 
        ]); 
        $user = User::find($id); 

        if($user->role === "user") 
        {     
            $basket = basket::find($request->products_id); 
            if($basket->status === "not_issued") 
            { 
                $basket->update([ 
                    'status' => "issued" 
                ]); 
                return response()->json([ 
                    "success" => "Товар успешно добавлен в список покупок" 
                ]); 
            } 
            else{ 
                return response()->json([ 
                    "error" => "Товар уже добавлен в список покупок" 
                ]); 
            } 
        } 
        else 
        { 
            return response()->json([ 
                "error" => "Недоступно" 
            ]); 
        } 
    }

    public function purchase($id)
    {
        $product_id = Product::join('baskets', 'products.id', '=', 'baskets.products_id') 
        ->where('baskets.user_id', $id)
        ->where('baskets.status', 'issued')
        ->pluck('products.id'); 
        $product_title = Product::join('baskets', 'products.id', '=', 'baskets.products_id') 
        ->where('baskets.user_id', $id)
        ->where('baskets.status', 'issued')
        ->pluck('products.title'); 
        $product_price = Product::join('baskets', 'products.id', '=', 'baskets.products_id') 
        ->where('baskets.user_id', $id)
        ->where('baskets.status', 'issued')
        ->pluck('products.price'); 
        $fullprice = 0; 
        for($i = 0; $i < sizeof($product_price); $i++)
        { 
            $fullprice += $product_price[$i]; 
        } 

        $products = array(); 
        for($j = 0; $j < sizeof($product_id); $j++)  
        {  
            array_push($products, array(  
                "id" => $product_id[$j] ,  
                "title" => $product_title[$j],  
                "price" => $product_price[$j]  
            )); 
        } 
        return response()->json([ 
            "cart" => [ 
                $products 
            ], 
            "full_price" => $fullprice 
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
