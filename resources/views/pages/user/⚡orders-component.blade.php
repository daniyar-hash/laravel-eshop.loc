<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;



new class extends Component
{


use WithPagination;

   public function render()
   {

    $orders = Order::query()->where('user_id', '=', auth()->id())
    ->orderBy('created_at', 'desc')
    ->paginate();
        // dump($orders);
    return $this->view([
        'orders' => $orders
    ]);
   }



};
?>

<div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="breadcrumbs">
                    <ul>
                        <li><a wire:navigate href="{{route('home')}}"">Home</a></li>
                        <li><a wire:navigate href="{{route('account')}}"">Account</a></li>
                        <li><span>Orders</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="container position-relative">

            <div class="update-products" wire:loading>
                <div class="spinner-grow fg-accent " role="status">
                    <span class="visually-hidden">Loading…</span>
                </div>
             </div>

        <div class="row">
             <div class="col-lg-4 mb-3">
                <div class="cart-summary p-3 sidebar">
                    <h5 class="section-title"><span>Links</span></h5>
                    @include('incs.account-links')
                </div>
            </div>
            <div class="col-lg-8 mb-3">
               <div class="cart-content p-3 h-100 bg-white">
                    <h5 class="section-title"><span>Orders</span></h5>
                    @if($orders->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <td>#</td>
                                                        <td>Total</td>
                                                        <td>Status</td>
                                                        <td>Created at</td>
                                                        <td>Updated at</td>
                                                        <td style="text-align: center"><i class="fa-solid fa-eye"></i></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($orders as $order)
                                                        <tr wire:key={{$order->id}}>
                                                            <td>{{$order->id}}</td>
                                                            <td>${{$order->total}}</td>
                                                            <td>{{$order->status ? 'completed':'new'}}</td>
                                                            <td>{{$order->created_at}}</td>
                                                            <td>{{$order->updated_at}}</td>
                                                            <td style="text-align: center">
                                                                <a href="{{route('order-show', $order->id)}}"
                                                                    class="btn btn-warning" wire:navigate>
                                                                    <i class="fa-solid fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                        </table>
                                        {{$orders->links()}}

                    </div>
               
                    @else Orders is empty...
                    @endif
               
                           
               </div>
            </div>

          

        </div>
    </div>

</div>