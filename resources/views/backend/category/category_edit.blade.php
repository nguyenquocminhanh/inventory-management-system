@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Edit Category Page</h4>
                        <br></br>
                       
                        <form id="myForm" method="POST" action="{{ route('category.update') }}">
                            @csrf

                             <!-- hidden input -->
                             <input type="hidden" name="id" value="{{ $category->id }}">

                            <div class="row mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Category Name</label>
                                <div class="form-group col-sm-10">
                                    <input class="form-control" type="text" name="name" value="{{ $category->name }}">
                                </div>
                            </div>

                            <center>
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Update Category">
                            </center>
                        </form>
                        
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                }, 
            },
            messages :{
                name: {
                    required : 'Please Enter Category Name',
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

@endsection