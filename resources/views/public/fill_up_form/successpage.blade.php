@extends('layouts/fullLayoutMaster')

@section('title', 'Form submission successfull')

@section('page-style')
  {{-- Page Css files --}}
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

  <div class="card col-12 page_header">
    <div class="comp_details">
      <div class="card_header">
        {{Session::get('success')}} 
      </div>
    </div>
        
    <div class="form_header_fields">
      <label>Thanks for filling up the form.</label>
    </div>

  </div>

</div>
@endsection

<!-- @section('vendor-script')
@endsection -->

<!-- @section('page-script')
@endsection -->

