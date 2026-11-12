<?php

use Livewire\Component;
use App\Models\Product;


new class extends Component
{

    public string $term = '';

    public function search()
    {

        if($this->term){
           $this->redirectRoute('search', ['query' => $this->term], navigate:true);
        }
      
    }



    public function render()
    {
        // dump($this->term);
     

      $search_results = [];

      if(mb_strlen($this->term, 'UTF-8') > 1){

        $search_results = Product::query()->whereLike('title', '%' . $this->term . '%')
        ->limit(10)
        ->get();
      
      }

       return $this->view([
            'search_results' => $search_results
        ]);

    }

}
?>

 <div class="col-sm-6 mt-2 mt-md-0">

    <div class="search-form">
        <form wire:submit="search">
                <div class="input-group">
                    <input type="text" class="form-control"  placeholder="Searching..."
                            aria-label="Searching..." aria-describedby="button-search"
                             wire:model.live.debounce.500ms="term">
                    <button class="btn btn-outline-warning @if(!$term) disabled @endif "
                     type="submit" id="button-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

              
                @if($term)
                <span class="search-clear" x-on:click="$wire.term='';$wire.$refresh()">
                    <i class="fa-solid fa-xmark" style="cursor: pointer"></i>
                </span>
                @endif
        </form>

        @if(count($search_results))
        <ul class="search-results">
            @foreach($search_results as $productItem)
            <li><a href="{{route('product', $productItem->slug)}}"  wire:navigate>
                <span>{{$productItem->title}}</span><span>${{$productItem->price}}</span>
                </a>
            </li>
            @endforeach
            
        </ul>

        @endif
    </div>
  
</div>