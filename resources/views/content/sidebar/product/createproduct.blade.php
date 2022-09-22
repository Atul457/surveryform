@extends('layouts/contentLayoutMaster')

@section('title', 'Create product')

@section('content')
<div class="row">
  <div class="col-12">
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12">
            <div class="card">
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

                @if(count($comp))
                <div class="card-body">
                    <form 
                        class="form form-horizontal" 
                        action="{{url('createproduct')}}" 
                        method="post"
                        onsubmit="return createForm()"
                        id="createProdForm">
                        <div class="row">
                            @csrf
                            <div class="col-12 col-md-4 mb-2">
                                <input 
                                    type="text" 
                                    id="prod_name" 
                                    class="form-control" 
                                    name="prod_name" 
                                    placeholder="Product name" />
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <select 
                                    type="text" 
                                    id="company_selected" 
                                    class="form-control" 
                                    name="comp_id">
                                    @foreach($comp as $com)
                                        <option value="{{$com['id']}}">
                                        {{$com["comp_name"]}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-4 mb-2">
                                <input 
                                    type="text" 
                                    id="batch_no" 
                                    class="form-control" 
                                    name="batch_no" 
                                    placeholder="Batch number" />
                            </div>
                            
                            <div class="col-12 col-md-4 mb-2">
                                <input 
                                    type="text" 
                                    id="city_name" 
                                    class="form-control" 
                                    name="city" 
                                    placeholder="City" />
                            </div>

                            <div class="col-12 col-md-4 mb-2">
                                <input 
                                    type="number" 
                                    id="sample_size" 
                                    class="form-control" 
                                    name="sample_size" 
                                    placeholder="Sample size" />
                            </div>
                            
                            <div class="col-12 col-md-4 mb-2">
                                <select name="status" id="productStatus" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1">Create</button>
                            </div>

                        </div>
                    </form>
                </div>
                @else
                <div class="alert alert-danger p-1 mb-0 rounded-3 mt-2 mx-2 mb-2">
                    Create a company to be able to create a product.
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
<script src="{{asset('js/custom/createproduct.js')}}"></script>
@endsection
