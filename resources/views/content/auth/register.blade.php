@extends('layouts/fullLayoutMaster')

@section('title', 'Register Page')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset('css/base/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Register v1 -->
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

        <form class="auth-register-form mt-2" action="{{url('register_user')}}" method="POST">
          @csrf
          <div class="mb-1">
            <label for="register-username" class="form-label">Name</label>
            <input
              type="text"
              class="form-control"
              id="register-username"
              name="name"
              placeholder="john"
              aria-describedby="register-username"
              tabindex="1"
              autofocus
            />
          </div>
          <div class="mb-1">
            <label for="register-email" class="form-label">Email</label>
            <input
              type="text"
              class="form-control"
              id="register-email"
              name="email"
              placeholder="john@example.com"
              aria-describedby="register-email"
              tabindex="2"
            />
          </div>

          <div class="mb-1">
            <label for="register-password" class="form-label">Password</label>

            <div class="input-group input-group-merge form-password-toggle">
              <input
                type="password"
                class="form-control form-control-merge"
                id="register-password"
                name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="register-password"
                tabindex="3"
              />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
          </div>

          <button class="btn btn-primary w-100 mt-2" tabindex="5">Register</button>
        </form>

        <p class="text-center mt-2">
          <span>Already have an account?</span>
          <a href="{{url('login')}}">
            <span>Login now</span>
          </a>
        </p>

      </div>
    </div>
    <!-- /Register v1 -->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset('vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/scripts/pages/auth-register.js')}}"></script>
@endsection
