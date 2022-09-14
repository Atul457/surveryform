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
                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="citiesSelectBox">Cities</label>
                            <select 
                                class="select2InModal form-select"
                                onchange="showRelatedAreas(this)"
                                id="citiesSelectBox">
                            @foreach($cities as $city)
                                <option value="{{$city['id']}}">{{$city['city_name']}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label" for="areasSelectBox">Areas</label>
                            <div class="mb-1">
                                <select class="select2InModal form-select" id="areasSelectBox"></select>
                            </div>
                        </div>

                        <div class="cities_n_areasfoot">
                            <a href="{{url('addcityview')}}">
                                <button class="btn btn-primary cities_n_areasbtns">Add City</button>
                            </a>
                            <a href="{{url('addareaview')}}">
                                <button class="btn btn-primary cities_n_areasbtns">Add Area</button>
                            </a>
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
