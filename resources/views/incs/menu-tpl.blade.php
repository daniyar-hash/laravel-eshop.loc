<li @if(isset($val['children'])) class="nav-item dropend" @endif>
   @if(isset($val['children']))
        <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown"
           data-bs-auto-close="outside">{{$val['title'] }}</a>
        <ul class="dropdown-menu dropdown-menu-end">
           {!! \App\Helpers\Category\Category::getHtml($val['children']) !!}
        </ul>
  @else
        <a class="dropdown-item" wire:current="active" wire:navigate
           href="{{ route('category', $val['slug']) }}">{{$val['title'] }}</a>
   @endif
</li>

