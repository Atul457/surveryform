@extends('layouts/contentLayoutMaster')

@section('title', 'Edit area')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
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
                    action="{{url('updatearea')}}">
                    <div class="row align-items-start">
                        @csrf
                        <div class="col-md-5 mb-1">
                            <select 
                                class="select2InModal form-select"
                                name="city_ref"
                                id="citiesSelectBox">
                                    @foreach($cities as $city)
                                        <option 
                                        value="{{$city['id']}}"
                                        @if($city['id']==$area["city_ref"]) selected="selected" @endif>
                                            {{$city['city_name']}}
                                        </option>
                                    @endforeach
                            </select>
                        </div>

                        <div class="col-md-5 col-12 mb-1">
                            <input 
                                type="text"
                                placeholder="Area name"
                                class="form-control"
                                value="{{$area['area_name']}}"
                                name="area_name"
                                id="area_name"/>

                            <input 
                                type="hidden"
                                value="{{$area['id']}}"
                                name="area_id"/>
                                
                            <input 
                                type="hidden"
                                value="{{$area['city_ref']}}"
                                name="old_city_ref"/>
                        </div>

                        @accessible("update_area")
                        <div class="col-md-2 col-12 mb-1">
                            <button class="btn btn-primary">Update</button>
                        </div>
                        @endaccessible
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
