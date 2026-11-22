<tr wire:key="{{$id}}">
   <td style="width: 10%">{{$id}}</td>
   <td style="width: 70%"><span style="padding-left:
   {{3 * strlen($tab)}}px">{{$tab . $val['title']}}</span></td>
   <td>
      <a href="{{route('category', $val['slug'])}}" class="btn btn-info btn-circle" target="_blank">
            <i class="fa-regular fa-eye"></i>
      </a>
       <a href="{{route('admin.categories.edit', $val['id'])}}" class="btn btn-warning btn-circle" wire:navigate>
            <i class="fa-solid fa-pen"></i>
      </a>
       <button class="btn btn-danger btn-circle" 
       wire:confirm="Are you sure ?" wire:click="deleteCategory({{$val['id']}})">
          <i class="fa-solid fa-trash-can"></i>
      </button>


   </td>
</tr>
@if(isset($val['children']))
{!! \App\Helpers\Category\Category::getHtml($val['children'], "$tab - ") !!}
@endif




