@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Invoice Page (Due Amount)</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                            <li class="breadcrumb-item active">Customer Invoice Page (Due Amount)</li>
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

                        <a href="{{ route('credit.customer') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float: right;"><i class="fas fa-list"> Back</i></a>

                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <div class="p-2">
                                        <h3 class="font-size-16"><strong>Customer Invoice: (Invoice No: #{{ $payment['invoice']['invoice_number'] }})</strong></h3>
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

                        <form method="POST" action="{{ route('customer.update.invoice', $payment->invoice_id) }}">
                                @csrf
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
                                                        <td class="text-center"><strong>Unit Price</strong></td>
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
                                                        <td class="no-line text-center" style="background-color: #ddd"><strong>Due Amount</strong></td>
                                                        <!-- hidden field -->
                                                        <input type="hidden" name="new_paid_amount" value="{{ $payment->due_amount }}">
                                                        <td class="no-line text-end" style="background-color: #ddd"><h4 class="m-0">$<span id="old_due_amount">{{ $payment->due_amount }}</span></h4></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>Paid Status</label>
                                    <select name="paid_status" id="paid_status" class="form-select">
                                        <option value="">Select Paid Status</option>
                                        <option value="full_paid">Full Paid</option>
                                        <option value="partial_paid">Partial Paid</option>
                                        <option value="full_due">Full Due</option>
                                    </select>

                                    <input type="text" name="paid_amount" id="paid_amount" class="form-control paid_amount mt-3" placeholder="Enter Paid Amount" style="display: none;">

                                    <input class="form-control due_amount mt-3" type="text" name="due_amount" id="due_amount" readonly style="background-color: #ddd; color: #f54242; display: none;">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="example-text-input" class="form-label">Date</label>
                                    <input class="form-control" type="date" name="date" id="date" paceholder="YYYY-MM-DD">
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="md-3" style="padding-top: 28px;">
                                        <button type="submit" class="btn btn-info">Invoice Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

<!-- select partial paid -> enter paid amount -->
<script type="text/javascript">
    $(document).on("change", "#paid_status", function(){
        var paid_status = $(this).val();
        if (paid_status == 'partial_paid') {
            $(".paid_amount").show();
        } else {
            $(".paid_amount").hide();
            $(".due_amount").hide();
        }
    })
</script>

<!-- change paid amount -> appear due amount -->
<script type="text/javascript">
    $(document).on("keyup", "#paid_amount", function(){
        $(".due_amount").show();

        var due_amount = parseFloat($('#old_due_amount').text());
        var paid_amount = parseFloat($(this).val());
        var new_due_amount = due_amount - paid_amount;

        $('#due_amount').val('Estimated Due Amount: $' + new_due_amount);
    })
</script>

@endsection