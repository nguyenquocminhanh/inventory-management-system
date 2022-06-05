@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="row" data-masonry="{&quot;percentPosition&quot;: true }" style="position: relative; height: 708.375px;">
                    <div class="col-sm-6 col-lg-6" style="position: absolute; left: 0%; top: 0px;">
                        <div class="card">
                            <br></br>
                            <center>
                                <img class="rounded-circle avatar-xl" src="{{ (!empty($adminData->profile_image)) ? url($adminData->profile_image) : url('upload/no_image.jpg') }}" class="card-img-top" alt="...">
                            </center>
                            <div class="card-body">
                                <h5 class="card-title">Name: {{ $adminData->name }}</h5>
                                <hr>
                                <h5 class="card-title">User Email: {{ $adminData->email }}</h5>
                                <hr>
                                <h5 class="card-title">User Name: {{ $adminData->username }}</h5>
                                <hr>
                                <center>
                                    <a href="{{ route('edit.profile') }}" class="btn btn-info btn-rounded waves-effect waves-light">Edit Profile</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>

    </div>
</div>




@endsection