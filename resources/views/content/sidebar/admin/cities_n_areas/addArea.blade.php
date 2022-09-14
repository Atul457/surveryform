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
                    id="add_area_form"
                    onsubmit="return createArea();"
                    action="{{url('addarea')}}">
                    <div class="row align-items-end">
                        @csrf
                        <div class="col-md-5 mb-1">
                            <label class="form-label" for="citiesSelectBox">Cities</label>
                            <select 
                                class="select2InModal form-select"
                                name="city_ref"
                                id="citiesSelectBox">
                            @foreach($cities as $city)
                                <option value="{{$city['id']}}">{{$city['city_name']}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-5 col-12 mb-1">
                            <label class="form-label" for="citiesSelectBox">Area</label>
                            <input 
                                type="text"
                                placeholder="Area name"
                                class="form-control"
                                name="area_name"
                                id="area_name"/>
                        </div>

                        <div class="col-md-2 col-12 mb-1">
                            <button class="btn btn-primary">Add Area</button>
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
