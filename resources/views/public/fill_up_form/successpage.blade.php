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

  @if(Session::has("success"))
  <div class="card pt-1">
    <div class="alert alert-success p-1">
      {{Session::get('success')}}
      <br>
      <small>
        Thanks for filling up the form.
      </small>
    </div>
  </div>
  @endif

</div>
@endsection

<!-- @section('vendor-script')
@endsection -->

<!-- @section('page-script')
@endsection -->

