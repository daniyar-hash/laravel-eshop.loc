<?php

use Livewire\Component;
use App\Helpers\Cart\Cart;
use Livewire\Attributes\On; 

new class extends Component
{

     #[On('cart-updated')]   
     public function updateCartIcon(){
      
      

     }
    
};
?>

<div>
    <button class="btn p-1" id="cart-open" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="badge text-bg-warning cart-badge bg-warning rounded-circle">
            {{-- {{Cart::getCartQuantityItems()}} --}}
            {{Cart::getCartQuantityTotal()}}
        </span>
         {{-- {{Cart::getCartQuantityTotal()}} --}}
    </button>
</div>