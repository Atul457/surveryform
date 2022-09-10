@extends('layouts/contentLayoutMaster')

@section('title', 'Update company')

@section('content')
<div class="row">
  <div class="col-12">
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12">
            <div class="card">
                @if ($errors->any())
                    <div class="card-header">
                        <div class="alert alert-danger p-1 col-12 m-1 rounded-3">
                            {{$errors->all()[0]}}
                        </div>
                    </div>
                @endif

                @if (Session::has('error'))
                <div class="alert alert-danger p-1">
                    {{Session::get('error')}}
                </div>
                @else
                <div class="card-body">
                    <form 
                        class="form form-horizontal" 
                        action="{{url('updatecompany')}}" 
                        method="post" 
                        id="createCompForm">
                        <div class="row">
                            @csrf
                            <div class="col-12 col-md-4 mb-2">
                                <input 
                                    type="text" 
                                    id="comp_name" 
                                    class="form-control" 
                                    name="comp_name"
                                    value="{{$comp['comp_name']}}"
                                    placeholder="Company name" />
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                            <input 
                                type="number"
                                placeholder="Customer care number"
                                name="comp_care_no" 
                                id="comp_care_no"
                                value="{{$comp['comp_care_no']}}"
                                onkeydown="validateNumber(this)"
                                class="form-control"/>
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <select 
                                    name="status"
                                    id="companyStatus"
                                    class="form-control">
                                    <option 
                                        value="1" 
                                        @if($comp['status']=="1") selected="selected" @endif>
                                            Active
                                    </option>
                                    <option 
                                        value="0" 
                                        @if($comp['status']=="0") selected="selected" @endif>
                                            InActive
                                    </option>
                                </select>
                            </div>
                                
                            <div class="col-12 col-md-4 d-none">
                                <input 
                                    type="hidden" 
                                    name="company_id" 
                                    value="{{$comp['id']}}"
                                    id="update_company_id"/>
                            </div>

                            <div class="col-12 mb-2">
                                <textarea 
                                placeholder="Company address"
                                name="comp_addr"
                                id="comp_addr" 
                                class="form-control">{{$comp['comp_addr']}}</textarea>
                            </div>

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary me-1">Update</button>
                            </div>
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

@section('page-script')
<script src="{{asset('js/custom/createcom.js')}}"></script>
@endsection
