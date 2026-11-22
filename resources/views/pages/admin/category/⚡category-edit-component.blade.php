<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Category;

new 
#[Layout('layouts::admin')] 
#[Title('Edit Category')]
class extends Component
{
    public Category $category;
    public string $title;
    public $parent_id = 0;
    public $id;
   

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->title = $category->title;
        $this->parent_id = $category->parent_id;
        $this->id = $category->id;
    }

    public function save()
    {

        $validated = $this->validate([
            'title' => 'required|max:255',
            'parent_id' => 'required|integer'
        ]);


        $this->category->update($validated);
        cache()->forget('categories_data');
        session()->flash('success', 'Category updated successfully!');
        $this->redirectRoute('admin.categories.index', navigate:true);
       

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
                            <label for="title" class="form-label required">{{ $title}}</label>
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
                            <button type="submit" class="btn btn-warning">
                                Update
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
