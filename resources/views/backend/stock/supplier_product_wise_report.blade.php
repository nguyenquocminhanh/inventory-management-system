@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Supplier and Product Wise Report</h4>
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
                                <strong>Supplier Wise Report</strong>
                                <input type="radio" name="supplier_product_wise" value="supplier_wise" class="search_value">
                                &nbsp;
                                <strong>Product Wise Report</strong>
                                <input type="radio" name="supplier_product_wise" value="product_wise" class="search_value">
                            </div>
                        </div>      

                        <br>

                        <!-- Supplier Wise -->
                    
                        <div class="show_supplier" style="display: none">
                            <form method="GET" action="{{ route('supplier.wise.pdf') }}" id="myForm" target="_blank">
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>Supplier Name</label>
                                        
                                        <select id="supplier_id" name="supplier_id" class="form-select select2 w-100" aria-label="Default select example">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supp)
                                            <option value="{{ $supp->id }}">{{ $supp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4" style="padding-top: 28px;">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Product Wise -->

                        <div class="show_product" style="display: none">
                            <form method="GET" action="{{ route('product.wise.pdf') }}" id="myForm" target="_blank">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="form-label">Category Name</label>
                                            <select id="category_id" name="category_id" class="form-select select2" aria-label="Default select example">
                                                <option selected="">Select Category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="example-text-input" class="form-label">Product Name</label>
                                            <select id="product_id" name="product_id" class="form-select select2" aria-label="Default select example">
                                                <option selected="">Select Product</option>

                                            </select>
                                        </div>
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
                supplier_id: {
                    required : true,
                }, 
            },
            messages :{
                supplier_id: {
                    required : 'Please Select One Supplier',
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
        if (search_value == 'supplier_wise') {
            $(".show_supplier").show();
            $(".show_product").hide();
        } else {
            $(".show_supplier").hide();
            $(".show_product").show();
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

@endsection