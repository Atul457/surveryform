@extends('layouts/contentLayoutMaster')

@section('title', 'Create Employee')

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

                @if((count($prod) > 0) && (count($comp) > 0))
                <div class="card-body">
                    <form 
                        class="form form-horizontal" 
                        action="{{url('createuser')}}" 
                        method="post" 
                        id="createUserForm">
                        <div class="row">
                            @csrf
                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="text" 
                                    id="user_name" 
                                    class="form-control" 
                                    name="name" 
                                    placeholder="Employee name" />
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="email" 
                                    id="user_email" 
                                    class="form-control" 
                                    name="email" 
                                    placeholder="Employee email" />
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="text" 
                                    id="user_password" 
                                    class="form-control" 
                                    name="password" 
                                    placeholder="Employee password" />
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="text" 
                                    id="employee_code" 
                                    class="form-control"
                                    name="emp_code" 
                                    placeholder="Employee code" />
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <select 
                                    type="text" 
                                    id="company_selected" 
                                    class="form-control" 
                                    name="comp_ref">
                                    @foreach($comp as $com)
                                        <option value="{{$com['id']}}">
                                        {{$com["comp_name"]}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2">
                                <select name="status" id="userStatus" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="number" 
                                    id="phone_no" 
                                    class="form-control" 
                                    onkeydown="validateNumber(this)"
                                    name="phone_no" 
                                    placeholder="Employee phone no" />
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
                @else
                <div class="card-header">
                    <div class="alert alert-danger p-1 col-12 mb-0 rounded-3">
                        @if(count($comp) > 1)
                        Create a product first to create a employee, if the created product/products are inactive, activate them.
                        @else
                        if the created company/companies or product/products are inactive, activate them to creat a employee.
                        @endif
                    </div>
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
<script src="{{asset('js/custom/createuser.js')}}"></script>
@endsection
