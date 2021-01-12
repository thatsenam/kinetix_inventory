@extends('layouts.admin.app_pos')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
              <li class="breadcrumb-item active">Add New Category</li>
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
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title">Create Category</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/create_category') }}" id="addCaategory" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="inputName">Category Name</label>
                                <input type="text" name="cat_name" id="inputName" class="form-control" placeholder="Type Category Name Here...">
                            </div>
                            <div class="form-group">
                                <label for="cat_desc">Category Description</label>
                                <textarea id="cat_desc" name="cat_desc" class="form-control" rows="8" placeholder="Type Category Description Here..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="parent_cat">Parent Category</label>
                                <select id="parent_cat" name="parent_cat" class="form-control">
                                    <option value="0" selected>Select Parent Category</option>
                                    <?php if($category != null){ 
                                        foreach($category as $cat){ ?>
                                        @if($cat->parent_id != 0)
                                        <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?> </option>
                                        @else
                                        <option value="<?php echo $cat['id'] ?>" style="background-color:#007bff; color:white;"><?php echo $cat['name'] ?> </option>
                                        @endif
                                    <?php } }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputImage">Category Image</label>
                                <div class="custom-file">
                                    <input type="file" name="inputImage" class="custom-file-input" id="inputImage">
                                    <label class="custom-file-label" for="inputImage">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputType">Category Type <span class="small">(If fetaured category block will be diplayed on homepage)</span></label>
                                <select id="inputType" name="cat_type" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    <option value="1">Featured Category</option>
                                    <option value="0">Normal Category</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputStatus">Status</label>
                                <select id="inputStatus" name="cat_status" class="form-control custom-select">
                                    <option selected disabled>Select one</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cat_url">Category URL</label>
                                <input type="text" id="cat_url" name="cat_url" class="form-control" placeholder="Category URL Here...">
                            </div>
                            <div class="card-footer">
                                <a href="/admin/create_category" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Create new Category" class="btn btn-success float-right">
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
        $('#addCaategory').validate({
            rules: {
                cat_name: {
                required: true
                },
                cat_desc: {
                    required: true
                },
                cat_status: {
                    required: true
                },
            },
            messages: {
                cat_name: {
                    required: "Category Field Can't Be Empty!",
                },
                cat_desc: {
                    required: "Category Description Field Can't Be Empty!",
                },
                cat_status: {
                    required: "Please Select Category Status.",
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
    $(function () {
        // Summernote
        $('#cat_desc').summernote()
    })
</script>

@endsection