
@extends('layouts/contentLayoutMaster')

@section('title', 'Employees')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
@endsection

@section('content')
<!-- Advanced Search -->
<section id="advanced-search-datatable">
  <div class="row">
    @if(Session::has("success"))
      <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="success alert-success p-1 w-100">
                    {{Session::get('success')}}
                </div>
            </div>
          </div>
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger p-1 w-100">
          {{$errors->all()[0]}}
      </div>
    @endif

    <div class="col-12">
      <div class="card">
        @accessible("createuserview")
        <div class="card-header border-bottom d-flex justify-content-end">
          <a href="{{url('createuserview')}}">
            <button
              class="btn btn-primary waves-effect waves-float waves-light">
              <i data-feather='plus'></i> Create Employee
            </button>
          </a>
        </div>
        @endaccessible

        <hr class="my-0" />
        <div class="card-datatable">
          <table class="dt-advanced-search table">
            <thead>
              <tr>
                <th></th>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Employee code</th>
                <th>Phone no</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>View Permissions</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div
    class="modal fade text-start modal-danger deleteModal"
    id="deleteUserModal"
    tabindex="-1"
    aria-labelledby="deleteUserModal"
    aria-hidden="true"
    >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-danger">Delete employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Are you sure you want to delete this employee?
            <form action="{{url('deleteuser')}}" id="deleteUserForm" method="post" class="d-none">
                @csrf
                <input 
                    type="hidden" 
                    name="user_id" 
                    id="del_user_id"/>
                </div>
                <div class="updatePassModalFields">
            </form>
        </div>
        <div class="modal-footer">
            <button 
                type="button" 
                class="btn btn-danger"
                onclick="confirmDeleteUser()"
                id="updatePassBtn">
                Delete
            </button>
        </div>
        </div>
    </div>
    </div>


    <!-- View permissions modal -->
    <div id="viewPermissionsModal" class="modal fade">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-transparent">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body px-5 pt-0 pb-3">
            <h3 class="text-center">Permissions given to user</h3>
            <form class="row mx-0" id="permissionsAssignedForm">
              <h4 class="my-2">Modules</h4>
              <div id="permissionModules"></div>
              <input type="hidden" id="userId">
              @accessible('update_permissions')
              <div class="text-center mt-2">
                <button 
                 class="btn btn-primary updatePermissionsBtn"
                 id="updatePermissionsBtn"
                 type="button" 
                 onclick="updatePermissions()">
                Update permissions
              </button>
              </div>
              @endaccessible
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- View permissions modal -->
  <!-- Modals -->
</section>
<!--/ Advanced Search -->

@endsection

@section('vendor-script')
{{-- vendor files --}}
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script>
    const showDeleteIcon = `@accessible('deleteuser')${true}@endaccessible` === "true",
    showEditIcon = `@accessible('users_view')${true}@endaccessible` === "true",
    showPermissionIcon = `@accessible('view_permissions')${true}@endaccessible` === "true"
  </script>
  <script src="{{ asset('js\custom\getusers.js') }}"></script>
@endsection