@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

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

        <form class="auth-login-form mt-2" action="{{url('admin/login_user')}}" method="POST">
          @csrf
          <div class="mb-1">
            <label for="login-email" class="form-label">Email</label>
            <input
              type="text"
              class="form-control"
              id="login-email"
              name="email"
              placeholder="john@example.com"
              aria-describedby="login-email"
              tabindex="1"
              autofocus
            />
          </div>

          <div class="mb-1">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="login-password">Password</label>
              <a href="{{url('auth/forgot-password-v1')}}">
                <small>Forgot Password?</small>
              </a>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
              <input
                type="password"
                class="form-control form-control-merge"
                id="login-password"
                name="password"
                tabindex="2"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="login-password"
              />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
          </div>
          <button class="btn btn-primary w-100 mt-2" tabindex="4">Login</button>
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
