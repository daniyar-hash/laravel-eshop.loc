<?php

use Livewire\Component;
use App\Models\User;


new class extends Component
{
    
    public string $name;
    public string $email;
    public string $password;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }


    public function save()
    {

        $user = User::query()->findOrFail(auth()->id());
          $validated = $this->validate([
            'name' => 'required|max:255',
            'email' =>'required|email|max:255|unique:users,email,'. auth()->id(),
            'password' => 'nullable|min:6'
        ]);

      

        if(!$validated['password']){
            unset($validated['password']);
        }

  

        $user->update($validated);

        $this->js("toastr.success('Account details changed successfully!')");



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
                        <li><span>Change Account</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">


        <div class="row">
             <div class="col-lg-4 mb-3">
                <div class="cart-summary p-3 sidebar">
                    @include('incs.account-links')
                </div>
            </div>
            <div class="col-lg-8 mb-3">
               <div class="cart-content p-3 h-100 bg-white">
                    <p>Welcome, {{ auth()->user()->name}} !</p>
                    <form wire:submit="save">

                                <div class="mb-3">
                                    <label for="name" class="form-label required">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" placeholder="Name" wire:model="name">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label required">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" placeholder="Email" wire:model="email">
                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label required">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                           placeholder="Password" wire:model="password">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-warning">
                                        Save
                                        <div wire:loading wire:target="save">
                                            <div class="spinner-grow spinner-grow-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>

                            </form>
               </div>
            </div>

          

        </div>
    </div>

</div>