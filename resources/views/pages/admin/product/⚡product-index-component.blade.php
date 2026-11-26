<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Product;
use Livewire\WithPagination;


new 
#[Layout('layouts::admin')] 
#[Title('Products')]
class extends Component
{

     use WithPagination;

    
    public function render()
    {

        $products = Product::query()->with('category')->orderBy('id', 'desc')->paginate();
        return $this->view(compact('products'));
    }


    
};
?>

<div class="row">
    <div class="col-12 mb-4 position-relative">

         <div class="update-loading" wire:loading wire:target.except="deleteProduct">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
          </div>


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.products.create')}}" class="btn btn-primary" wire:navigate>Add Product</a>
            </div>
            <div class="card-body">
                 <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:10%">Id</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($products as $product)
                           <tr wire:key="{{$product->id}}">
                            <td>{{$product->id}}</td>
                            <td><img src="{{asset($product->getImage())}}" alt="Image" height="50"></td>
                            <td>{{$product->title}}</td>
                            <td>{{$product->category->title}}</td>
                            <td>
                                <a href="{{route('product', $product->slug)}}" class="btn btn-info btn-circle" target="_blank">
                                        <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{route('admin.products.edit', $product->id)}}" class="btn btn-warning btn-circle" wire:navigate>
                                        <i class="fa-solid fa-pen"></i>
                                </a>
                                <button class="btn btn-danger btn-circle" wire:loading.attr="disabled" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                                wire:confirm="Are you sure ?" wire:click="deleteProduct({{$product->id}})">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>


                           </tr>
                           @endforeach
                        </tbody>
                    </table>
                   
                  </div>
                   {{$products->links()}}
            </div>
        </div>

    </div>
</div>

