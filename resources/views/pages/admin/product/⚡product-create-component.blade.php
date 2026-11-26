<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;




new 
#[Layout('layouts::admin')] 
#[Title('Create Product')]

class extends Component
{

     use WithFileUploads;
    
    public string $title = '';
    public $category_id;
    public string $excerpt;
    public string $content = '';
    public array $selectedFilters = [];

     #[Validate('image|max:1024')]
    public $image;
     #[Validate('image|max:1024')]
    public $gallery;
    public int $price = 0;
    public int $old_price = 0;
    public bool $is_hit = false;
    public bool $is_new = false;

    #[Computed]
    public function filters()
    {
        $filter_groups = [];

        if($this->category_id){
            $ids = App\Helpers\Category\Category::getIds($this->category_id) . $this->category_id;
            $category_filters = DB::table('category_filters')
            ->select('category_filters.filter_group_id', 'filter_groups.title', 'filters.title as filter_title', 'filters.id as filter_id' )
            ->join('filter_groups', 'category_filters.filter_group_id', '=', 'filter_groups.id')
            ->join('filters', 'filters.filter_group_id', '=', 'filter_groups.id')
            ->whereIn('category_filters.category_id', explode(',', $ids))
            ->get();
           
            foreach ($category_filters as $filter) {
                $filter_groups[$filter->filter_group_id][] = $filter;
            }

           
           
        }

// echo '<pre>';
// print_r($filter_groups);
// echo '</pre>';
        return $filter_groups;

    }



};
?>


<div class="row">
    <div class="col-12 mb-4 position-relative">
        
          <div class="update-loading" wire:loading wire:target="save">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
          </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{route('admin.products.index')}}" class="btn btn-info  rounded">Products List</a>
            </div>
            <div class="card-body">
                    <form wire:submit="save">

                        <div class="mb-3">
                            <label for="title" class="form-label required">Product title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                             id="title" placeholder="Product title"
                                    wire:model="title">
                            @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label required">Category</label>

                            <select wire:model.live="category_id" id="category_id"
                                class="custom-select @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                    {!!  App\Helpers\Category\Category::getMenu('incs.menu-select-tpl') !!}
                            </select>


                            @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                         <div class="row">
                                @foreach($this->filters as $key => $filter_group)
                                <div class="col-lg-3 col-md-6" wire:key="{{$key}}">
                                    <div class="card">
                                        <div class="card-header py-3">
                                            <h6 class="text-primary font-weight-bold m-0">{{$filter_group[0]->title}}</h6>
                                        </div>
                                         <div class="card-body">
                                            @foreach($filter_group as $filter)
                                                <div wire:key="{{$filter->filter_id}}">
                                                    <input type="checkbox" wire:model="selectedFilters" 
                                                     value="{{$filter->filter_id}}" id="filters-{{$filter->filter_id}}">
                                                    <label class="form-check-label" for="filters-{{$filter->filter_id}}">
                                                    {{$filter->filter_title}}
                                                    </label>
                                                </div>
                                            @endforeach
                                         </div>
                                    </div>
                                </div>
                                @endforeach
                               
                            </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Product excerpt</label>
                            <input type="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
                             id="excerpt" wire:model="excerpt">
                            @error('excerpt')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="product_content" class="form-label required">Product content</label>
                             <textarea class="form-control @error('content') is-invalid @enderror"
                               id="product_content" wire:model="content">
                             </textarea>
                         
                            @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label required">Price</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                             id="price"  wire:model="price">
                            @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="old_price" class="form-label required">Old price</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                             id="old_price" wire:model="old_price">
                            @error('old_price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                           
                            <input type="checkbox" class="form-check-input @error('is_hit') is-invalid @enderror"
                             id="is_hit" wire:model="is_hit">
                            <label for="is_hit" class="form-check-label">Is_hit</label>
                            @error('is_hit')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            
                            <input type="checkbox" class="form-check-input @error('is_new') is-invalid @enderror"
                             id="is_new" wire:model="is_new">
                             <label for="is_new" class="form-check-label">Is_new</label>
                            @error('is_new')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
 
                        <div class="mb-3">
                            <button type="submit" class="btn btn-warning" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })">
                                Save
                                    <div class="spinner-grow spinner-grow-sm" role="status" 
                                    wire:loading wire:target="save">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                
                            </button>
                        </div>

                    </form>
                  
            </div>
        </div>
    </div>

</div>
