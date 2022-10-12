
@extends('layouts/contentLayoutMaster')

@section('title', 'My forms')

@section('vendor-style')
	{{-- vendor css files --}}
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
	<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
	{{-- Page Css files --}}
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
				<div class="card-datatable">
					<table class="dt-advanced-search table">
						<thead>
							<tr>
								<th></th>
								<th>Sr. No</th>
								<th>Form Name</th>
								<th>Form's Company</th>
								<th>Form's Product</th>
								<th>City</th>
								<th>Area</th>
								<th>Surveys assigned</th>
								<th>Surveys completed</th>
								<th>Surveys remaining</th>
								<th>Share</th>
								<th>View Report</th>
								<th>Created at</th>
								<th>Updated at</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modals -->

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

				<div class="modal-footer justify-content-center">
					<button
						type="button"
						onclick="shareForm()"
						id="sendMessageBtn"
						class="btn btn-primary updatePermissionsBtn">
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
	<script src="{{ asset('js\custom\userforms.js') }}"></script>
@endsection