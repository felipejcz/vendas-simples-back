@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Product') }}</div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('success') }}
                    </div>
                    @elseif(session('error'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{ session('error') }}
                    </div>
                    @endif
                    <div class="row-fluid">
                        <form action="/products/edit" method="post">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{$product->id}}">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Name:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="name" id="name" value="{{$product->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="description">Description:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="description" id="description" value="{{$product->description}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="price">Price:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="price" id="price" value="{{$product->price}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="stock">Stock:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="stock" id="stock" value="{{$product->stock}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="active">Active:</label>
                                <div class="col-sm-10">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="active1" value="1" @if($product->active == 1) checked @endif>
                                        <label class="form-check-label" for="active1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="active" id="active2" value="0" @if($product->active == 0) checked @endif>
                                        <label class="form-check-label" for="active2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <a href="/products" class="btn btn-primary">Back</a>
                                    <input type="submit" value="Edit" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection