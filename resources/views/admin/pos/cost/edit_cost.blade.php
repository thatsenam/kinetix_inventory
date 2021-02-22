@extends('layouts.admin.app_pos')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ব্যয় আপডেট</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">হোম</a></li>
              <li class="breadcrumb-item active">ব্যয় আপডেট</li>
            </ol>
          </div>
        </div>
        @if ($success = Session::get('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $success }}</strong>
            </div>
        @endif
        
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
<section class="content pb-5">
    <form action="{{ url('/admin/edit_cost/'.$id) }}" id="editProduct" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">ব্যয় আপডেট করুন</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                    <div class="form-group">
                            <label for="category">ক্যাটাগরি<span class="text-danger">*</span></label>
                            <select id="category" name="category" class="form-control custom-select">
                                <?php if($categories != null){ 
                                    foreach($categories as $cat){ ?>
                                    <option value="<?php echo $cat['id'] ?>" {{ $cost->category->id == $cat['id'] ? "selected" : ""}}><?php echo $cat['name'] ?> </option>
                                <?php } }?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sub_category">সাব ক্যাটাগরি<span class="text-danger">*</span></label>
                            <input type="text" name="sub_category" id="sub_category" class="form-control" value="{{$cost->sub_category}}">
                        </div>
                        <div class="form-group">
                            <label for="reason">ব্যয়ের কারন<span class="text-danger">*</span></label>
                            <input type="text" name="reason" id="reason" class="form-control" value="{{$cost->reason}}">
                        </div>
                        <div class="form-group">
                            <label for="amount">ব্যয়ের পরিমান</label>
                            <input type="text" name="amount" class="form-control" id="amount" value="{{$cost->amount}}">

                        </div>
                        <div class="form-group">
                            <label for="details">বিস্তারিত</label>
                            <textarea name="details" id="details" class="form-control" rows="8" value="{{$cost->details}}"></textarea>

                        </div> 
                        <div class="form-group">
                            <label for="date">তারিখ</label>
                            <input type="date" name="date" class="form-control" value="{{$cost->date}}" id="date">
                        </div> 
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12">
                <a href="" class="btn btn-secondary">বাদ</a>
                <input type="submit" value="ব্যয় আপডেট করুন" class="btn btn-success float-right">
            </div>
    </form>
</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <script>
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
        $('#inputDescription').summernote()
    });
    $(function () {
        // Summernote
        $('#inputSpecs').summernote()
    });
</script>

@endsection