<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Helpers\Category\Category;



new 
#[Layout('layouts::admin')] 
#[Title('Categories')]
class extends Component
{

 
    
};
?>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="#" class="btn btn-primary">Add Category</a>
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
                            {!! Category::getMenu('incs.menu-table-tpl') !!}
                        </tbody>
                    </table>
                  </div>
            </div>
        </div>




      
    </div>
</div>