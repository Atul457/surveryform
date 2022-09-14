{{-- For submenu --}}
<ul class="menu-content">
  @if(isset($menu))
  @foreach($menu as $submenu)
  @if (($submenu->role === "admin") && (Session::get("is_admin") != "1"))
    @continue
  @endif
    
  @if (($submenu->role === "user") && (Session::get("is_admin") == "1"))
    @continue
  @endif
  <li 
    @if($submenu->slug === Route::currentRouteName()) class="active" @endif
    @if(isset($submenu->id) && Route::currentRouteName() === "editcompany") 
    id="{{$submenu->id}}" 
    class="active" @endif>
    <a href="{{isset($submenu->url) ? url($submenu->url):'javascript:void(0)'}}" class="d-flex align-items-center" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
      @if(isset($submenu->icon))
      <i data-feather="{{$submenu->icon}}"></i>
      @endif
      <span 
        class="menu-item text-truncate">
        {{ __(''.$submenu->name) }}
      </span>
    </a>
    @if (isset($submenu->submenu))
    @include('panels/submenu', ['menu' => $submenu->submenu])
    @endif
  </li>
  @endforeach
  @endif
</ul>
