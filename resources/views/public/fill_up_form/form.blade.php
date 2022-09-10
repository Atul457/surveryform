@extends('layouts/fullLayoutMaster')

@section('title', 'Survey form')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
  <link rel="stylesheet" href="{{ asset('css/custom/formbuilder.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom/fillupform.css') }}">
@endsection
@section('content')
<div class="formContainer row px-2">
  <div class="card">
    @if ($errors->any())
    <div class="alert alert-danger p-1">
      {{$errors->all()[0]}}
    </div>
    @endif
  </div>

  <div class="card pt-1">
    @if(Session::has("error"))
    <div class="alert alert-danger p-1">
      {{Session::get('error')}}
    </div>
    @endif
  </div>

  @if(isset($form))
  <input 
    type="hidden" 
    id="form_json" 
    value="{{$form['form_json']}}"/>

  <div class="card col-12 page_header">
    <div class="card-header border-bottom">
      {{$form["form_name"]}}
    </div>
  </div>

  <form method="post" action="" class="col-12 px-0">
      <div class="fb-render">
        <textarea id="fb-template" class="d-none">
            <form-template>
            </form-template>
        </textarea>
      </div>
  </form>
  @endif
</div>
@endsection

<!-- @section('vendor-script')
@endsection -->

@section('page-script')
  <script src="{{asset('js/cdns/jquery-ui.min.js')}}"></script>
  <script src="{{asset('js/cdns/form-builder.min.js')}}"></script>
  <script src="{{asset('js/cdns/form-render.min.js')}}"></script>
  <script src="{{asset('js/custom/fillupform.js')}}">
@endsection

