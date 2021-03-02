@extends('layouts.admin.app_pos')
@section('title','Add Bank')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Bank</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
              <li class="breadcrumb-item active">Add New Bank</li>
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
      <form action="{{url('/dashboard/add_bank')}}" id="addProduct" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">New Bank Info</a>
                  </li>
                  
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade active show add_card" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="form-group">
                            <label for="category">Bank Type<span class="text-danger">*</span></label>
                            <select id="category" name="category" class="form-control custom-select">
                                <option value="" selected disabled>Select Bank Type</option>
                                <option value="general_bank">General Bank</option>
                                <option value="mobile_bank">Mobile Bank</option> 
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="bank_name">Bank Name<span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Bank Name ...">
                        </div> 
                        <div class="form-group" id="bank_address_div">
                            <label for="bank_address">Bank Address<span class="text-danger">*</span></label>
                            <input type="text" name="bank_address" id="bank_address" class="form-control" placeholder="Bank Address ...">
                        </div> 
                        <div class="form-group" id="acc_name_div">
                            <label for="acc_name">Bank Account Name<span class="text-danger">*</span></label>
                            <input type="text" name="acc_name" id="acc_name" class="form-control" placeholder="Bank Account Name...">
                        </div>
                        <div class="form-group">
                            <label for="acc_no">Bank Account No.<span class="text-danger">*</span></label>
                            <input type="text" name="acc_no" id="acc_no" class="form-control" placeholder="Bank Account No....">
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="button" class="btn btn-primary" onclick="addCardFunc()" id="addCard">+ Add Card</button>
                            </div>
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
                <a href="#" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Add New Bank" class="btn btn-success float-right">
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
                bank_name: {
                required: true
                },
                bank_address: {
                required: true
                },
                acc_name: {
                    required: true
                },
                acc_no: {
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
                bank_name: {
                    required: "This Field is Required",
                },
                bank_address: {
                    required: "This Field is Required",
                },
                acc_no: {
                    required: "This Field is Required",
                },
                acc_name: {
                    required: "This Field is Required",
                },
                DiscountPrice: {
                    required: "This Field is Required",
                },
                inputPrice: {
                    required: "This Field is Required",
                },
                category: {
                    required: "This Field is Required",
                },
                sub_unit: {
                    required: "This Field is Required",
                },
                // inputBrand: {
                //     required: "Please Select Brand.",
                // },
                // description: {
                //     required: "Please Select Product Image.",
                // },
                inputStatus: {
                    required: "This Field is Required",
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
    $("#category").on('change',function(){
        if($('#category').val()=="mobile_bank"){
            $("#addCard").attr('disabled',true);
            $(".cards_name").remove();
            $("#bank_address_div").hide();
            $("#bank_address").val("");
            $("#acc_name_div").hide();
            $("#acc_name").val("");

        }else{
            $("#addCard").removeAttr('disabled');
            $("#bank_address_div").show();
            $("#acc_name_div").show();
        }
    });

    function addCardFunc(){
        $(".add_card").append(
            '<div class="form-group cards_name">'+
                            '<label for="card_name">Card Name<span class="text-danger">*</span></label>'+
                            '<input type="text" name="card_name[]" id="card_name" class="form-control" placeholder="Card Name...">'+
                        '</div>'
        );
    }
</script>
@endsection