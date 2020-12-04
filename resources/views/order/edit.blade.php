@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Order') }}</div>

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
                        <form id="form-order" action="/orders/edit" method="post">
                            @csrf
                            <input type="hidden" name="id" id="id" value="{{$order->id}}">
                            <input type="hidden" name="status" id="status" value="edited">
                            <input type="hidden" name="order_items" id="orderitems">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Customer:</label>
                                <div class="col-sm-10">
                                    <select class="selectpicker show-tick form-control" name="customer_id" data-live-search="true" title="Nothing selected" data-size="5" required>
                                        @foreach($customers as $item)
                                        <option value="{{$item->id}}" @if($item->id == $order->customer_id) {{__('selected')}} @endif>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="description">Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="description" rows="5">{{$order->description}}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="product-list">Products:</label>

                                <div class="col-sm-6">
                                    <select class="selectpicker show-tick form-control" id="product-list" data-live-search="true" title="Nothing selected" data-size="5">
                                        @foreach($products as $item)
                                        <option value="{{$item->id}}" data-subtext="Stock: {{$item->stock}}" data-stock="{{$item->stock}}" data-name="{{$item->name}}" data-description="{{$item->description}}" data-price="{{$item->price}}" @if($item->id == $order->product_id) {{__('selected')}} @endif>{{$item->name}} - {{$item->description}} - ${{$item->price}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input class="form-control" type="number" id="product-quantity" placeholder="Quantity:" min="0">
                                </div>
                                <div class="col-sm-2">
                                    <a id="product-button-add" class="btn btn-outline-success btn-block" onclick="productAdd()">Add</a>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="product-list">Products list:</label>
                                <div id="product-list" class="col-sm-10">
                                    <table id="products-list-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID:</th>
                                                <th>Name:</th>
                                                <th>Quantity:</th>
                                                <th>Amount:</th>
                                                <th>Options:</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($order_items == null)
                                            <tr id="products-list-void">
                                                <td colspan="5" class="text-center">No products on the list.</td>
                                            </tr>
                                            @else
                                            @foreach($order_items as $item)
                                            <tr class='products-list-item'>
                                                <td class='products-list-id' name='teste-nome'>{{$item->product->id}}</td>
                                                <td class='products-list-name'>{{$item->product->name}}</td>
                                                <td class='products-list-quantity'>{{$item->quantity}}</td>
                                                <td class='products-list-amount'>{{$item->product->price}}</td>
                                                <td><button class="btn btn-danger btn-block btn-sm" onclick="productDelete(this)">Delete</button></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Total Amount:</label>
                                <div class="offset-sm-8 col-sm-2">
                                    <input class="form-control" type="text" name="total-amount" id="total-amount" disabled value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <a href="/customers" class="btn btn-primary">Back</a>
                                    <input type="submit" value="Create" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        productsTableUpdate();
    }

    function productAdd() {
        if ($("#product-list option:selected").index() == 0) {
            console.log('selecione um produto');
            return;
        }
        if ($("#product-quantity").val() == '' || $("#product-quantity").val() == '0') {
            console.log('quantidade invalida ou vazia');
            return;
        }

        $("#products-list-void").remove();

        var id = $("#product-list option:selected").val();
        var name = $("#product-list option:selected").attr('data-name');
        var description = $("#product-list option:selected").attr('data-description');
        var stock = $("#product-list option:selected").attr('data-stock');
        var price = parseFloat($("#product-list option:selected").attr('data-price'));
        var quantity = parseInt($("#product-quantity").val());

        //pesquisar na lista se ja existe um id igual, caso sim apenas alterar, do contrario realizar a inserção.
        var searchInList = false;
        $("#products-list-table tbody tr").each(function() {
            if ($(this).find('.products-list-id').text() == id) {
                $(this).children('.products-list-quantity').text(quantity);
                searchInList = true;
                return;
            }
        });
        if (!searchInList) {
            // Append product to the table
            $("#products-list-table tbody").append("<tr class='products-list-item'>" +
                "<td class='products-list-id' name='teste-nome'>" + id + "</td>" +
                "<td class='products-list-name'>" + name + "</td>" +
                "<td class='products-list-quantity'>" + quantity + "</td>" +
                "<td class='products-list-amount'>" + price + "</td>" +
                "<td>" + '<button class="btn btn-danger btn-block btn-sm" onclick="productDelete(this);">Delete</button>' + "</td>" +
                "</tr>");
        }
        productsTableUpdate();
    }

    function productDelete(ctl) {
        $(ctl).parents("tr").remove();
        if ($("#products-list-table tbody").children().length == 0) {
            $("#products-list-table tbody").append('<tr id="products-list-void">' +
                '<td colspan="5" class="text-center">' +
                'No products on the list.' +
                '</td>' +
                '</tr>');
        }
        productsTableUpdate();
    }

    function productsTableUpdate() {
        var total = 0;
        var itens = [];
        $(".products-list-item").each(function() {

            if ($(this).find('.products-list-quantity').text() != '' && $(this).find('.products-list-amount').text() != '') {
                total = total + (parseFloat($(this).find('.products-list-amount').text()) * parseInt($(this).find('.products-list-quantity').text()));
                itens.push({
                    'id': $(this).find('.products-list-id').text(),
                    'name': $(this).find('.products-list-name').text(),
                    'description': $(this).find('.products-list-description').text(),
                    'price': $(this).find('.products-list-amount').text(),
                    'quantity': $(this).find('.products-list-quantity').text()
                });
            }
        });
        $("#orderitems").val(JSON.stringify(itens));
        $("#total-amount").val(total);
    }
</script>
@endsection