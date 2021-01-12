@extends('layouts.admin.app')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Banner</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Update Banner</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                @if ($success = Session::get('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $success }}</strong>
                </div>
                @endif

                @foreach($banners as $banner)
                    
                    @php( $name = $banner->title)
                    @php( $type = $banner->type )
                    @php( $status = $banner->status )
                    @php( $url = $banner->link )
                    @php( $image = $banner->image )
                                        
                @endforeach
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title">Update Banner</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/edit_banner/'.$id) }}" id="addBrand" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="inputName">Banner Title</label>
                                <input type="text" name="inputName" id="inputName" class="form-control" value="{{$name}}">
                            </div>
                            <div class="form-group">
                                <img src="/images/banners/{{$image}}" alt="" width="100px"> <br>
                                <label for="inputImage">Banner Image</label>
                                <div class="custom-file">
                                    <input type="file" name="inputImage" class="custom-file-input" id="inputImage">
                                    <label class="custom-file-label" for="inputImage">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputStatus">Status</label>
                                <select id="inputStatus" name="inputStatus" class="select2 form-control custom-select">
                                    <option selected>{{$status}}</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputType">Type</label>
                                <select id="inputType" name="inputType" class="select2 form-control custom-select">
                                    <option selected>{{$type}}</option>
                                    <option value="homeslider">Home Slider</option>
                                    <option value="banners">Site Banner</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputURL">Banner URL</label>
                                <input type="text" id="inputURL" name="inputURL" class="form-control" value="{{$url}}">
                            </div>
                            <div class="card-footer">
                                <a href="/admin/view_banners" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Update Banner" class="btn btn-success float-right">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
    $(function () {
        $('#addBrand').validate({
            rules: {
                inputName: {
                required: true
                },
                inputStatus: {
                    required: true
                },
            },
            messages: {
                inputName: {
                    required: "Banner Title Field Can't Be Empty!",
                },
                inputStatus: {
                    required: "Banner status field can't be empty!",
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            }
        });
    });
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    });
</script>

@endsection