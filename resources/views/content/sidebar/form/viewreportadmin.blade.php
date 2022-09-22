@extends('layouts/contentLayoutMaster')

@section('title', 'Survey report')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
  
@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/charts/chart-apex.css')) }}">
	<link rel="stylesheet" href="{{ asset('css/custom/viewreport.css') }}"/>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12">
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

                <!-- <div class="col-12 card p-2">
                    <div class="filters">Filters</div>
                    <div class="filtersCont">
                        city, area
                    </div>
                </div> -->

                <div class="col-12">
                    <div class="survey_result" id="surveyResult">
                    </div>
                </div>

            </div>
        </div>
    </section>
  </div>
</div>
@endsection

@section('vendor-script')
  {{-- vendor files --}}
  <script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        const formId = "{{$form_id}}";
    </script>
    <script src="{{asset('js/custom/reportAdmin.js')}}"></script>
@endsection
