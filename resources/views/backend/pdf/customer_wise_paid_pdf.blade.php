@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Wise Paid Report</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Wise Paid Report</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        @php 
            $customer = App\Models\Customer::findOrFail($customer_id);
        @endphp

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16"><strong>Customer Name: {{ $customer->name }}</strong></h4>

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
                    
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Wise Paid Report</strong></h3>
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
                                                    <td>{{ $customer->name }}</td>
                                                    <td class="text-center">{{ $customer->phone_number }}</td>
                                                    <td class="text-center">{{ $customer->email }}</td>
                                                    <td class="text-center">{{ $customer->address }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- end row -->

                        <br>
                        <br>

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
                                                    <td class="text-center"><strong>Customer Name</strong></td>
                                                    <td class="text-center"><strong>Invoice Number</strong></td>
                                                    <td class="text-center"><strong>Date</strong></td>
                                                    <td class="text-end"><strong>Total Amount</strong></td>
                                                    <td class="text-end"><strong>Paid Amount</strong></td>
                                                    <td class="text-end"><strong>Due Amount</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $total_due = '0';
                                                    $total_paid = '0';
                                                @endphp
                                                <!-- 1 invoice co 1 hoac nhieu invoice_details -->
                                                <!-- with('invoice_detail') -->
                                                @foreach($allData as $key => $payment)
                                                <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                    <td class="text-center">{{ $payment['customer']['name'] }}</td>
                                                    <td class="text-center">#{{ $payment['invoice']['invoice_number'] }}</td>
                                                    <td class="text-center">{{ date('d-m-Y', strtotime($payment['invoice']['date'])) }}</td>
                                                    <td class="text-end">${{ $payment->total_amount }}</td>
                                                    <td class="text-end">${{ $payment->paid_amount }}</td>
                                                    <td class="text-end">${{ $payment->due_amount }}</td>
                                                </tr>
                                                @php 
                                                    $total_due += $payment->due_amount;
                                                    $total_paid += $payment->paid_amount;
                                                @endphp
                                                @endforeach

                                                <tr style="background-color: #ddd">
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Total Paid Amount</strong></td>
                                                    <td class="no-line text-end"><h4 class="m-0">${{ $total_paid }}</h4></td>
                                                </tr>

                                                <tr style="background-color: #ddd">
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Total Due Amount</strong></td>
                                                    <td class="no-line text-end"><h4 class="m-0">${{ $total_due }}</h4></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        @php
                                            $date = new Datetime('now', new DateTimeZone('America/New_York'));
                                        @endphp

                                        <i>Printing time: {{ $date->format('F j, Y, g:i a') }}</i>

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