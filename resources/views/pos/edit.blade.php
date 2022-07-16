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

                    <form  method="POST" action="" id="pos" class="mt-5">
                        @csrf
                        @foreach($data as $row)
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="control-label">Customer Name</label>
                                <select name="customer_id" class="form-control">
                                    <option>Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option name="customer_id" value="{{$customer->id}}" {{ $customer->id == $row->customer_id ? 'selected': ''}}>{{$customer->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="control-label">Date</label>
                                <input name="date" value="{{$row->date}}"  class="form-control datepicker" type="date" >
                                <input type="hidden" name="inventory_id" value="{{$row->id}}">
                            </div>
                        </div>

                        @endforeach

                        <table class="table table-bordered mt-5" id="pos">
                            <thead>
                            <tr>
                                <th scope="col">Product Name</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Net Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $row)
                                @foreach($row->inventoryProducts as $result)
                            <tr>
                                <td><select name="product_id[]" class="form-control productname" >
                                        <option>Select Product</option>
                                        @foreach($products as $product)
                                            <option name="product_id[]" value="{{$product->id}}" {{ $product->id == $result->product_id ? 'selected' : '' }} >{{$product->name}}</option>
                                        @endforeach
                                    </select></td>
                                <td><input type="text" name="qty[]" value="{{$result->qty}}" class="form-control qty" required ></td>
                                <td><input type="text" name="price[]" value="{{$result->rate}}" class="form-control price" required ></td>
                                <td><input type="text" name="dis[]"  value="{{$result->discount}}" class="form-control dis" required ></td>
                                <td><input type="text" name="amount[]" value="{{($result->qty *  $result->rate) - $result->discount}}" class="form-control amount" required ></td>
                                <input type="hidden" name="inventoryProduct_id[]" value="{{$result->id}}"  />

                            </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                            <tfoot>
                            @foreach($data as $row)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total</b></td>
                                <td><input type="number" name="totalBillAmount" value="{{$row->totalBillAmount}}" class="total" readonly ></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Total Discount</b></td>
                                <td> <input type="number" name="totalDiscount" class="totalDiscount" value="{{$row->totalDiscount}}" readonly > </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Paid Amount</b></td>
                                <td> <input type="number" name="paidAmount" value="{{$row->paidAmount}}" class="paidAmount" > </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Due Amount</b></td>
                                <td> <input type="number" name="dueAmount" class="dueAmount" value="{{$row->dueAmount}}" readonly > </td>
                                <td></td>
                            </tr>
                            @endforeach
                            </tfoot>

                        </table>

                        <div >
                            <button class="btn btn-primary float-end" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('tbody').delegate('.productname', 'change', function () {
                var  tr = $(this).parent().parent();
                tr.find('.qty').focus();
            })
            $('tbody').delegate('.productname', 'change', function () {

                var tr =$(this).parent().parent();
                var id = tr.find('.productname').val();
                var dataId = {'id':id};
                $.ajax({
                    type    : 'GET',
                    url     :'{!! URL::route('findPrice') !!}',
                    dataType: 'json',
                    data: {"_token": $('meta[name="csrf-token"]').attr('content'), 'id':id},
                    success:function (data) {
                        tr.find('.price').val(data.rate);

                    }
                });
            });




            $('tbody').delegate('.qty,.price,.dis', 'keyup', function () {
                var tr = $(this).parent().parent();
                var qty = tr.find('.qty').val();
                var price = tr.find('.price').val();
                var dis = tr.find('.dis').val();
                var amount = (qty * price)-dis;
                tr.find('.amount').val(amount);
                total();
                totalDiscount();
            });


            function total(){
                var total = 0;
                $('.amount').each(function (i,e) {
                    var amount =$(this).val()-0;
                    total += amount;
                })
                $('.total').val(total);

            }

            function totalDiscount(){
                var totalDiscount = 0;
                $('.dis').each(function (i,e) {
                    var dis = $(this).val()-0;
                    totalDiscount += dis;
                })
                $('.totalDiscount').val(totalDiscount);
            }

            $('.paidAmount').on('change',function () {

                var  total = $('.total').val();
                var  paid = $('.paidAmount').val();
                var due =   total - paid;
                $('.dueAmount').val(due);

            })



            $('.addRow').on('click', function () {
                addRow();
            });
            function addRow() {
                var addRow = '<tr>\n' +
                    '         <td><select name="product_id[]" class="form-control productname " >\n' +
                    '         <option value="0" selected="true" disabled="true">Select Product</option>\n' +
                    '                                        @foreach($products as $product)\n' +
                    '                                            <option value="{{$product->id}}">{{$product->name}}</option>\n' +
                    '                                        @endforeach\n' +
                    '               </select></td>\n' +
                    '                                <td><input type="text" name="qty[]" class="form-control qty" ></td>\n' +
                    '                                <td><input type="text" name="price[]" class="form-control price" ></td>\n' +
                    '                                <td><input type="text" name="dis[]" class="form-control dis" ></td>\n' +
                    '                                <td><input type="text" name="amount[]" class="form-control amount" ></td>\n' +
                    '                                <td><a   class="btn btn-danger remove"> <i class="mdi mdi-trash-can"></i></a></td>\n' +
                    '                             </tr>';
                $('tbody').append(addRow);
            };

            $(document).on('click', '.remove', function() {

                var l =$('tbody tr').length;
                if(l==1){
                    alert('you cant delete first row')
                }
                else
                    $(this).parent().parent('tr').remove();
            });

            $("#pos").on('submit',function (e) {
                e.preventDefault();

                $.ajax({
                    type    : 'POST',
                    url     :'{!! URL::route('pos.update') !!}',
                    data:  $('#pos').serialize(),
                    success:function (response) {
                        alert('Data has been submitted successfully');
                        location.reload()

                    }
                });

            })

        });


    </script>
@endsection
