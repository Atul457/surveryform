
@extends('layouts/contentLayoutMaster')

@section('title', 'Forms Allocated')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
<link rel="stylesheet" href="{{ asset('css/custom/myforms.css') }}"/>
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
            <div class="card-header border-bottom d-block">
                Showing result for <span class="text-capitalize fw-bold d-inline text-primary">{{$form_name}}</span> form.
            </div>
        </div>
    </div>
    
    <div class="col-12">
      <div class="card">
        <div class="card-header border-bottom d-flex justify-content-end">
            <a href="{{url('allocateformview')}}">
                <button
                class="btn btn-primary waves-effect waves-float waves-light">
                <i data-feather='plus'></i> Allocate form
                </button>
            </a>
        </div>
        <hr class="my-0" />
        <div class="card-datatable">
          <table class="dt-advanced-search table" id="formsAllocated">
            <thead>
              <tr>
                <th></th>
                <th>Sr. No</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Company Name</th>
                <th>City Name</th>
                <th>Area Name</th>
                <th>Share</th>
                <th>Created at</th>
                <th>Updated at</th>
                <th>Deallocate</th>
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
    id="deallocateFormModal"
    tabindex="-1"
    aria-labelledby="deallocateFormModal"
    aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Deallocate form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to deallocate this form for the employee?
                <form 
                    action="{{url('deallocateform')}}" 
                    id="deallocationForm" 
                    method="post" 
                    class="d-none">
                    @csrf
                    <input 
                        type="hidden" 
                        name="share_id" 
                        id="share_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-danger"
                    onclick="confirmDeallocation()"
                    id="deallocateFormBtn">
                    Deallocate
                </button>
            </div>
          </div>
      </div>
  </div>

  <!-- Share form modal -->
	<div
		class="modal fade text-start modal-primary shareFormModal"
		id="shareFormModal"
		tabindex="-1"
		aria-labelledby="shareFormModal"
		aria-hidden="true"
		>
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-primary">Share form</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body pb-2">
					<div class="share_fields">Copy the link below and share it to anyone</div>
					<input 
						class="form-control share_fields"
						type="text" 
						id="form_link"/>

					<div id="cunsumer_inputs_cont" class="share_fields">
						<div class="phone_num_fields share_fields">
							<div class="row shareModalinputs">

								<input
									type="text"
									placeholder="Name"
									autocomplete="off"
									class="name form-control">

								<input
									type="number"
									placeholder="Consumer phone no"
									autocomplete="off"
									onkeyup="validateNumber(this)"
									class="number form-control">

								<input
									type="text"
									placeholder="Location"
									autocomplete="off"
									class="location form-control">

							</div>
							
							<button
								type="button"
								class="btn btn-danger ml-1 remove_phone_btn">Remove</button>
						</div>
					</div>

					<div class="form_add_removeBtns share_fields">
						<button
							type="button"
							class="btn btn-success"
							onclick="addField()">Add</button>
					</div>
				</div>

				<div class="modal-footer">
					<button
						type="button"
						onclick="shareForm()"
						class="btn btn-primary">
						Send message
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Share form modal -->
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
        const form_id_for_datatable = "{{$form_id}}"
    </script>
  <script src="{{asset('js/custom/formsAllocated.js')}}"></script>
@endsection
