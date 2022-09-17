@extends('layouts/contentLayoutMaster')

@section('title', 'Allocate Form')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
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
          <div class="card-header">
              <div class="alert alert-danger p-1 col-12 mb-0 rounded-3">
                  {{$errors->all()[0]}}
              </div>
          </div>
      @endif

      <div class="card-body">
        <form 
          method="post"
          id="surveyAllocationForm"
          onsubmit="return allocateForm();"
          action="{{url('allocateform')}}">
          @csrf
          <div class="create_form_details row" id="formFieldsCont">

            <div class="col-12 col-md-6 mb-1">
              <label>Select company to get it's users</label>
              <select 
                  type="text" 
                  id="companiesSelectBox" 
                  class="form-control"
                  onchange="getUsersOfComp()">
                  @foreach($comp as $com)
                      <option value="{{$com['id']}}">
                      {{$com["comp_name"]}}
                      </option>
                  @endforeach
              </select>
            </div>

            <div class="col-12 col-md-6 mb-1">
                <label>Users</label>
                <select 
                    type="text" 
                    id="usersSelectBox" 
                    class="form-control"
                    name="user_ref">
                    <option>Loading users</option>
                </select>
            </div>

            <div class="col-12 col-md-6 mb-1">
                <label>Select company to get it's products</label>
                <select 
                    type="text" 
                    id="companiesSelectBoxForForms" 
                    class="form-control"
                    onchange="getProductOfComp()">
                    @foreach($comp as $com)
                        <option value="{{$com['id']}}">
                        {{$com["comp_name"]}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 mb-1">
                <label>Select product to get it's forms</label>
                <select 
                    type="text" 
                    id="product_id" 
                    class="form-control"
                    onchange="getFormsOfProd()">
                    <option>Loading products</option>
                </select>
                </select>
            </div>

            <div class="col-12 forms_of_product_cont">
                <label>Forms of the product selected</label>
                <div class="forms_of_product" id="formsOfProd">
                    Loading forms   
                </div>
            </div>
            
            <div class="selectedFormsCont col-12">
            <label>Selected forms</label>
                <div class="selectedForms" id="selectedForms">
                    No forms added
                </div>
            </div>

            <div class="col-md-6 mb-1">
                <label class="form-label" for="citiesSelectBox">Cities</label>
                <select 
                    class="select2InModal form-select"
                    name="city_ref"
                    onchange="showRelatedAreas(this)"
                    id="citiesSelectBox">
                @foreach($cities as $city)
                    <option value="{{$city['id']}}">{{$city['city_name']}}</option>
                @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-1">
                <label class="form-label" for="areasSelectBox">Area</label>
                <select 
                    class="select2InModal form-select"
                    name="area_ref"
                    id="areasSelectBox">
                    Loading areas
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-2 mb-3">
          <button 
            type="submit" 
            class="btn btn-primary">
              Allocate form
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/custom/formAllocation.js')}}"></script>
@endsection
