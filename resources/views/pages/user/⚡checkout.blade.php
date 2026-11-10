<?php

use Livewire\Component;
use App\Helpers\Cart\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderClient;
use App\Mail\OrderManager;





new class extends Component
{
    public string $name;
    public string $email;
    public string $note;

    public function mount()
    {
        $this->name = auth()->user()->name ?? '';
        $this->email = auth()->user()->email ?? '';
    }

    public function saveOrder()
    {
        $validated = $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'note' => 'string|nullable'
        ]);

        $validated = array_merge([
            'user_id' => auth()->id(),
            'total' => Cart::getTotalProducts()
        ], $validated);
 
        //  dump($validated);

        try{
            DB::beginTransaction();
            $order = Order::query()->create($validated);

            $order_products = [];
            $cart = Cart::getCart();
           //dump($cart);

            foreach ($cart as $key => $value) {
                $order_products[] = [
                    'product_id' => $key,
                    'title' => $value['title'],
                    'price' => $value['price'],
                    'quantity' => $value['quantity'],
                    'slug' => $value['slug'],
                    'image' => $value['image'],
                ];
            }

            $order->orderProducts()->createMany($order_products);
            Mail::to($validated['email'])->send(new OrderClient($order->id, 
            Cart::getTotalProducts(), $order_products, $validated['note'] ??'') );


            Mail::to('eshop@maneger.loc')->send(new OrderManager($order->id,  Cart::getTotalProducts()));

            Cart::clearCart();
            $this->dispatch('cart-updated');

            DB::commit();
            $this->js("toastr.success('Success ordering!')");

        } catch(\Exception $e){

            DB::rollback();
            Log::error($e->getMessage());
            $this->js("toastr.error('Error ordering!')");

        }
    }
};
?>

<div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="breadcrumbs">
                    <ul>
                        <li><a wire:navigate href="{{ route('home') }}">Home</a></li>
                        <li><span>Checkout</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">

        @if($cart = \App\Helpers\Cart\Cart::getCart())

                <div class="row">

                    <div class="col-lg-8 mb-3">
                        <div class="Checkout p-3 h-100 bg-white">

                            <h1 class="section-title h5"><span>Checkout</span></h1>

                            <form wire:submit="saveOrder">

                                @guest

                                    <div class="mb-3">
                                        <label for="name" class="form-label required">Name</label>
                                        <input type="text" id="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="John Doe" aria-label="Name" wire:model="name">
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label required">Email</label>
                                        <input type="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="johndoe@mail.com" aria-label="Name" wire:model="email">
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                @endguest

                                <div class="mb-3">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" id="note"
                                              rows="5"
                                              placeholder="Note to order..." wire:model="note"></textarea>
                                    @error('note')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-warning" wire:navigate>
                                        Checkout
                                        <div wire:loading wire:target="saveOrder">
                                            <div class="spinner-grow spinner-grow-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">

                        <div class="cart-summary p-3 sidebar">
                            <h5 class="section-title"><span>Cart Summary</span></h5>

                            <ul class="list-unstyled">
                                @foreach($cart as $k => $item)

                                    <li wire:key="{{ $k }}" class="d-flex justify-content-between my-3">
                                        <span class="fw-medium">{{ $item['title'] }}</span>
                                        <span>{{ $item['quantity'] }} x {{ $item['price'] }}</span>
                                    </li>

                                @endforeach
                            </ul>

                            <div class="d-flex justify-content-between pt-3 border-top">
                                <h3>Total</h3>
                                <h3>${{ \App\Helpers\Cart\Cart::getTotalProducts() }}</h3>
                            </div>

                        </div>

                    </div>

                </div>
        @else
            <p>Cart is empty...</p>
        @endif
    </div>

</div>
