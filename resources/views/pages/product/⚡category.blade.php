<?php

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use App\Helpers\Traits\CartTrait;

new class extends Component
{
    

    use WithPagination, CartTrait;

    public string $slug = '';
    #[Url]
    public string $sortBy = 'default';

    public int $limitBy = 3;

    #[Url]
    public array $filter_select = [];


    public array $sortList = [
        'default' => ['title' => 'Default', 'order_field' => 'id', 'order_direction' => 'desc'],
        'name-asc' => ['title' => 'Name (a-z)', 'order_field' => 'title', 'order_direction' => 'asc'],
        'name-desc' => ['title' => 'Name (z-a)', 'order_field' => 'title', 'order_direction' => 'desc'],
        'price-asc' => ['title' => 'Price (low > high)', 'order_field' => 'price', 'order_direction' => 'asc'],
        'price-desc' => ['title' => 'Price (high > low)', 'order_field' => 'price', 'order_direction' => 'desc'],
    ];

    public array $limitList = [3,6,9,12];



   

    public function mount($slug)
    {
        
        $this->slug = $slug;
    }

    public function updated($property)
    {
        if($property == 'filter_select'){
            $this->resetPage();
        }
    }

    public function changeSort()
    {

        $this->sortBy = isset($this->sortList[$this->sortBy]) ? $this->sortBy : 'default';
       
    }

    public function changeLimit()
    {
        $this->limitBy = in_array($this->limitBy, $this->limitList) ? $this->limitBy : $this->limitList[0];
        $this->resetPage();
    }

    public function render()
    {


        $category = Category::query()->where('slug', '=', $this->slug)->firstOrFail();

    

        $ids = App\Helpers\Category\Category::getIds($category->id) . $category->id;
        $breadcrumbs = App\Helpers\Category\Category::getBreadcrumbs($category->id);

        $category_filters = DB::table('category_filters')
        ->select('category_filters.filter_group_id', 'filter_groups.title', 'filters.title as filter_title', 'filters.id as filter_id' )
        ->join('filter_groups', 'category_filters.filter_group_id', '=', 'filter_groups.id')
        ->join('filters', 'filters.filter_group_id', '=', 'filter_groups.id')
        ->whereIn('category_filters.category_id', explode(',', $ids))
        ->get();    
        
        // dump($category_filters);
        $filter_groups = [];
        foreach ($category_filters as $filter) {
             $filter_groups[$filter->filter_group_id][] = $filter;
        }

         

      if($this->filter_select){

        $cnt_filter_groups = DB::table('filters')
        ->select(DB::raw('count(distinct filter_group_id) as cnt'))
        ->whereIn('id', $this->filter_select)
        ->value('cnt');  

        } else{
            $cnt_filter_groups = 1;
        }
        // dump($cnt_filter_groups);
        
        $products = Product::query()
        ->whereIn('category_id', explode(',', $ids))
        ->when($this->filter_select, function(Builder $query) use($cnt_filter_groups){

            $query->leftJoin(DB::raw("filter_products FORCE INDEX FOR JOIN(filter_id)"),
            "filter_products.product_id", "=", "products.id")
            ->whereIn("filter_products.filter_id", $this->filter_select)
            ->groupBy('id')
            ->havingRaw("count(distinct filter_products.filter_group_id) >= $cnt_filter_groups");

        })
        ->orderBy($this->sortList[$this->sortBy]['order_field'], $this->sortList[$this->sortBy]['order_direction'])
        ->paginate($this->limitBy);


        return $this->view([
            'category' => $category,
            'products' => $products,
            'breadcrumbs' => $breadcrumbs,
            'filter_groups' => $filter_groups
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

                        @foreach($breadcrumbs as $breadcrumb_slug => $breadcrumbs_title)
                        @if($loop->last)
                         <li><span>{{$breadcrumbs_title }}</span></li>
                        @else
                        
                          <li><a href="{{route('category',$breadcrumb_slug )}}">{{$breadcrumbs_title}}</a></li>
                        @endif
  
                        @endforeach
                        
                   
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
            <div class="col-lg-3 col-md-4">
                <div class="sidebar">

                    <button class="btn btn-warning w-100 text-start collapse-filters-btn mb-3" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false"
                            aria-controls="collapseExample">
                        <i class="fa-solid fa-filter"></i> Filters
                    </button>

                    <div class="collapse collapse-filters" id="collapseFilters">
                        @foreach($filter_groups as $key => $filter_group)
                        <div class="filter-block" wire:key="{{$key}}">
                            <h5 class="section-title"><span>Filter by {{ $filter_group[0]->title}}</span></h5>

                            @foreach($filter_group as $filter)
                                <div class="form-check d-flex justify-content-between" wire:key="{{$filter->filter_id}}">
                                    <div>
                                        <input class="form-check-input"
                                        wire:model.live="filter_select"
                                         type="checkbox" 
                                         value="{{$filter->filter_id}}"
                                          id="filter-{{$filter->filter_id}}">
                                        <label class="form-check-label" for="filter-{{$filter->filter_id}}">
                                            {{$filter->filter_title}}
                                        </label>
                                    </div>
                                </div>
                             @endforeach
                             
                        </div>
                        @endforeach
                    </div>


                </div>
            </div>

            <div class="col-lg-9 col-md-8">
                <div class="row mb-3">
                    <div class="col-12">
                        <h1 class="section-title h3"><span>{{ $category->title }}</span></h1>
                    </div>
                </div>

                @if(count($products))
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Sort By:</span>
                                <select class="form-select" aria-label="Sort by:" wire:change="changeSort"
                                 wire:model="sortBy" 
                                >
                                @foreach($sortList as $key => $value)
                                      <option value="{{$key}}" @if($key == $sortBy) 
                                       selected  @endif
                                       wire:key="{{$key}}">{{$value['title']}}</option>
                                @endforeach

                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Show:</span>
                                <select class="form-select" aria-label="Show:"
                                wire:model="limitBy" wire:change="changeLimit">

                                @foreach($limitList as $key => $value)
                                    <option value="{{$value}}" @if($value == $limitBy)selected @endif
                                     wire:key="{{$key}}">{{$value}}</option>
                                @endforeach

                                    
                               
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($products as $product)
                        
                            <div class="col-lg-4 col-sm-6 mb-3" wire:key="{{ $product->id }}">
                                @include('incs.product-card')
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">

                                {{ $products->links(data: ['scrollTo' => '#breadcrumbs']) }}
                                {{-- <ul class="pagination">
                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active" aria-current="page"><a class="page-link"
                                                                                        href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul> --}}
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

     {{-- ->when($this->filter_select, function(Builder $query) use($cnt_filter_groups){
            
            $query->leftJoin(DB::raw("filter_products FORCE INDEX FOR JOIN (PRIMARY)"),
            'filter_products.product_id', '=', 'products.id')
          ->whereIn('filter_products.filter_id', $this->filter_select)
          ->groupBy('id')
          ->havingRaw("count(distinct filter_products.filter_group_id ) >= $cnt_filter_groups");

            
        }) --}}