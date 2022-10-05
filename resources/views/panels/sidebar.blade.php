@php
$configData = Helper::applClasses();
@endphp
<div
  class="main-menu menu-fixed {{ $configData['theme'] === 'dark' || $configData['theme'] === 'semi-dark' ? 'menu-dark' : 'menu-light' }} menu-accordion menu-shadow"
  data-scroll-to-active="true">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">
      <li class="nav-item me-auto">
        <a class="navbar-brand" href="{{ url('/') }}">
          <span class="brand-logo">
          </span>
          <h2 class="brand-text">Survey Form</h2>
        </a>
      </li>
    </ul>
  </div>
  <div class="shadow-bottom"></div>
  <div class="main-menu-content">
  <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    @allnotaccessible("mycompanies", "create_comp_view")
    <li class="nav-item has-sub" style="">
        <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="menu-title text-truncate">My companies</span>
        </a>
        <ul class="menu-content">
          @accessible("mycompanies")
            <li class="{{request()->is('mycompanies') ? 'active' : ''}}">
                <a href="{{route('mycompanies')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      View
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("create_comp_view")
            <li class="{{request()->is('create_comp_view') ? 'active' : ''}}">
                <a href="{{route('create_comp_view')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                    Create
                  </span>
                </a>
            </li>
            @endaccessible
        </ul>
    </li>
    @endallnotaccessible

    @allnotaccessible("myproducts", "createprodview")
    <li class="nav-item has-sub">
        <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="menu-title text-truncate">Products</span>
        </a>
        <ul class="menu-content">
            @accessible("myproducts")
            <li class="{{request()->is('myproducts') ? 'active' : ''}}">
                 <a href="{{route('myproducts')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      View
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("createprodview")
            <li class="{{request()->is('createprodview') ? 'active' : ''}}">
                <a href="{{route('createprodview')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Create
                    </span>
                </a>
            </li>
            @endaccessible
        </ul>
    </li>
    @endallnotaccessible

    @allnotaccessible("users", "createuserview")
    <li class="nav-item has-sub">
        <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span class="menu-title text-truncate">Employees</span>
        </a>
        <ul class="menu-content">
        @accessible("users")
            <li class="{{request()->is('users') ? 'active' : ''}}">
                <a href="{{route('users')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      View
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("createuserview")
            <li class="{{request()->is('createuserview') ? 'active' : ''}}">
                <a href="{{route('createuserview')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Create
                    </span>
                </a>
            </li>
            @endaccessible
        </ul>
    </li>
    @endallnotaccessible

    @allnotaccessible("myforms", "create_form_view", "allocateformview")
    <li class="nav-item has-sub sidebar-group-active" style="">
        <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
            </svg>
            <span class="menu-title text-truncate">Forms</span>
        </a>
        <ul class="menu-content">
            @accessible("myforms")
            <li  class="{{request()->is('myforms') ? 'active' : ''}}">
                <a href="{{route('myforms')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      All forms
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("create_form_view")
            <li  class="{{request()->is('create_form_view') ? 'active' : ''}}">
                <a href="{{route('create_form_view')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Create form
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("allocateformview")
            <li  class="{{request()->is('allocateformview') ? 'active' : ''}}">
                <a href="{{route('allocateformview')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Allocate form
                    </span>
                </a>
            </li>
            @endaccessible
        </ul>
    </li>
    @endallnotaccessible

    @allnotaccessible("cities", "addcityview", "addareaview")
    <li class="nav-item has-sub">
        <a href="javascript:void(0)" class="d-flex align-items-center" target="_self">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
            <span class="menu-title text-truncate">Cities and Areas</span>
        </a>
        <ul class="menu-content">
            @accessible("cities")
            <li  class="{{request()->is('cities') ? 'active' : ''}}">
                <a href="{{route('cities')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                    View
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("addcityview")
            <li  class="{{request()->is('addcityview') ? 'active' : ''}}">
                <a href="{{route('addcityview')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Add city
                    </span>
                </a>
            </li>
            @endaccessible
            @accessible("addareaview")
            <li  class="{{request()->is('addareaview') ? 'active' : ''}}">
                <a href="{{route('addareaview')}}" class="d-flex align-items-center" target="_self">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    <span class="menu-item text-truncate">
                      Add area
                    </span>
                </a>
            </li>
            @endaccessible
        </ul>
      </li>
      @endallnotaccessible

      @if(!Auth::user()->is_admin)
        <li  class="{{request()->is('userforms') ? 'active' : ''}}">
            <a href="{{route('userforms')}}" class="d-flex align-items-center" target="_self">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                </svg>
                <span class="menu-item text-truncate">
                    My Forms
                </span>
            </a>
        </li>
    @endif

    </ul>
  </div>
</div>
<!-- END: Main Menu-->
