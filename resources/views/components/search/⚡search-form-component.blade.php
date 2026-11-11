<?php

use Livewire\Component;

new class extends Component
{

    public string $term = '';


    //
}
?>

 <div class="col-sm-6 mt-2 mt-md-0">

    <div class="search-form">
        <form>
                <div class="input-group">
                    <input type="text" class="form-control" name="s" placeholder="Searching..."
                            aria-label="Searching..." aria-describedby="button-search">
                    <button class="btn btn-outline-warning" type="submit" id="button-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <span class="search-clear">
                    <i class="fa-solid fa-xmark"></i>
                </span>
        </form>
        <ul class="search-results">
            <li><a href="#"><span>Product 1</span><span>100$</span></a></li>
            <li><a href="#"><span>Product 2</span><span>200$</span></a></li>
            <li><a href="#"><span>Product 3</span><span>400$</span></a></li>
        </ul>
    </div>
  
</div>