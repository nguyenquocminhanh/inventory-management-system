@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Invoice Approve</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        @php
            $payment = App\Models\Payment::where('invoice_id', $invoice->id)->first();
        @endphp
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Invoice Number: #{{ $invoice->invoice_number }} - {{ date('Y-m-d', strtotime($invoice->date)) }}</h4>

                        <a href="{{ route('invoice.pending') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;">
                            <i class="fa fa-list"> Pending Invoice List </i>
                        </a> 

                        <br></br>

                        <table class="table table-dark" width="100%">
                            <tbody>
                                <tr>
                                    <td><p>Customer Info</p></td>
                                    <td><p>Name: <strong>{{ $payment['customer']['name'] }}</strong></p></td>
                                    <td><p>Phone: <strong>{{ $payment['customer']['phone_number'] }}</strong></p></td>
                                    <td><p>Email: <strong>{{ $payment['customer']['email'] }}</strong></p></td>
                                </tr>

                                <tr>
                                    <td colspan="4"><p>Description: <strong>{{ $invoice->description }}</strong></p></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <form action="{{ route('invoice.approve.store', $invoice->id) }}" method="POST">
                        @csrf
                            <table border="1" class="table table-dark" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Product Name</th>
                                        <th class="text-center" style="background-color: #8B008B">Current Stock</th>
                                        <th class="text-center">Sell Qty</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Total Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $total_sum = '0';
                                    @endphp
                                    <!-- 1 invoice co 1 hoac nhieu invoice_details -->
                                    <!-- with('invoice_detail') -->
                                    @foreach($invoice['invoice_detail'] as $key => $detail)
                                    <tr>
                                        <input type="hidden" name="category_id[]" value="{{ $detail->category_id }}">
                                        <input type="hidden" name="product_id[]" value="{{ $detail->product_id }}">
                                        <!-- pass key la detail->id vao cho selling_qty => key cua selling_qty se la detail->id -->
                                        <input type="hidden" name="selling_qty[{{ $detail->id }}]" value="{{ $detail->selling_qty }}">

                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center">{{ $detail['category']['name'] }}</td>
                                        <td class="text-center">{{ $detail['product']['name'] }}</td>
                                        <td class="text-center" style="background-color: #8B008B">{{ $detail['product']['quantity'] }}</td>
                                        <td class="text-center">{{ $detail->selling_qty }}</td>
                                        <td class="text-center">{{ $detail->unit_price }}</td>
                                        <td class="text-center">{{ $detail->selling_price }}</td>
                                    </tr>
                                    @php 
                                        $total_sum += $detail->selling_price;
                                    @endphp
                                    @endforeach

                                    <tr>
                                        <td colspan="6">Sub Total</td>
                                        <td class="text-center">{{ $total_sum }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Discount</td>
                                        <td class="text-center">{{ $invoice['payment']['discount_amount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Grand Amount</td>
                                        <td class="text-center">{{ $invoice['payment']['total_amount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Paid Amount</td>
                                        <td class="text-center">{{ $invoice['payment']['paid_amount'] }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Due Amount</td>
                                        <td class="text-center">{{ $invoice['payment']['due_amount'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <center>
                                <button type="submit" class="btn btn-info">Approve Invoice</button>
                            </center>
                        </form>          

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>



@endsection