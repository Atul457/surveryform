@extends('layouts/contentLayoutMaster')

@section('title', 'Cities and areas')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<!-- <link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}"> -->
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12">
            <div class="card">
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
                    id="add_city_form"
                    onsubmit="return createCity();"
                    action="{{url('addcity')}}">
                    <div class="row">
                        @csrf
                        <div class="col-md-5 col-12 my-1">
                            <input 
                                type="text"
                                placeholder="City name"
                                class="form-control"
                                name="city_name"
                                id="city_name"/>
                        </div>

                        <div class="col-md-3 col-12 my-1">
                            <button class="btn btn-primary">Add City</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </div>
</div>
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Page js files --}}
	<script src="{{ asset('js\custom\citiesNAreas.js') }}"></script>
@endsection
