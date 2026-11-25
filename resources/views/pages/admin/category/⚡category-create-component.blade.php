<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\FilterGroup;
use Illuminate\Support\Facades\Log;

new 
#[Layout('layouts::admin')] 
#[Title('Create Category')]
class extends Component
{

    public string $title;
    public $parent_id = 0;
    public array $selectedFilterGroup = [];

    public function save()
    {

        $validated = $this->validate([
            'title' => 'required|max:255',
            'parent_id' => 'required|integer',
            'selectedFilterGroup.*' => 'numeric',
            // 'selectedFilterGroup' => 'required|array|min:1',
            // 'selectedFilterGroup.*' => 'required|integer|exists:filter_groups,id',
        ]);

        try {

        DB::beginTransaction();

        $category = Category::query()->create($validated);
       
        if(!empty($validated['selectedFilterGroup'])){
             $data = [];
            foreach ($validated['selectedFilterGroup'] as $filter_group_id) {
                   $data[] = [
                    'category_id' => $category->id,
                    'filter_group_id' => $filter_group_id
                       ];
            }

            DB::table('category_filters')->insert($data);
         
        }
    
        DB::commit();
        cache()->forget('categories_data');
        session()->flash('success', 'Category created successfully!');
        $this->redirectRoute('admin.categories.index', navigate:true);
       
            //code...
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->js("toastr.error('Error! Category is not saving')");
        }
       

    }

    public function render()
    {
        $filter_groups = FilterGroup::all();
        // dump($filter_groups);
        return $this->view(compact('filter_groups'));
    }
    
};
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{route('admin.categories.index')}}" class="btn btn-info  rounded">Categories List</a>
            </div>
            <div class="card-body">
                    <form wire:submit="save">

                        <div class="mb-3">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Category title"
                                    wire:model="title">
                            @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label required">Categories List</label>

                            <select wire:model="parent_id" id="parent_id"
                                class="custom-select @error('parent_id') is-invalid @enderror">
                                <option value="0" wire:key='0'>Root Category</option>
                                    {!!  App\Helpers\Category\Category::getMenu('incs.menu-select-tpl') !!}
                            </select>
                            @error('parent_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="card">
                                <div class="card-header">Filters</div>
                                <div class="card-body">
                                    {{-- @error('selectedFilterGroup')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror --}}
                                    @foreach ($filter_groups as $filter_group)
                                        <div class="form-check" wire:key="{{$filter_group->id}}">
                                              <input class="form-check-input" type="checkbox"  wire:model="selectedFilterGroup"
                                              value="{{$filter_group->id}}" id="filter_group-{{$filter_group->id}}">
                                            <label class="form-check-label" for="filter_group-{{$filter_group->id}}">
                                                {{$filter_group->title}}
                                            </label>
                                        </div>

                                    @endforeach
                                    @error('selectedFilterGroup.*')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                     @enderror
                                   
                                </div>
                            </div>
                           
                            
                        </div>
                             
                        <div class="mb-3">
                            <button type="submit" class="btn btn-warning">
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
