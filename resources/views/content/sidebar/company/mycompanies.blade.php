
@extends('layouts/contentLayoutMaster')

@section('title', 'My companies')

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
			<div class="col-12">
				<div class="card">
						<div class="card-header border-bottom">
								<div class="danger alert-danger p-1 w-100">
								{{$errors->all()[0]}}
								</div>
						</div>
					</div>
			</div>
		@endif
    
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom d-flex justify-content-end">
          <a href="{{url('viewcompany')}}">
            <button 
              class="btn btn-primary waves-effect waves-float waves-light">
              <i data-feather='plus'></i> Create Company
            </button>
          </a>
        </div>
        <hr class="my-0" />
        <div class="card-datatable">
          <table class="dt-advanced-search table">
            <thead>
              <tr>
                <th></th>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Address</th>
                <th>Customer care no</th>
                <th>Status</th>
                <th>Created at</th>
                <th>Updated at</th>
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
    id="deleteCompanyModal"
    tabindex="-1"
    aria-labelledby="deleteCompanyModal"
    aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this company?
                <br/>
                All the products, and forms under this company will be deleted
                <form action="{{url('deletecompany')}}" id="deleteCompanyForm" method="post" class="d-none">
                    @csrf
                    <input 
                        type="hidden" 
                        name="company_id" 
                        id="del_company_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-danger"
                    onclick="confirmDeleteComp()"
                    id="updatePassBtn">
                    Delete
                </button>
            </div>
          </div>
      </div>
  </div>
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
  <script src="{{ asset('js\custom\mycompanies.js') }}"></script>
@endsection