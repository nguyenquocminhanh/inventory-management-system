@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Payment Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Payment History</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Invoice #{{ $payment['invoice']['invoice_number'] }}</strong></h4>
                                    <h3>
                                        <img src="{{ asset('backend/assets/images/logo-sm.png') }}" alt="logo" height="24"/> BJ's Shopping Mall
                                    </h3>
                                </div>
                                <hr>
                        
                                <div class="row">
                                    <div class="col-6 mt-4">
                                        <address>
                                            <strong>BJ's Shopping Mall</strong>
                                            <br>
                                            Boston, MA
                                            <br>
                                            minhanh.nguyenquoc@gmail.com
                                        </address>
                                    </div>
                                    <div class="col-6 mt-4 text-end">
                                        <address>
                                            <strong>Invoice Date:</strong>
                                            <br>
                                            {{ date('Y-m-d', strtotime($payment['invoice']['date'])) }}
                                            <br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Invoice</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>Customer Name</strong></td>
                                                    <td class="text-center"><strong>Customer Mobile</strong></td>
                                                    <td class="text-center"><strong>Customer Email</strong></td>
                                                    <td class="text-center"><strong>Customer Address</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                <tr>
                                                    <td>{{ $payment['customer']['name'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['phone_number'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['email'] }}</td>
                                                    <td class="text-center">{{ $payment['customer']['address'] }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>SL</strong></td>
                                                    <td class="text-center"><strong>Category</strong></td>
                                                    <td class="text-center"><strong>Product Name</strong></td>
                                                    <td class="text-center"><strong>Current Stock</strong></td>
                                                    <td class="text-center"><strong>Sell Qty</strong></td>
                                                    <td class="text-center"><strong>Quantity</strong></td>
                                                    <td class="text-end"><strong>Total Price</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $total_sum = '0';
                                                    $invoice_details = App\Models\InvoiceDetail::where('invoice_id', $payment->invoice_id)->get();
                                                @endphp
                                                <!-- 1 invoice co 1 hoac nhieu invoice_details -->
                                                <!-- with('invoice_detail') -->
                                                @foreach($invoice_details as $key => $detail)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td class="text-center">{{ $detail['category']['name'] }}</td>
                                                    <td class="text-center">{{ $detail['product']['name'] }}</td>
                                                    <td class="text-center">{{ $detail['product']['quantity'] }}</td>
                                                    <td class="text-center">{{ $detail->selling_qty }}</td>
                                                    <td class="text-center">${{ $detail->unit_price }}</td>
                                                    <td class="text-end">${{ $detail->selling_price }}</td>
                                                </tr>
                                                @php 
                                                    $total_sum += $detail->selling_price;
                                                @endphp
                                                @endforeach


                                                <tr>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line"></td>
                                                    <td class="thick-line text-center">
                                                        <strong>Subtotal</strong></td>
                                                    <td class="thick-line text-end">${{ $total_sum }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Discount</strong></td>
                                                    <td class="no-line text-end">${{ $payment->discount_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Grand Amount</strong></td>
                                                    <td class="no-line text-end"><h4 class="m-0">${{ $payment->total_amount }}</h4></td>
                                                </tr>
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Paid Amount</strong></td>
                                                    <td class="no-line text-end">${{ $payment->paid_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center" style="background-color: #ddd;">
                                                        <strong>Due Amount</strong></td>
                                                    <td class="no-line text-end" style="background-color: #ddd;"><h4 class="m-0">${{ $payment->due_amount }}</h4></td>
                                                </tr>

                                                <tr>
                                                    <td colspan="7" style="text-align: center; font-weight: bold;">Paid Summary</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;">Date</td>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;">Paid Amount</td>
                                                </tr>

                                                @php
                                                    $payment_details = App\Models\PaymentDetail::where('invoice_id', $payment->invoice_id)->get();
                                                @endphp

                                                @foreach($payment_details as $key => $item)
                                                <tr>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;">{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;">${{ $item->current_paid_amount }}</td>
                                                </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;">Total Paid</td>
                                                    <td colspan="4" style="text-align: center; font-weight: bold;"><h4 class="m-0">${{ $payment->paid_amount }}</h4></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-print-none">
                                            <div class="float-end">
                                                <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                                <a href="#" class="btn btn-primary waves-effect waves-light ms-2">Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection