@extends('layouts/contentLayoutMaster')

@section('title', 'Create Form')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
<link rel="stylesheet" href="{{ asset('css/custom/formbuilder.css') }}">
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
          id="create_forms_form"
          onsubmit="return createForm();"
          action="{{url('createform')}}">
          @csrf
          <input type="hidden" id="form_json" name="form_json"/>
          <div class="create_form_details row">

            <div class="col-12 col-md-6 mb-1">
              <label>Form name</label>
              <input 
                type="text"
                placeholder="Form name"
                class="form-control"
                name="form_name" 
                id="form_name"/>
            </div>

            <div class="col-12 col-md-6 mb-1">
              <label>Status</label>
              <select class="form-control" name="status">
                  <option value="1">Active</option>
                  <option value="0">InActive</option>
              </select>
            </div>

            <div class="col-12 col-md-6 mb-1">
              <label>Companies</label>
              <select 
                  type="text" 
                  id="company_selected" 
                  class="form-control" 
                  onchange="getProductOfComp()"
                  name="comp_ref">
                  @foreach($comp as $com)
                      <option value="{{$com['id']}}">
                      {{$com["comp_name"]}}
                      </option>
                  @endforeach
              </select>
          </div>

          <div class="col-12 col-md-6 mb-1">
              <label>Products</label>
              <select 
                  type="text" 
                  id="product_id" 
                  class="form-control"
                  name="prod_ref">
                  <option>Loading products</option>
              </select>
          </div>

          <div class="col-12 col-md-6 mb-1">
            <label>Survey start date</label>
            <input 
              type="date"
              placeholder="Start date"
              class="form-control"
              name="start_date"
              value="<?php echo date("Y-m-d"); ?>"
              id="start_date"/>
          </div>

          <div class="col-12 col-md-6 mb-1">
            <label>Survey end date</label>
            <input 
              type="date" 
              placeholder="End date"
              class="form-control"
              value="<?php echo date("Y-m-d"); ?>"
              name="end_date"
              id="end_date"/>
          </div>
        </div>
          
        <textarea name="formBuilder" id="formBuilder" class="d-none mt-2"></textarea>
        <div class="d-flex justify-content-end mt-2 mb-3">
          <button 
            type="submit" 
            class="btn btn-success">
              Create form
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/cdns/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/cdns/form-builder.min.js')}}"></script>
<script src="{{asset('js/cdns/form-render.min.js')}}"></script>
<script src="{{asset('js/custom/formbuilder.js')}}"></script>
<script src="{{asset('js/cdns/moment.min.js')}}"></script>
@endsection
