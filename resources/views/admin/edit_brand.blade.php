@extends('layouts.admin.app_pos')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Brand</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Update Brand</li>
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
                @foreach($brands as $brand)
                    
                    @php( $name = $brand->name)
                    @php( $description = $brand->description )
                    @php( $parent = $brand->parent_id )
                    @php( $status = $brand->status )
                    @php( $url = $brand->url )
                    @php( $image = $brand->image )
                                        
                @endforeach
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title">Create Brand</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/edit_brand/'.$id) }}" id="addBrand" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="inputName">Brand Name <span class="text-danger">*</span></label>
                                <input type="text" name="inputName" value="{{$name}}" id="inputName" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDesc">Brand Description</label>
                                <textarea id="inputDesc" name="inputDesc" class="form-control" rows="8">{{$description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputImage">Brand Image</label> <br>
                                <img src="/images/brands/{{$image}}" alt="" style="border-radius: 100%; width: 70px;">
                                <div class="custom-file">
                                    <input type="file" name="inputImage" class="custom-file-input" id="inputImage">
                                    <label class="custom-file-label" for="inputImage">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputStatus">Status</label>
                                <select id="inputStatus" name="inputStatus" class="form-control custom-select">
                                    <option selected value="{{$status}}"><?php 
                                    if($status == 0){
                                        echo 'Inactive';
                                    }elseif($status == 1){
                                        echo 'Active';
                                    }
                                    ?></option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputURL">Brand URL</label>
                                <input type="text" id="inputURL" name="inputURL" class="form-control" value="{{$url}}">
                            </div>
                            <div class="card-footer">
                                <a href="/admin/edit_brand" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Update Brand" class="btn btn-success float-right">
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
        //Initialize Select2 Elements
        $('.select2').select2()
    });
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    $(function () {
        // Summernote
        $('#inputDesc').summernote()
    })
</script>

@endsection