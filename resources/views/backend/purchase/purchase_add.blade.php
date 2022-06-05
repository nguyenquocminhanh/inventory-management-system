@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Add Purchase Page</h4>
                        <br></br>
                       
                        <div class="row">
                            <div class="col-md-4 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Date</label>
                                    <input class="form-control" type="date" name="date" id="date">
                                </div>
                            </div>

                            <div class="col-md-4 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Purchase Number</label>
                                    <input class="form-control" type="text" name="purchase_number" id="purchase_number">
                                </div>
                            </div>

                            <div class="col-md-4 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Supplier Name</label>
                                    <select id="supplier_id" name="supplier_id" class="form-select select2" aria-label="Default select example">
                                        <option selected="">Select Supplier</option>
                                        @foreach($suppliers as $supp)
                                            <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Category Name</label>
                                    <select id="category_id" name="category_id" class="form-select select2" aria-label="Default select example">
                                        <option selected="">Select Category</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 mt-2">
                                <div class="md-3">
                                    <label for="example-text-input" class="form-label">Product Name</label>
                                    <select id="product_id" name="product_id" class="form-select select2" aria-label="Default select example">
                                        <option selected="">Select Product</option>

                                    </select>
                                </div>
                            </div>

                            

                            <div class="col-md-4 mt-2">
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
                            <form method="post" action="{{ route('purchase.store') }}">
                                @csrf
                                <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Product Name </th>
                                            <th>PSC/KG</th>
                                            <th>Unit Price </th>
                                            <th>Description</th>
                                            <th>Total Price</th>
                                            <th>Action</th> 

                                        </tr>
                                    </thead>

                                    <tbody id="addRow" class="addRow">

                                    </tbody>

                                    <tbody>
                                        <tr>
                                            <td colspan="5">Due Total</td>
                                            <td>
                                                <input type="text" name="estimated_amount" value="0" id="estimated_amount" class="form-control estimated_amount" readonly style="background-color: #ddd;" >
                                            </td>
                                            <td></td>
                                        </tr>

                                    </tbody>                
                                </table>
                                
                                <br>

                                <center>
                                    <button type="submit" class="btn btn-info" id="storeButton"> Purchase Store</button>
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
        <input type="hidden" name="date[]" value="@{{date}}">
        <input type="hidden" name="purchase_number[]" value="@{{purchase_number}}">
        <input type="hidden" name="supplier_id[]" value="@{{supplier_id}}">

        <td>
            <input type="hidden" name="category_id[]" value="@{{category_id}}">
            @{{ category_name }}
        </td>

        <td>
            <input type="hidden" name="product_id[]" value="@{{product_id}}">
            @{{ product_name }}
        </td>

        <td>
            <input type="number" min="1" class="form-control buying_qty text-right" name="buying_qty[]" value="">
        </td>

        <td>
            <input type="number" class="form-control unit_price text-right" name="unit_price[]" value="">
        </td>

        <td>
            <input type="text" class="form-control" name="description[]">
        </td>

        <td>
            <input type="number" class="form-control buying_price text-right" name="buying_price[]" value="0" readonly>
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
            var purchase_number = $('#purchase_number').val();
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
            if(purchase_number == '') {
                $.notify("Purchase Number is required", {globalPosition: 'top right', className: 'error'});
                return false;
            }
            if(supplier_id == '') {
                $.notify("Supplier Field is required", {globalPosition: 'top right', className: 'error'});
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
                purchase_number: purchase_number,
                supplier_id: supplier_id,
                category_id: category_id,
                category_name: category_name,
                product_id: product_id,
                product_name: product_name,
            }
            // data pass va2o @
            var html = template(data);
            $('#addRow').append(html);
        });

        // remove item
        $(document).on("click", ".removeeventmore", function(event){
            $(this).closest(".delete_add_more_item").remove();

            totalAmountPrice();
        })

        // calculate total price for one unit
        $(document).on('keyup click', '.unit_price, .buying_qty', function(){
            var unit_price = $(this).closest("tr").find("input.unit_price").val();
            var buying_qty = $(this).closest("tr").find("input.buying_qty").val();
            var totalPrice = unit_price * buying_qty;
            $(this).closest("tr").find("input.buying_price").val(totalPrice);

            totalAmountPrice();
        })

        // calculate total sum price
        function totalAmountPrice() {
            var sum = 0;
            $(".buying_price").each(function(){
                var value = $(this).val();
                if(!isNaN(value) && value.length !=0) {
                    sum += parseFloat(value);
                }
            });
            // display total price
            $('#estimated_amount').val(sum);
        }
    })
</script>

<!-- select supplier -> show categories tuong ung -->
<script type="text/javascript">
    $(function(){
        $(document).on('change', '#supplier_id', function() {
            var supplier_id = $(this).val();
            $.ajax({
                url: "{{ route('get-category') }}",
                type: "GET",
                data: {supplier_id: supplier_id},
                // data tra ve tu route
                // data tra ve tu table Product
                success: function(data){
                    var html = '<option value="">Select Category</option>';
                    $.each(data, function(key, v){
                        html += '<option value=" '+v.category_id+' "> '+v.category.name+' </option>';
                    });
                    $('#category_id').html(html);
                }
            });

            // ko xuat ra product nao
            $('#product_id').html('<option value="">Select Product</option>');
        });
    });
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

@endsection