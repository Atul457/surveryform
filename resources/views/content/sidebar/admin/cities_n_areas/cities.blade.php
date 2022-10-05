@extends('layouts/contentLayoutMaster')

@section('title', 'Cities')

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
                                @allnotaccessible("addcityview", "addareaview")
                                <div class="border-bottom d-flex justify-content-end">
                                <div class="cities_n_areasfoot">
                                    @accessible("addcityview")
                                    <a href="{{url('addcityview')}}">
                                        <button class="btn btn-primary cities_n_areasbtns">Add City</button>
                                    </a>
                                    @endaccessible
                                    @accessible("addareaview")
                                    <a href="{{url('addareaview')}}">
                                        <button class="btn btn-primary cities_n_areasbtns">Add Area</button>
                                    </a>
                                    @endaccessible
                                </div>
                                </div>
                                <hr class="my-0" />
                                @endallnotaccessible
                                <div class="card-datatable">
                                    <table class="dt-advanced-search table" id="citiesDatatable">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Sr. No</th>
                                                <th>City Name</th>
                                                <th>Created at</th>
                                                <th>Updated at</th>
                                                <th>View Areas</th>
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
    id="deleteCityModal"
    tabindex="-1"
    aria-labelledby="deleteCityModal"
    aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete city</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this city?
                <br/>
                <form action="{{url('deletecity')}}" id="deleteCityForm" method="post" class="d-none">
                    @csrf
                    <input 
                        type="hidden" 
                        name="city_id" 
                        id="del_city_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-danger"
                    onclick="confirmDeleteCity()"
                    id="deleteCityBtn">
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
        const showViewAreasIcon = `@accessible('areas')${true}@endaccessible` === "true",
        showEditIcon = `@accessible('edit_city')${true}@endaccessible` === "true",
        showDeleteIcon = `@accessible('delete_city')${true}@endaccessible` === "true"
    </script>
	<script src="{{ asset('js\custom\citiesNAreas.js') }}"></script>
@endsection
