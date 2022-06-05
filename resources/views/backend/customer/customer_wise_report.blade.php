@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Customer Wise Report</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <br>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <strong>Customer Credit Report</strong>
                                <input type="radio" name="customer_wise" value="customer_wise_credit" class="search_value">
                                &nbsp;
                                <strong>Customer Paid Report</strong>
                                <input type="radio" name="customer_wise" value="customer_wise_paid" class="search_value">
                            </div>
                        </div>      

                        <br>

                        <!-- Customer Credit Wise -->
                    
                        <div class="show_credit" style="display: none">
                            <form method="GET" action="{{ route('customer.wise.credit.pdf') }}" id="myForm" target="_blank">
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Customer Name</label>

                                        @php 
                                            $credit_customers = App\Models\Customer::whereHas('payments', function($payments) {
                                                $payments->whereIn('paid_status', ['partial_paid', 'full_due']);
                                            })->get(); 
                                        @endphp
                                        
                                        <select id="customer_credit" name="customer_id" class="form-select select2" aria-label="Default select example">
                                            <option value="">Select Customer</option>
                                            @foreach($credit_customers as $cus)
                                            <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4" style="padding-top: 28px;">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Customer Paid Wise -->

                        <div class="show_paid" style="display: none">
                            <form method="GET" action="{{ route('customer.wise.paid.pdf') }}" id="myForm" target="_blank">
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Customer Name</label>

                                        @php 
                                            $paid_customers = App\Models\Customer::whereHas('payments', function($payments) {
                                                $payments->where('paid_status', '!=', 'full_due');
                                            })->get();
                                            
                                        @endphp
                                        
                                        <select id="customer_paid" name="customer_id" class="form-select select2" aria-label="Default select example">
                                            <option value="">Select Customer</option>
                                            @foreach($paid_customers as $cus)
                                            <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4" style="padding-top: 28px;">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
                
    </div> <!-- container-fluid -->
</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                customer_id: {
                    required : true,
                }, 
            },
            messages :{
                customer_id: {
                    required : 'Please Select One Customer',
                },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

<!-- select radio -> appear form -->
<script type="text/javascript">
    $(document).on("change", ".search_value", function(){
        var search_value = $(this).val();
        if (search_value == 'customer_wise_credit') {
            $(".show_credit").show();
            $(".show_paid").hide();
        } else {
            $(".show_credit").hide();
            $(".show_paid").show();
        }
    })
</script>

@endsection