@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Print Purchase All</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <a href="{{ route('purchase.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-plus-circle"> Add Purchase</i></a>

                    <br></br>

                    <h4 class="card-title">Purchase Data</h4>
                    
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Purchase Number</th>
                                    <th>Date</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($purchases as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>#{{ $item->purchase_number }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->date )) }}</td>
                                    <td>{{ $item['product']['name'] }}</td>
                                    <td>${{ $item->unit_price }}</td>
                                    <td>{{ $item->buying_qty }}</td>
                                    <td>${{ $item->buying_price }}</td>
                                    <td><a href="{{ route('purchase.print', $item->id) }}" target="_blank" class="btn btn-info sm" title="Print Invoice" id=""><i class="fas fa-print"></i></a></td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>



@endsection