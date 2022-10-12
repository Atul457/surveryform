@extends('layouts/fullLayoutMaster')

@section('title', 'Forgot Password')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/base/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Login v1 -->
    <div class="card mb-0">
      <div class="card-body">
        <a href="#" class="brand-logo">
          <h2 class="brand-text text-primary ms-1">Survey Form</h2>
        </a>

        @if ($errors->any())
          <div class="alert alert-danger p-1">
              {{$errors->all()[0]}}
          </div>
        @endif

        @if(Session::has("error"))
          <div class="alert alert-danger p-1">
              {{Session::get('error')}}
          </div>
        @endif
        
        @if(Session::has("success"))
          <div class="success alert-success p-1">
              {{Session::get('success')}}
          </div>
        @endif

        <form class="auth-login-form mt-2" action="{{url('resetpass')}}" method="POST">
          @csrf
          <div class="mb-1">
            <label for="login-email" class="form-label">Enter the Otp</label>
            <input
              type="text"
              class="form-control"
              id="otp"
              name="otp"
              placeholder="Otp"
              tabindex="1"
              autofocus
              required
            />
            
            @if(Session::has("user_id"))
            <input
                type="hidden"
                class="form-control"
                id="user_id"
                name="user_id"
                placeholder="user_id"
                value="{{Session::get('user_id')}}"
                tabindex="1"
                autofocus
                required
                />
            @endif
          
          </div>

          <button class="btn btn-primary w-100 mt-2" tabindex="4">Verify</button>
          <p class="text-center mt-2">
            <a href="{{route('login')}}"> 
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left">
                <polyline points="15 18 9 12 15 6"></polyline>
              </svg>
              Back to login
            </a>
          </p>

        </form>
      </div>
    </div>
    <!-- /Login v1 -->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/auth-login.js'))}}"></script>
@endsection
