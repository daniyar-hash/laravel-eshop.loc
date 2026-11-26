<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;





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

        DB::table('category_filters')->where('category_id', '=', $category->id)
        ->delete();

        $category->delete();
        cache()->forget('categories_data');
        session()->flash('success', 'Category removed');
        $this->redirectRoute('admin.categories.index', navigate:true);

        
    }


};
?>

<div class="row">
    <div class="col-12 mb-4 position-relative">

          <div class="update-loading" wire:loading wire:target="deleteCategory">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
          </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ route('admin.categories.create')}}" class="btn btn-primary" wire:navigate>Add Category</a>
            </div>
            <div class="card-body">
                 <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category</th>
                                <th>Actions</th>
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