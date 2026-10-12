<?php

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Helpers\Cart\Cart;
use App\Helpers\Traits\CartTrait;


new class extends Component
{

    use CartTrait;

      #[On('cart-updated')]
        public function updateCartModal(){
            
        }

      

};
?>

   <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel" wire:ignore.self>
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasCartLabel">Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            @if(Cart::getCart())


   <div class="table-responsive">
            <table class="table offcanvasCart-table">
                <tbody>
                    @foreach(Cart::getCart() as $id => $item)
                    
                        <tr wire:key=$id>
                            <td class="product-img-td"><a href="{{route('product')}}"><img src="{{$item['image']}}" alt=""></a>
                            </td>
                            <td><a href="{{route('product')}}">{{$item['title']}}</a></td>
                            <td>${{$item['price']}}</td>
                            <td>&times;{{$item['quantity']}}</td>
                            <td><button class="btn btn-danger"
                                 wire:click="deleteFromCart({{$id}})"
                                 wire:loading.attr="disabled"
                                 wire:target="deleteFromCart">
                                <i class="fa-regular fa-circle-xmark"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total:</td>
                    <td>${{Cart::getTotalProducts()}}</td>
                </tr>
                </tfoot>
            </table>
    </div>
        <div class="text-end mt-3">
            <a href="#" class="btn btn-outline-warning">Cart</a>
            <a href="#" class="btn btn-outline-secondary">Checkout</a>
        </div>

            @else 
                <p>Cart is Empty...</p>

            @endif
         

        </div>
    </div>