@extends('layouts.master')


@section('content')


    <div class="row">

        <div class="col-12 mx-auto mt-3">
            <div class="card mb-md-0">
                <div class="card-body">
                    <div class="card-title">POS</div>

                    <div class="row">
                        <div class="col-sm-4 col-md-4">
                            <form method="GET" action="{{route('findPos')}}">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="billNo" placeholder="Enter Bill NO" class="form-control">
                                    <button class="btn btn-sm btn-info"  type="sumbit">Find</button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <form  method="POST" action="{{route('pos.store')}}" id="pos" class="mt-5">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="control-label">Customer Name</label>
                                <select name="customer_id" class="form-control">
                                    <option>Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option name="customer_id" value="{{$customer->id}}">{{$customer->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Date</label>
                                <input name="date"  class="form-control datepicker" type="date" >
                            </div>
                        </div>

                        <table class="table table-bordered mt-5" id="pos">
                            <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Net Amount</th>
                                <th scope="col"><a class="addRow"><i class="mdi mdi-plus "></i>Add</a></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><select name="product_id[]" class="form-control productname" >
                                        <option>Select Product</option>
                                        @foreach($products as $product)
                                            <option name="product_id[]" value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select></td>
                                <td><input type="text" name="qty[]" class="form-control qty" required ></td>
                                <td><input type="text" name="price[]" class="form-control price" required ></td>
                                <td><input type="text" name="dis[]" class="form-control dis" required ></td>
                                <td><input type="text" name="amount[]" class="form-control amount" required ></td>
                                <td><a   class="btn btn-danger remove"> <i class="mdi mdi-trash-can"></i></a></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total</b></td>
                                <td><input type="number" name="totalBillAmount" class="total" readonly ></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total Discount</b></td>
                                <td> <input type="number" name="totalDiscount" class="totalDiscount" readonly > </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Paid Amount</b></td>
                                <td> <input type="number" name="paidAmount" class="paidAmount" > </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Due Amount</b></td>
                                <td> <input type="number" name="dueAmount" class="dueAmount" readonly > </td>
                                <td></td>
                            </tr>
                            </tfoot>

                        </table>
                        <div >
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
