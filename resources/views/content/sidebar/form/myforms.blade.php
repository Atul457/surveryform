
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
				<div class="card-header border-bottom d-flex justify-content-end">
					<a href="url('create_form_view')">
					<button 
							class="btn btn-primary waves-effect waves-float waves-light">
							<i data-feather='plus'></i> Create Form
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
								<th>Form Name</th>
								<th>Company Name</th>
								<th>Start date</th>
								<th>End date</th>
								<th>Status</th>
								<th>Created at</th>
								<th>Updated at</th>
								<!-- <th>Share</th> -->
								<th>Allocated to</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modals -->

	<!-- Delete form modal -->
	<div
		class="modal fade text-start modal-danger deleteModal"
		id="deleteSurveyFormModal"
		tabindex="-1"
		aria-labelledby="deleteSurveyFormModal"
		aria-hidden="true"
		>
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-danger">Delete survey form</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Are you sure you want to delete this survey form?
					<br/>
					All the filled form details will be deleted
					<form action="{{url('deleteform')}}" id="deleteSuveyForm" method="post" class="d-none">
						@csrf
						<input 
								type="hidden" 
								name="form_id" 
								id="del_survey_id"/>
					</form>
				</div>
				<div class="modal-footer">
					<button 
							type="button" 
							class="btn btn-danger"
							onclick="confirmDeleteSuveyForm()"
							id="updatePassBtn">
							Delete
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Delete form modal -->

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
	<script src="{{ asset('js\custom\myforms.js') }}"></script>
@endsection