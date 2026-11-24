<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Product;




new 
#[Layout('layouts::admin')] 
#[Title('Categories')]
class extends Component
{

    public function deleteCategory(Category $category)
    {


        $categories_cnt = Category::query()
        ->where('parent_id', '=', $category->id)->count();
        if($categories_cnt){
            $this->js("toastr.error('Error! This Category has child')");
            return;
        }

        $products_cnt = Product::query()
        ->where('category_id', '=', $category->id)->count();
        if($products_cnt){
            $this->js("toastr.error('Error! This Category has products')");
            return;
        }

        $category->delete();
        cache()->forget('categories_data');
        session()->flash('success', 'Category removed');
        $this->redirectRoute('admin.categories.index', navigate:true);

        
    }


};
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.categories.create')}}" class="btn btn-primary" wire:navigate>Add Category</a>
            </div>
            <div class="card-body">
                 <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Category</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            {!! App\Helpers\Category\Category::getMenu('incs.menu-table-tpl') !!}
                        </tbody>
                    </table>
                  </div>
            </div>
        </div>




      
    </div>
</div>