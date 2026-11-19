<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Number;
use Livewire\Attributes\Title;



new 
#[Layout('layouts::admin')] 
#[Title('Dashboard')] 
class extends Component
{
    
    public function render()
    {

        $products_cnt = Product::query()->count();
        $users_cnt = User::query()->count();
        $orders_cnt = Order::query()->count();
        $orders_total = Order::query()->sum('total');

        return $this->view(compact(
            'products_cnt',
            'users_cnt',
            'orders_cnt',
            'orders_total'        
            
            
            ));
    }

}
?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{$title ?? 'Dashboard'}}</h1>
     
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Products Count</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$products_cnt}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-rectangle-list fa-2x text-gray-300 "></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$users_cnt}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders_cnt}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-cart-arrow-down  fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Orders Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{Number::currency($orders_total, in: 'USD')}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-sack-dollar fa-2x text-gray-300 "></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      
    </div>


</div>