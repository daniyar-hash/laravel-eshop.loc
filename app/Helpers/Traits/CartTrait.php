<?php

namespace App\Helpers\Traits;

use App\Helpers\Cart\Cart;


trait CartTrait {

    public $quantity = 1; 

    public function addCart(int $productId, $quantity = false)
    {

        $quantity = $quantity ? (int)$this->quantity : 1;
        
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

    public function updateItemQuanity(int $productId, int $quantity)
    {
        if(Cart::updateQuantity($productId, $quantity)){
             $this->js("toastr.success('Quantity updated!')");
             $this->dispatch('cart-updated');
        }else{
            $this->js("toastr.error('Error updating!')");
        }
    }

    public function clearCart(int $productId)
    {
        Cart::clearCart($productId);
        $this->js("toastr.info('Cart cleared!')");
        $this->dispatch('cart-updated');
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