@extends('layouts.admin.app_pos')
@section('title','ব্যয় যুক্ত করুন')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>নতুন ব্যয় যুক্ত করুন</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">হোম</a></li>
              <li class="breadcrumb-item active">ব্যয় যুক্ত করুন</li>
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

<section class="content">
      <div class="container-fluid">
      <form action="{{ url('/admin/create_cost') }}" id="addProduct" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">পণ্যের তথ্য</a>
                  </li>
                  
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="form-group">
                            <label for="category">ক্যাটাগরি<span class="text-danger">*</span></label>
                            <select id="category" name="category" class="form-control custom-select">
                                <option selected disabled>নির্বাচন করুন</option>
                                <?php if($categories != null){ 
                                    foreach($categories as $cat){ ?>
                                    <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?> </option>
                                <?php } }?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sub_category">সাব ক্যাটাগরি<span class="text-danger">*</span></label>
                            <input type="text" name="sub_category" id="sub_category" class="form-control" placeholder="সাব ক্যাটাগরি ...">
                        </div>
                        <div class="form-group">
                            <label for="reason">ব্যয়ের কারন<span class="text-danger">*</span></label>
                            <input type="text" name="reason" id="reason" class="form-control" placeholder="ব্যয়ের কারন...">
                        </div>
                        <div class="form-group">
                            <label for="amount">ব্যয়ের পরিমান</label>
                            <input type="text" name="amount" class="form-control" id="amount" placeholder="ব্যয়ের পরিমান...">

                        </div>
                        <div class="form-group">
                            <label for="details">বিস্তারিত</label>
                            <textarea name="details" id="details" class="form-control" rows="8"></textarea>

                        </div> 
                        <div class="form-group">
                            <label for="date">তারিখ</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date("Y-m-d");?>" id="date">
                        </div> 
                  </div>
                  
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
        <div class="row pb-5">
            <div class="col-12">
                <a href="#" class="btn btn-secondary">বাদ</a>
                <input type="submit" value="নতুন ব্যয় যুক্ত করুন" class="btn btn-success float-right">
            </div>
        </div>
        </form>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <script>
    $(document).ready(function(){
        var maxField = 10;
        var addButton = $('.add_button');
        var wrapper = $('.field_wrapper');
        var fieldHTML = '<div class="row"><input type="file" name="addImage[]" id="sku" class="form-control col-md-9 m-1" placeholder="Add New Additional Image" required><a href="javascript:void(0);" class="remove_button col-md-2 m-auto text-danger" title="Remove Field">Remove</a></div>';
        var x = 1;
        
        $(addButton).click(function(){
            if(x < maxField){ 
                x++;
                $(wrapper).append(fieldHTML);
            }
        });
        
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });
    });
    $(function () {
        $('#addProduct').validate({
            rules: {
                sub_category: {
                required: true
                },
                reason: {
                required: true
                },
                amount: {
                    required: true
                },
                per_box_qty: {
                    required: true
                },
                DiscountPrice: {
                    required: true
                },

                // inputSpecs: {
                //     required: true
                // },
                inputPrice: {
                    required: true
                },
                category: {
                    required: true
                },
                sub_unit: {
                    required: true
                },
                // inputBrand: {
                //     required: 
                // },
                // description: {
                //     required: true
                // },
                inputStatus: {
                    required: true
                },
            },
            messages: {
                sub_category: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                per_box_qty: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                reason: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                amount: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                DiscountPrice: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                inputPrice: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                category: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                sub_unit: {
                    required: "এই ফিল্ডটিতে মান দিন!",
                },
                // inputBrand: {
                //     required: "Please Select Brand.",
                // },
                // description: {
                //     required: "Please Select Product Image.",
                // },
                inputStatus: {
                    required: "এই ফিল্ডটিতে মান দিন!",
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
        $('#details').summernote(),
        $('#inputSpecs').summernote(),
        $('#inputFeature').summernote()
    });
</script>

@endsection