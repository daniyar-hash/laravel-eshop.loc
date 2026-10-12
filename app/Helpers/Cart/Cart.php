<?php

namespace App\Helpers\Cart;

use App\Models\Product;

class Cart 
{


public static function addCart(int $productId, int $quantity = 1): bool
{
    $addedPr = false;
   

    if(self::hasProductInCart($productId)){


            session(["cart.{$productId}.quantity" => session("cart.{$productId}.quantity") + $quantity]);
            $addedPr = true;

    } else{
        
            $product = Product::query()->find($productId);
            // $product = Product::query()->findOrFail($productId);

            if($product){
                $new_product = [
                'title' => $product->title,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => $product->price,
                'quantity' => $quantity,
                    ];

                    session(["cart.{$productId}" => $new_product]);
                    $addedPr = true;
            }
            
    }

    return $addedPr;
}



public static function hasProductInCart(int $productId): bool
{
    
    return session()->has("cart.$productId");
}




public static function getCart(): array
{
    return session('cart') ?: [];
}


public static function getCartQuantityItems(): int 
{
    return count(self::getCart());
}

public static function getCartQuantityTotal(): int 
{
    return array_sum(array_column(self::getCart(), 'quantity'));
}

public static function getTotalProducts(): int
{
    $total = 0;
    $count = self::getCart();
    foreach($count as $item){
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

public static function deleteProductFromCart(int $productId): bool 
{
    if(self::hasProductInCart($productId)){
        session()->forget("cart.{$productId}");
        return true;
    }else{
        return false;
    }
}






}

