 <div class="product-card" >
        <div class="product-card-offer">
            @if($product->is_hit)
            <div class="offer-hit">Hit</div>
            @endif
            @if($product->is_new)

            <div class="offer-new">New</div>
            @endif
        
        </div>
        <div class="product-thumb">
            <a href="#"><img src="{{ $product->image}}" alt=""></a>
        </div>
        <div class="product-details">
            <h4>
                <a href="#">{{ $product->title}}</a>
            </h4>
            <p class="product-excerpt">{{$product->excerpt}}</p>
            <div class="product-bottom-details d-flex justify-content-between">
                <div class="product-price">
                    @if($product->old_price)
                    <small>{{$product->old_price}}</small>
                    @endif
                    {{$product->price}}
                </div>
                <div class="product-links">
                    
                    <button wire:click="addCart({{$product->id}})" 
                        class="btn btn-outline-secondary add-to-cart"
                        wire:loading.attr="disabled">
                        
                        <div wire:loading.remove wire:target="addCart({{$product->id}})">
                            <i class="fas fa-shopping-cart"></i>
                        </div>

                        <div wire:loading wire:target="addCart({{$product->id}})"> 
                            <div class="spinner-grow spinner-grow-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        
                    
                    
                    </button>
                </div>
            </div>
        </div>
 </div>