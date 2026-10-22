<?php

namespace App\Helpers\Traits;

use App\Helpers\Cart\Cart;


trait CartTrait {

    public int $quantity = 1; 

    public function addCart(int $productId, $quantity = false)
    {

        $quantity = $quantity ? $this->quantity : 1;
        
        if($quantity < 1){
            $quantity = 1;
        }
       
        // $res = Cart::addCart($productId, $quantity);


            if(Cart::addCart($productId, $quantity)){
                $this->js("toastr.success('Product added in Cart successfully!')");
                $this->dispatch('cart-updated');
            }   else{
                
              $this->js("toastr.error('Oops! Something went wrong!')");

            }    
                
    }

    public function deleteFromCart(int $productId): void
    {
        if(Cart::deleteProductFromCart($productId)){
             $this->js("toastr.success('Product deleted from Cart successfully!')");
             $this->dispatch('cart-updated');
        }else{
             $this->js("toastr.error('Oops! Something went wrong!')");
        }
    }
     

}


/*

'cart' => [
    1 => [
        'title' => '',
        'slug' => '',
        'image' => '',
        'price' => '',
        'old_price' => '',
        'quantity' => 4,
    ],
    5 => [
        'title' => '',
        'slug' => '',
        'image' => '',
        'price' => '',
        'old_price' => '',
        'quantity' => 3,
    ],
]

 * */