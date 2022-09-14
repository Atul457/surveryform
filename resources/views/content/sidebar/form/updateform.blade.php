@extends('layouts/contentLayoutMaster')

@section('title', 'Update form')

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
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12">
            <div class="card">
                @if ($errors->any())
                    <div class="card-header">
                        <div class="alert alert-danger p-1 col-12 rounded-3">
                            {{$errors->all()[0]}}
                        </div>
                    </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-danger p-1 m-1">
                    {{Session::get('error')}}
                </div>
                @else
                <div class="card-body">
                    <form 
                        class="form form-horizontal" 
                        action="{{url('updateform')}}" 
                        method="post" 
                        onsubmit="return updateForm();"
                        id="updateFormRef">
                        <div class="row mb-2">
                            @csrf

                            <div class="col-12 col-md-6 mb-1">
                                <label>Form name</label>
                                <input 
                                    type="text" 
                                    required="required" 
                                    placeholder="Form name"
                                    class="form-control"
                                    value="{{$form['form_name']}}"
                                    name="form_name" 
                                    id="form_name"/>
                            </div>

                            <!-- {{print_r($form)}} -->
                            
                            <div class="col-12 col-md-4 d-none">
                                <input 
                                    type="hidden" 
                                    name="form_id" 
                                    value="{{$form['id']}}"
                                    id="update_form_id"/>
                            </div>
                            
                            <div class="col-12 col-md-4 d-none">
                                <input 
                                    type="hidden" 
                                    name="form_json" 
                                    id="form_json"
                                    value="{{$form['form_json']}}"/>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <label>Status</label>
                                <select 
                                    name="status"
                                    id="companyStatus"
                                    class="form-control">
                                    <option 
                                        value="1" 
                                        @if($form['status']=="1") selected="selected" @endif>
                                            Active
                                    </option>
                                    <option 
                                        value="0" 
                                        @if($form['status']=="0") selected="selected" @endif>
                                            InActive
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label>Companies</label>
                                <select 
                                    type="text" 
                                    id="company_selected" 
                                    class="form-control" 
                                    onchange="getProductOfComp()"
                                    name="comp_ref">
                                    @foreach($comp as $com)
                                        <option value="{{$com['id']}}"
                                        @if($com['id']==$form['comp_id']) selected="selected" @endif
                                        >
                                        {{$com["comp_name"]}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input 
                                type="hidden"
                                value="{{$form['prod_ref']}}"
                                id="selected_prod_hidden"/>

                            <div class="col-12 col-md-6 mb-1">
                                <label>Products</label>
                                <select 
                                    type="text" 
                                    id="product_id" 
                                    class="form-control"
                                    name="prod_ref"
                                    >
                                    <option>Loading products</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label>Survey start date</label>
                                <input 
                                type="date"
                                placeholder="Start date"
                                class="form-control"
                                name="start_date"
                                value="{{$form['start_date']}}"
                                min="<?php echo date("Y-m-d"); ?>"
                                id="start_date"/>
                            </div>

                            <div class="col-12 col-md-6 mb-1">
                                <label>Survey end date</label>
                                <input 
                                type="date" 
                                placeholder="End date"
                                class="form-control"
                                value="{{$form['end_date']}}"
                                min="<?php echo date("Y-m-d"); ?>"
                                name="end_date"
                                id="end_date"/>
                            </div>

                        </div>

                        <textarea name="formBuilderUpdater" id="formUpdater" class="d-none"></textarea>
                            <div class="d-flex justify-content-end mt-2 mb-3">
                                <button 
                                    type="submit"
                                    class="btn btn-success">
                                    Update form
                                </button>
                            </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    </section>
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

