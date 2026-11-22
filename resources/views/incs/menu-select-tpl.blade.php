<option value="{{$id}}"  wire:key="{{$id}}" 

{!! $val['parent_id']==0 ? 'class="fw-bold"': '' !!}>
{!! $tab . $val['title'] !!}

</option>

@if(isset($val['children']))
{!! \App\Helpers\Category\Category::getHtml($val['children'], "&nbsp;$tab - ") !!}
@endif





