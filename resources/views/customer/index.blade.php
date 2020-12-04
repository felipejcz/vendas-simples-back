@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Customer') }}</div>

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
                            <a href="/customers/create" class="btn btn-success btn-sm">Add Customer</a>
                        </div>
                    </div>
                    <div class="row-fluid">
                        @if(count($customers) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code:</th>
                                        <th>Name:</th>
                                        <th>Document:</th>
                                        <th>Active:</th>
                                        <th>Options:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->document}}</td>
                                        <td>@if($item->active == 1) Yes @else No @endif</td>
                                        <td class="text-center">
                                            <a href="/customers/edit/{{$item->id}}" class="btn btn-outline-info btn-sm">Edit</a>
                                            <a class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{$item->id}}')">Delete</a>
                                        </td>


                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        Table customers void.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(id) {
        if (confirm('Do you really want to delete this customer?')) {
            window.location.href = "/customers/delete/" + id;
        }
    }
</script>
@endsection