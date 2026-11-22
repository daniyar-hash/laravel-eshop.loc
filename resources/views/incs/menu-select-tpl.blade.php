<option value="{{$id}}"  wire:key="{{$id}}" 

{!! $val['parent_id']==0 ? 'class="font-weight-bold"': '' !!}
@if(isset($this->id) && $val['id'] == $this->id) disabled style="color:#ccc;" @endif
>

{!! $tab . $val['title']   !!}

</option>

@if(isset($val['children']))
{!! \App\Helpers\Category\Category::getHtml($val['children'], "&nbsp;$tab - ") !!}
@endif





