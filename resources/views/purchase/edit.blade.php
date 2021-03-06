@extends('layouts.master')

@section('title', 'Edit Purchase Orders')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form class="form-group" action="{{route('purchase.update')}}" method="post">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="del_branch">PURCHASE CODE:</label>
                                <input type="text" class="form-control" id="po_number" name="po_number" value={{ $data->po_number }} readonly>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">SUPPLIER NAME:</label>
                            <select style="height:20%" name="supplier_id" id="supplier_id" class="form-control {{$errors->has('supplier_id') ? 'is-invalid' : ''}}" required >
                                <option value="{{ $data->supplier_id }}">{{ $data->supplier->supplier_name }}</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <div class="form-group">
                                <label for="req_branch">REQUESTED BRANCH:</label>
                            <select style="height:20%" name="req_branch" id="req_branch" class="form-control {{$errors->has('req_branch') ? 'is-invalid' : ''}}" required >
                             <option value="{{ $data->req_branch }}">{{ $data->reqbranch->branch_name }}</option>
                            </select>
                            </div>     
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="del_branch">DELIVER TO BRANCH:</label>
                            <select style="height:20%" name="del_branch" id="del_branch" class="form-control {{$errors->has('del_branch') ? 'is-invalid' : ''}}" required >
                                <option value="{{ $data->del_branch }}">{{ $data->delbranch->branch_name }}</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <div class="form-group">
                                <label for="requested_by">REQUESTED BY:</label>
                                <input type="text" name="requested_by" id="requested_by" class="form-control {{$errors->has('requested_by') ? 'is-invalid' : ''}}" value="{{ $data->requested_by }}" readonly>
                                @if($errors->has('requested_by'))
                                <span class="invalid-feedback">
                                    <strong>{{$errors->first('requested_by')}}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <input type="hidden" name="count" id="inc" value="1">
                                        <th width="25%">Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price Minimum</th>
                                        <th>Price Maximum</th>
                                        <th style="text-align: center"><a href="#" data-count="1" id="addForm" class="btn btn-info addRow"><i class="fas fa-plus-circle"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->order as $item)
                                    <tr class="product-row">
                                        <td>
                                            <select style="height:20%" name="product_name[]" class="form-control">
                                                @foreach ($products as $product)
                                                <option value="{{ $product->product_id }}">{{ $item->product->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="quantity[]" value="{{ $item->quantity }}" >
                                            <input type="hidden" name="quantity_old[]" value="{{ $item->quantity }}">
                                        </td>
                                        <td><input type="number" class="form-control price_min" name="price_min[]" value="{{ $item->price_min }}" readonly></td>
                                        <td><input type="number" class="form-control price_max" name="price_max[]" value="{{ $item->price_max }}" readonly></td>
                                        <td style="text-align: center"><a href="#" class="btn btn-danger remove"><i class="fas fa-minus"></i></a></td>
                                    </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning">Change</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('change', '.product_name', function(e){
    
    $curr_count = $(this).data('count');
    var price = $(this).children('option:selected').data('price');
    var pricemax = $(this).children('option:selected').data('pricemax');
    $('#price_min'+$curr_count).val(price);
    $('#price_max'+$curr_count).val(pricemax);

});
    $(document).ready(function(){
        $(document).on('click', '#addForm', function(e){
        $curr_count = $('#inc').val();
        addRow($curr_count);
        $('#inc').val( +$curr_count+ 1)
        var row_counter = $curr_count;
        });

            function addRow($curr_count){
            var tr = '<tr class="product-row">'+
                            '<td>'+
                                '<select data-count="'+$curr_count+'" style="height:20%" name="product_name[]" id="product_name'+$curr_count+'" class="form-control product_name {{$errors->has('unit_id') ? 'is-invalid' : ''}}">'+
                                    '<option disabled selected value> -- select an option -- </option>'+
                                        @foreach($products as $product)
                                            '<option value="{{ $product->id }}" data-price="{{ $product->price_min }}" data-pricemax="{{ $product->price_max }}" >{{ $product->product_name }}</option>'+
                                        @endforeach
                                '</select>'+
                            '</td>'+
                            '<td><input type="number" class="form-control" id="quantity'+$curr_count+'" name="quantity[]"></td>'+
                            '<td><input type="number" class="form-control price_min" id="price_min'+$curr_count+'" name="price_min[]" readonly></td>'+
                            '<td><input type="number" class="form-control price_max" id="price_max'+$curr_count+'" name="price_max[]" readonly></td>'+
                            '<td style="text-align: center"><a href="#" class="btn btn-danger remove"><i class="fas fa-minus"></i></a></td>'+
                     '</tr>';
                $('tbody').append(tr);                          
            }                            
        $('tbody').on('click', '.remove', function(){
            $(this).parent().parent().remove();
        });
    });
</script>



@endsection