@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Add Invoice Page</h4>
                        <br></br>
                        
                        <div class="row">

                            <div class="col-md-1 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Invoice No</label>
                                    <input class="form-control" type="text" value="{{ $invoice_number }}" name="invoice_number" id="invoice_number" readonly style="background-color: #ddd">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Date</label>
                                    <input class="form-control" type="date" name="date" id="date" value="{{ $date }}">
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Category Name</label>
                                    <select id="category_id" name="category_id" class="form-select select2" aria-label="Default select example">
                                        <option value="" selected="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Product Name</label>
                                    <select id="product_id" name="product_id" class="form-select select2" aria-label="Default select example">
                                        <option value="" selected="">Select Product</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Stock(PCG/KG)</label>
                                    <input class="form-control" type="text" name="current_stock_qty" id="current_stock_qty" readonly style="background-color: #ddd">
                                </div>
                            </div>

                            <div class="col-md-2 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label" style="margin-top: 43px;"></label>
                                    <!-- <input type="submit" class="btn btn-secondary btn-rounded waves-effect waves-light" value="Add More"> -->
                                    <i class="btn btn-secondary btn-rounded waves-effect waves-light fas fa-plus-circle addeventmore"> Add More</i>
                                </div>
                            </div>
                        </div>
                        
                        <br>
                        <br>
                        <br>

                        <!-- ======================================================== -->

                        <div class="card-body px-0">
                            <form method="post" action="{{ route('invoice.store') }}">
                                @csrf
                                <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Product Name </th>
                                            <th width="7%">PSC/KG</th>
                                            <th width="10%">Unit Price </th>
                                            <th width="15%">Total Price</th>
                                            <th width="7%">Action</th> 

                                        </tr>
                                    </thead>

                                    <tbody id="addRow" class="addRow">

                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <td colspan="4">Discount</td>

                                            <td>
                                                <input type="text" name="discount_amount" value="0" id="discount_amount" class="form-control estimated_amount" placeholder="Discount Amount" >
                                            </td>
                                        </tr>

                                        <tr>
                                            <!-- chiem 4 cot -->
                                            <td colspan="4">Grand Total</td>
                                            <td>
                                                <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;" >
                                            </td>
                                            <td></td>
                                        </tr>

                                    </tbody>                
                                </table>
                                
                                <br>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <textarea name="description" class="form-control" id="description" placeholder="Write Description Here"></textarea>
                                    </div>
                                </div>

                                <br>

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

                                    <div class="form-group col-md-9">
                                        <label>Customer Name</label>
                                        <select name="customer_id" id="customer_id" class="form-select">
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone_number }}</option>
                                            @endforeach
                                            <option value="0">New Customer</option>
                                        </select>
                                        

                                        <!-- Hide Add Customer Form -->
                                        <div class="row new_customer mt-3" style="display: none;">
                                            <div class="form-group col-md-3">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Customer Name">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Customer Phone Number">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="text" name="email" id="email" class="form-control" placeholder="Customer Email">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="text" name="address" id="address" class="form-control" placeholder="Customer Address">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                                <br>
                        
                                <center>
                                    <button type="submit" class="btn btn-info" id="storeButton">Store Invoice</button>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>

<!-- template -->
<script id="document-template" type="text/x-handlebars-template">
    <tr class="delete_add_more_item" id="delete_add_more_item">
        <!-- hidden de store vao database -->
        <input type="hidden" name="date" value="@{{date}}">
        <input type="hidden" name="invoice_number" value="@{{invoice_number}}">

        <td>
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
            @{{ category_name }}
        </td>

        <td>
            <input type="hidden" name="product_id[]" value="@{{product_id}}">
            @{{ product_name }}
        </td>

        <td>
            <input type="number" min="1" class="form-control selling_qty text-right" name="selling_qty[]" value="">
        </td>

        <td>
            <input type="number" class="form-control unit_price text-right" name="unit_price[]" value="">
        </td>

        <td>
            <input type="number" class="form-control selling_price text-right" name="selling_price[]" value="0" readonly>
        </td>

        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>
    </tr>    
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("click", ".addeventmore", function(){
            var date = $('#date').val();
            var invoice_number = $('#invoice_number').val();
            var supplier_id = $('#supplier_id').val();
            var category_id = $('#category_id').val();
            var category_name = $('#category_id').find('option:selected').text();
            var product_id = $('#product_id').val();
            var product_name = $('#product_id').find('option:selected').text();

            // validate
            if(date == '') {
                $.notify("Date is required", {globalPosition: 'top right', className: 'error'});
                return false;
            } 
            if(category_id == '') {
                $.notify("Category Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }
            if(product_id == '') {
                $.notify("Product Field is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }

            var source = $("#document-template").html();
            var template = Handlebars.compile(source);
            var data = {
                date: date,
                invoice_number: invoice_number,
                category_id: category_id,
                category_name: category_name,
                product_id: product_id,
                product_name: product_name,
            }
            // data pass vao @
            var html = template(data);
            $('#addRow').append(html);
        });

        // remove item
        $(document).on("click", ".removeeventmore", function(event){
            $(this).closest(".delete_add_more_item").remove();

            totalAmountPrice();
        })

        // calculate total price for one unit
        $(document).on('keyup click', '.unit_price, .selling_qty', function(){
            var unit_price = $(this).closest("tr").find("input.unit_price").val();
            var selling_qty = $(this).closest("tr").find("input.selling_qty").val();
            var totalPrice = unit_price * selling_qty;
            $(this).closest("tr").find("input.selling_price").val(totalPrice);
            
            // DISCOUNT
            $('#discount_amount').trigger('keyup');
        })

        $(document).on('keyup', '#discount_amount', function(){
            totalAmountPrice();
        })

        // calculate total sum price
        function totalAmountPrice() {
            var sum = 0;
            $(".selling_price").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length !=0) {
                    sum += parseFloat(value);
                }
            });

            // DISCOUNT
            var discount_amount = parseFloat($('#discount_amount').val());
            if(!isNaN(discount_amount) && discount_amount.length !=0) {
                sum -= parseFloat(discount_amount);
            }

            // display total price
            $('#estimated_amount').val(sum);
        }
    })
</script>

<!-- select category -> show products tuong ung -->
<script type="text/javascript">
    $(function(){
        $(document).on('change', '#category_id', function() {
            var category_id = $(this).val();
            $.ajax({
                url: "{{ route('get-product') }}",
                type: "GET",
                // value truyen vao route
                data: {category_id: category_id},
                // data tra ve tu route
                // data tra ve tu table Product
                success: function(data){
                    var html = '<option value="">Select Product</option>';
                    $.each(data, function(key, v){
                        html += '<option value=" '+v.id+' "> '+v.name+' </option>';
                    });
                    $('#product_id').html(html);
                }
            })
        });
    });
</script>

<!-- select product -> show stock tuong ung -->
<script type="text/javascript">
    $(function(){
        $(document).on('change', '#product_id', function() {
            var product_id = $(this).val();
            $.ajax({
                url: "{{ route('check-product-stock') }}",
                type: "GET",
                // value truyen vao route
                data: {product_id: product_id},
                // data tra ve tu route
                // data tra ve tu table Product
                success: function(data){
                    $('#current_stock_qty').val(data);
                }
            })
        });
    });
</script>

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

        var total_amount = parseFloat($('#estimated_amount').val());
        var paid_amount = parseFloat($(this).val());
        var due_amount = total_amount - paid_amount;

        $('#due_amount').val('Due Amount: $' + due_amount);
    })
</script>

<!-- select customer id new customer -> enter new customer info -->
<script type="text/javascript">
    $(document).on("change", "#customer_id", function(){
        var customer_id = $(this).val();
        if (customer_id == '0') {
            $(".new_customer").show();
        } else {
            $(".new_customer").hide();
        }
    })
</script>

@endsection