@extends('layouts/fullLayoutMaster')

@section('title', 'Survey form')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
  <link rel="stylesheet" href="{{ asset('css/custom/formbuilder.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom/fillupform.css') }}">
@endsection
@section('content')
<div class="formContainer row px-2">
  @if ($errors->any())
  <div class="card">
    <div class="alert alert-danger p-1">
      {{$errors->all()[0]}}
    </div>
  </div>
  @endif

  @if(Session::has("error"))
  <div class="card pt-1">
    <div class="alert alert-danger p-1">
      {{Session::get('error')}}
    </div>
  </div>
  @endif

  @if(isset($form))
  <input 
    type="hidden" 
    id="form_json" 
    value="{{$form['form_json']}}"/>

    <!-- {{print_r($form)}} -->

  <div class="card col-12 page_header">
    <!-- <div class="card_header">
      {{$form["user_ref"]}}
    </div> -->

    <div class="comp_details">
      <div class="card_header">
          {{$form["comp_name"]}} 
      </div>
      <div class="form_header_fields">
      <span class="values">{{$form["comp_addr"]}}</span>
      </div>
    </div>
        
    <div class="form_header_fields">
      <label>Customer care no :</label>
      <span class="values">
        {{$form["comp_care_no"]}}
      </span>
    </div>
    
    <div class="form_header_fields">
      <label>Survey creator :</label>
      <span class="values">
        {{$form["name"]}}
      </span>
    </div>
    
    <div class="form_header_fields">
      <label>Phone no :</label>
      <span class="values">
        {{$form["phone_no"]}}
      </span>
    </div>
    
    <div class="form_header_fields">
      <label>Date :</label>
      <span class="values">
        {{$form["start_date"]}}
      </span>
    </div>

  </div>

  <form method="post" action="{{url('saveform')}}" class="col-12 px-0" onsubmit="return getFormData()">
      @csrf
      <input
        type="hidden"
        id="data_filled"
        name="data_filled"/>

      <input
        type="hidden"
        id="user_ref"
        name="user_ref"
        value='{{$form["user_ref"]}}'/>

      <input
        type="hidden"
        id="survey_form_ref"
        name="survey_form_ref"
        value='{{$form["id"]}}'/>

      <div class="fb-render">
        <textarea id="fb-template" class="d-none">
            <form-template>
            </form-template>
        </textarea>
      </div>
  </form>

  <div class="col-12 mt-3 px-0">
    <div class="fw-bold">
      Batch no: {{$form["batch_no"]}}
    </div>
  </div>

  @endif
</div>
@endsection

<!-- @section('vendor-script')
@endsection -->

@section('page-script')
  <script src="{{asset('js/cdns/jquery-ui.min.js')}}"></script>
  <script src="{{asset('js/cdns/form-builder.min.js')}}"></script>
  <script src="{{asset('js/cdns/form-render.min.js')}}"></script>
  <script src="{{asset('js/custom/fillupform.js')}}">
@endsection

