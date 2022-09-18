@extends('layouts/contentLayoutMaster')

@section('title', 'Update employee')

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

                @if (Session::has('other'))
                <div class="alert alert-danger p-1 m-1 mb-0">
                    {{Session::get('other')}}
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
                        action="{{url('updateuser')}}" 
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
                                    value="{{$user['name']}}"
                                    placeholder="Employee name" />
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="email" 
                                    id="user_email" 
                                    class="form-control" 
                                    name="email"
                                    value="{{$user['email']}}"
                                    placeholder="Employee email" />
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                            <input 
                                type="text" 
                                id="user_update_password" 
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
                                    value="{{$user['emp_code']}}"
                                    placeholder="Employee code" />
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="number" 
                                    id="phone_no" 
                                    class="form-control" 
                                    onkeydown="validateNumber(this)"
                                    name="phone_no" 
                                    value="{{$user['phone_no']}}"
                                    placeholder="Employee phone no" />
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2 d-none">
                                <input 
                                    type="hidden" 
                                    name="users_id" 
                                    value="{{$user['id']}}"
                                    id="update_user_id"/>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <select 
                                    type="text" 
                                    id="company_selected" 
                                    class="form-control" 
                                    name="comp_ref">
                                    @foreach($comp as $com)
                                        <option 
                                        value="{{$com['id']}}"
                                        @if($com['id']==$comp_id) selected="selected" @endif
                                        >
                                        {{$com["comp_name"]}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-6 mb-2">
                                <select 
                                    name="status"
                                    id="userStatus"
                                    class="form-control">
                                    <option 
                                        value="1" 
                                        @if($user['status']=="1") selected="selected" @endif>
                                            Active
                                    </option>
                                    <option 
                                        value="0" 
                                        @if($user['status']=="0") selected="selected" @endif>
                                            InActive
                                    </option>
                                </select>
                            </div>

                            <!-- <div class="col-12 col-md-6 mb-2">
                                <input 
                                    type="number" 
                                    id="phone_no" 
                                    class="form-control" 
                                    onkeydown="validateNumber(this)"
                                    name="phone_no" 
                                    value="{{$user['phone_no']}}"
                                    placeholder="Employee phone no" />
                            </div> -->

                            <div class="mb-2 d-flex justify-content-end">
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
<script src="{{asset('js/custom/createuser.js')}}"></script>
@endsection
