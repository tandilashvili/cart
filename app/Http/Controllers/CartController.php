<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    
    // Adds the product to cart with default quantity equal to 1
    public function addProductInCart(Request $request){
        $cart = new Cart;
        $data = $request->all();
        $cart->product_id = $data['product_id'];

        $cart->user_id = 15; //auth()->id();
        $cart->quantity = 1;

        $cart->save();

        return response()->json([
            'success' => true,
            'data' => [
                'inserted_id' => $cart->id
            ]
        ]);
    }




    // Removes the product from cart
    public function removeProductFromCart(Request $request){
        $data = $request->all();
        $product_id = $data['product_id'];
        $user_id = 15; //auth()->id();
        $cart = Cart::where('product_id', $product_id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'deleted successfully'
            ]
        ]);
    }




    // Updates the quantity of the product in cart
    public function setCartProductQuantity(Request $request){
        $user_id = 15; //auth()->id();
        $data = $request->all();

        $cart = Cart::where('product_id', $data['product_id'])->where('user_id', $user_id)->first();
        $cart->quantity = $data['quantity'];
        $cart->save();

        return response()->json([
            'success' => true,
            'data' => [
                'updated_id' => $cart->id
            ]
        ]);
    }




    // returns all the products (with discount amount) that the client put into cart 
    public function getUserCart(){
        $user_id = 15; // auth()->id();

        // Selects all products with price, discount, group_id and other details
        $cart_products = DB::table('carts')
            ->selectRaw('carts.*, products.price , products.title, user_product_groups.discount, user_product_groups.group_id')
            ->where('carts.user_id', $user_id)
            ->leftJoin('products', 'products.product_id', '=', 'carts.product_id')
            ->leftJoin('product_group_items', 'product_group_items.product_id', '=', 'products.product_id')
            ->leftJoin('user_product_groups', 'user_product_groups.group_id', '=', 'product_group_items.group_id')
            ->get();

        $product_ids = $cart_products->pluck('product_id'); //1,2,5


        // Selects all target groups with products count bought by the client
        $bought_products_on_groups = DB::table('product_group_items')
            ->whereIn('product_id', $product_ids)
            ->select('group_id', DB::raw('count(*) as cnt'))
            ->groupBy('group_id')
            ->get();

        $target_group_ids = $bought_products_on_groups->pluck('group_id');


        // Selects all target groups with their product counts
        $all_products_on_groups = DB::table('product_group_items')
            ->whereIn('group_id', $target_group_ids)
            ->select('group_id', DB::raw('count(*) as cnt'))
            ->groupBy('group_id')
            ->get();


        // Puts the groups into the array if all products are bought completely in the group
        $completed_groups = [];
        $min = 0;
        foreach($bought_products_on_groups as $key => $bought_products_on_group){
            $group_all_products = $all_products_on_groups[$key];

            // If all products are bought from discount group
            if($group_all_products->cnt == $bought_products_on_group->cnt){ 
                $completed_groups[$group_all_products->group_id] = true;
            }
        }

        // Min product quantity on each discount group
        // Discountable quantity if all products are bought from discount group
        $discountable = []; 
        foreach($cart_products as $product){
            $group_id = $product->group_id;
            $discount = $product->discount;

            $has_discount = isset($completed_groups[$group_id]);

            if($group_id && $has_discount){
                if(!isset($discountable[$group_id])){
                    $discountable[$group_id] = $product->quantity;
                }else{
                    if($product->quantity < $discountable[$group_id]){
                        $discountable[$group_id] = $product->quantity;
                    }
                }
            }
        }


        $discount_all = 0;
        $result = [
            'products' => [],
        ];
        

        // Calculates discount & prepares the result
        foreach($cart_products as $product){
            $group_id = $product->group_id;
            $price = floatval($product->price);
            $discount = $product->discount;

            $result['products'][] = [
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'price' => $price,
            ];

            $has_discount = isset($completed_groups[$group_id]);
            if($has_discount){
                $quantity = $discountable[$group_id];
                $discount_all += $quantity * $price * ($discount/100);
            }
        }
        $result['discount'] = $discount_all;

        return response()->json($result);
    }

}
