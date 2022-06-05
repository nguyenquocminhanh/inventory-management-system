@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Purchase</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Purchase</li>
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
                                    <h4 class="float-end font-size-16"><strong>Purchase #{{ $purchase->purchase_number }}</strong></h4>
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
                                            <strong>purchase Date:</strong>
                                            <br>
                                            {{ date('Y-m-d', strtotime($purchase->date)) }}
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
                                        <h3 class="font-size-16"><strong>Purchase Invoice</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <td><strong>Purchaser</strong></td>
                                                    <td><strong>Purchaser Email</strong></td>
                                                    <td><strong>Purchaser Username</strong></td>
                                                    <td><strong>Purchaser Image</strong></td>
                                                    <td><strong>Description</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                <tr>
                                                    <td>{{ $purchase['user']['name'] }}</td>
                                                    <td>{{ $purchase['user']['email'] }}</td>
                                                    <td>{{ $purchase['user']['username'] }}</td>
                                                    <td><img src="{{ $purchase['user']['profile_image'] != null ? asset($purchase['user']['profile_image']) : url('upload/no_image.jpg') }}" style="width: 60px; height: 50px;"></td>
                                                    <td>{{ $purchase->description }}</td>
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
                                                    <td class="text-start"><strong>Product Name</strong></td>
                                                    <td class="text-center"><strong>Category</strong></td>
                                                    <td class="text-center"><strong>Supplier</strong></td>
                                                    <td class="text-center"><strong>Unit Price</strong></td>
                                                    <td class="text-center"><strong>Quantity</strong></td>
                                                    <td class="text-end"><strong>Total Price</strong></td>
                                                </tr>
                                                </thead>
                                                <tbody>
            
                                                <tr>
                                                    <td class="text-start">{{ $purchase['product']['name'] }}</td>
                                                    <td class="text-center">{{ $purchase['category']['name'] }}</td>
                                                    <td class="text-center">{{ $purchase['supplier']['name'] }}</td>
                                                    <td class="text-center">${{ $purchase->unit_price }}</td>
                                                    <td class="text-center">{{ $purchase->buying_qty }}</td>
                                                    <td class="text-end">${{ $purchase->buying_price }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line"></td>
                                                    <td class="no-line text-center">
                                                        <strong>Grand Purchased Amount</strong></td>
                                                    <td class="no-line text-end"><h4 class="m-0">${{ $purchase->buying_price }}</h4></td>
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