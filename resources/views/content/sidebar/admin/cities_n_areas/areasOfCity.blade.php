@extends('layouts/contentLayoutMaster')

@section('title', 'Areas of a city')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <section id="basic-horizontal-layouts">
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

                    <div class="card-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="card">
                                    @accessible("add_area")
                                    <div class="border-bottom d-flex justify-content-end">
                                    <div class="cities_n_areasfoot">
                                        <a href="{{url('addareaview')}}">
                                            <button class="btn btn-primary cities_n_areasbtns">Add Area</button>
                                        </a>
                                    </div>
                                    </div>
                                    <hr class="my-0" />
                                    @endaccessible
                                    <div class="card-datatable">
                                        <table class="dt-advanced-search table" id="areasOfACity">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Sr. No</th>
                                                    <th>Area Name</th>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
  </div>

  <!-- Modals -->
  <div
    class="modal fade text-start modal-danger deleteModal"
    id="deleteAreaModal"
    tabindex="-1"
    aria-labelledby="deleteAreaModal"
    aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this area?
                <br/>
                <form action="{{url('deletearea')}}" id="deleteAreaForm" method="post" class="d-none">
                    @csrf
                    <input 
                        type="hidden" 
                        name="area_id" 
                        id="del_area_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-danger"
                    onclick="confirmDeleteArea()"
                    id="deleteAreaBtn">
                    Delete
                </button>
            </div>
          </div>
      </div>
  </div>
  <!-- Modals -->

</div>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Page js files --}}
    <script>
        var cityId = "{{$cityid}}",
        showEditIcon = `@accessible('edit_area')${true}@endaccessible` === "true",
        showDeleteIcon = `@accessible('delete_area')${true}@endaccessible` === "true"
    </script>
	<script src="{{ asset('js\custom\citiesNAreas.js') }}"></script>
@endsection
