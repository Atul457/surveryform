@extends('layouts/contentLayoutMaster')

@section('title', 'Allocation Details')

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
      <div class="card">
          <div class="card-header">
              <div class="alert alert-danger p-1 col-12 mb-0 rounded-3">
                  {{$errors->all()[0]}}
              </div>
            </div>
        </div>
      @endif


      @if(count($details))
      <div class="row">
        <!-- User Card starts-->
        <div class="col-12">
        <div class="card user-card">
            <div class="card-body">
            <div class="row">
                <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">
                    <div class="user-avatar-section">
                        <h4 class="mb-1">User Details</h4>
                        <div class="d-flex justify-content-start">
                            <div class="d-flex flex-column">
                                <div class="user-info mb-1">
                                    <div class="row">

                                        <div class="col-6 fw-bold">
                                            Name
                                        </div>
                                        <div class="col-6 text-capitalize">
                                            {{$details[0]["name"]}}
                                        </div>

                                        <div class="col-6 fw-bold">
                                            Email
                                        </div>
                                        <div class="col-6">
                                            {{$details[0]["email"]}}
                                        </div>
                                        
                                        <div class="col-6 fw-bold">
                                            City
                                        </div>
                                        <div class="col-6 text-capitalize">
                                            {{$details[0]["city_name"]}}
                                        </div>

                                        <div class="col-6 fw-bold">
                                            Area
                                        </div>
                                        <div class="col-6 text-capitalize">
                                            {{$details[0]["area_name"]}}
                                        </div>

                                        <div class="col-6 fw-bold">
                                            Company
                                        </div>
                                        <div class="col-6 text-capitalize">
                                            {{$details[0]["comp_name"]}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12 mt-2 mt-xl-0">
                    <div class="user-avatar-section">
                        <h4 class="mb-1">Details Regarding Allocation</h4>
                        <div class="d-flex justify-content-start">
                            <div class="d-flex flex-column">
                                <div class="user-info mb-1">
                                    <div class="row">

                                        <div class="col-6 fw-bold">
                                            Status
                                        </div>
                                        <div class="col-6">
                                            @if($details[0]["is_completed"])
                                                <span class="badge badge-light-success">
                                                {{$details[0]["is_admin_completed"]  ? "Completed by admin" : "Completed"}}
                                                </span>
                                            @else
                                                <span class="badge badge-light-warning">Incomplete</span>
                                            @endif
                                        </div>

                                        <div class="col-6 fw-bold">
                                            Assigned
                                        </div>
                                        <div class="col-6">
                                            {{$details[0]["sample_size"]}}
                                        </div>
                                        
                                        <div class="col-6 fw-bold">
                                            Filled
                                        </div>
                                        <div class="col-6">
                                            {{$details[0]["filled_count"]}}
                                        </div>

                                        <div class="col-6 fw-bold">
                                            Remaining
                                        </div>
                                        <div class="col-6">
                                            {{$details[0]["remaining_count"]}}
                                        </div>
                                        
                                        @if($details[0]["is_admin_completed"])
                                        <div class="col-6 fw-bold">
                                            Completed by
                                        </div>
                                        <div class="col-6">
                                            {{$details[0]["completed_by"]}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        <!-- /User Card Ends-->

        @if(!$details[0]["is_completed"] || $details[0]["is_admin_completed"])
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{route('complete_survey')}}">
                        @csrf
                        <h4 class="mb-1 text-capitalize">Survey completion section</h4>
                        <input type="hidden" value="{{$details[0]['id']}}" name="id"/>
                        <input type="hidden" value=' {{$details[0]["is_admin_completed"]  ? "0" : "1"}}' name="complete"/>
                        <div class="row">
                            <div class="col-12">
                                <label for="reasonBox" class="w-100">
                                    Reason to complete the form
                                </label>
                                <textarea name="reason" rows="3" class="form-control" id="reasonBox" placeholder="Enter the reason for which you are completing up the form">{{$details[0]["reason"]}}</textarea>
                            </div>

                            <div class="d-flex justify-content-end col-12 mt-2">
                                <button class="btn btn-primary">
                                    {{$details[0]["is_admin_completed"]  ? "Incomplete the survey" : "Complete the survey"}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif   

    </div>
    @else
        <div class="card">
            <div class="success alert-success p-1 w-100">
                Something went wrong
            </div>
        </div>
    @endif


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
