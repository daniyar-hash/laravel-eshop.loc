<?php

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;
use App\Helpers\Traits\CartTrait;


new class extends Component
{

    use WithPagination, CartTrait;

    public string $query;

    public function mount()
    {
        $this->query = request()->query('query') ?? '';
    }

    public function render()
    {

        $products = [];
        if($this->query){

                $products = Product::query()->whereLike('title', '%' . $this->query . '%')
                ->paginate(6);
            
            }

        return $this->view([
                'products' => $products
            ]);


    }


  

};
?>

<div>
    <div class="container">
        
        <div class="row">
            <div class="col-12">
                <nav class="breadcrumbs" id="breadcrumbs">
                    <ul>
                        <li><a href="{{route('home')}}" wire:navigate>Home</a></li>
                        <li>Search Page</li>
                                        
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="container position-relative">
        
           <div class="update-products" wire:loading wire:target.except="addCart">
                <div class="spinner-grow fg-accent " role="status">
                    <span class="visually-hidden">Loading…</span>
                </div>
          </div>

        <div class="row">
            

            <div class="col-lg-12">
                

                @if(count($products))
                  
                <h1 class="h3"><span>Search by: <em>{{ $query }}</em></span></h1>

                    <div class="row">
                        @foreach($products as $product)
                        
                            <div class="col-lg-3 col-sm-6 mb-3" wire:key="{{ $product->id }}">
                                @include('incs.product-card')
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">

                                {{ $products->links(data: ['scrollTo' => '#breadcrumbs']) }}
                               
                            </nav>
                        </div>
                    </div>
                @else
                    <p>No products found...</p>
                @endif

            </div>
        </div>
    </div>
</div>