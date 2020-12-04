@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Orders') }}</div>

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
                    <div class="row-fluid" style="padding-bottom: 10px;">
                        <div class="text-right">
                            <a href="/orders/create" class="btn btn-success btn-sm">Add Order</a>
                        </div>
                    </div>
                    <div class="row-fluid">
                        @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code:</th>
                                        <th>Date:</th>
                                        <th>Customer:</th>
                                        <th>Description:</th>
                                        <th>Amount:</th>
                                        <th>Status:</th>
                                        <th>Options:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->customer->name}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>-</td>
                                        <td>{{$item->status}}</td>
                                        <td class="text-center">
                                            <a href="/orders/edit/{{$item->id}}" class="btn btn-outline-info btn-sm">Edit</a>
                                            <a class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{$item->id}}')">Delete</a>
                                        </td>


                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        Table orders void.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(id) {
        if (confirm('Do you really want to delete this order?')) {
            window.location.href = "/orders/delete/" + id;
        }
    }
</script>
@endsection