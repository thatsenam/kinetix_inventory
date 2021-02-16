@extends('layouts.admin.app_pos')
@section('title','Add Products')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
              <li class="breadcrumb-item active">Product Add</li>
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
      <form action="{{ url('/admin/add_product') }}" id="addProduct" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Product Information</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Additional Images</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="form-group">
                            <label for="inputBarcode">Barcode</label>
                            <input type="text" name="inputBarcode" id="inputBarcode" class="form-control" placeholder="Barcode here...">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Product Title<span class="text-danger">*</span></label>
                            <input type="text" name="inputName" id="inputName" class="form-control" placeholder="Enter Title...">
                        </div>
                        <div class="form-group">
                            <label for="inputDescription">Product Description</label>
                            <textarea name="inputDescription" id="inputDescription" class="form-control" rows="8"></textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="inputSpecs">Product Specification</label>
                            <textarea name="inputSpecs" id="inputSpecs" class="form-control" rows="8"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="inputFeature">Main Features</label>
                            <textarea name="inputFeature" id="inputFeature" class="form-control" rows="8"></textarea>
                        </div> --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Regular Price<span class="text-danger">*</span></label>
                                    <input type="text" name="inputPrice" class="form-control" placeholder="Enter ...">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputWarranty">Warranty</label>
                                    <div class="input-group">
                                        <input type="text" name="inputWarranty" id="inputWarranty" placeholder="Enter Warranty" class="form-control"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">Month</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Discounted Price</label>
                                    <input type="text" name="DiscountPrice" class="form-control" placeholder="Enter ...">
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputCategory">Select Category<span class="text-danger">*</span></label>
                                    <select id="inputCategory" name="inputCategory" class="form-control custom-select">
                                        <option selected disabled>Select One</option>
                                        <?php if($category != null){ 
                                            foreach($category as $cat){ ?>
                                            <option value="<?php echo $cat['id'] ?>"><?php echo $cat['name'] ?> </option>
                                        <?php } }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputBrand">Select Brand<span class="text-danger">*</span></label>
                                    <select id="inputBrand" name="inputBrand" class="form-control custom-select">
                                        <option selected disabled>Select One</option>
                                        <?php if($brands != null){ 
                                            foreach($brands as $brand){ ?>
                                            <option value="<?php echo $brand['id'] ?>"><?php echo $brand['name'] ?> </option>
                                        <?php } }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputSKU">SKU</label>
                                    <input type="text" name="inputSKU" id="inputSKU" placeholder="Enter SKU" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputSize">Product Weight</label>
                                    <input type="text" name="inputSize" id="inputSize" placeholder="Enter Weight" class="form-control"/>
                                </div>
                            </div>
                            
                            <div class="">
                                <div class="">
                                    {{-- <label for="inputStock">Stock</label> --}}
                                    <input type="hidden" name="inputStock" id="inputStock" placeholder="Enter Stock" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Product Code</label>
                                    <input type="text" name="inputCode" class="form-control" placeholder="Enter ...">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputImage">Product Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="inputImage" class="custom-file-input" id="inputImage">
                                        <label class="custom-file-label" for="inputImage">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check text-center">
                                <input name="serial" type="hidden" value="0">
                                <input name="serial" class="form-check-input" type="checkbox" value="1" id="SerialCheckbox">
                                <label class="form-check-label font-weight-bold" for="SerialCheckbox">
                                  SERIAL NUMBER ?
                                </label>
                            </div>
                        </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                        <div class="form-group field_wrapper">
                            <label for="addImage" class="">New Additional Image</label>
                            <div class="row">
                                <input type="file" name="addImage[]" id="addImage" class="form-control col-md-9 m-1" placeholder="Add New Additional Image" required>
                                <a href="javascript:void(0);" class="add_button col-md-2 m-auto" title="Add field">Add New</a>
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
                <input type="submit" value="Create new Product" class="btn btn-success float-right">
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
                inputName: {
                required: true
                },
                inputDescription: {
                    required: true
                },
                // inputSpecs: {
                //     required: true
                // },
                inputPrice: {
                    required: true
                },
                inputCategory: {
                    required: true
                },
                inputBrand: {
                    required: true
                },
                // inputImage: {
                //     required: true
                // },
                inputStatus: {
                    required: true
                },
            },
            messages: {
                inputName: {
                    required: "Product Title Field Can't Be Empty!",
                },
                inputDescription: {
                    required: "Product Description Field Can't Be Empty!",
                },
                // inputSpecs: {
                //     required: "Product Specification Field Can't Be Empty!",
                // },
                inputPrice: {
                    required: "Product Price Field Can't Be Empty!",
                },
                inputCategory: {
                    required: "Please a Select Category.",
                },
                inputBrand: {
                    required: "Please Select Brand.",
                },
                // inputImage: {
                //     required: "Please Select Product Image.",
                // },
                inputStatus: {
                    required: "Please Select Product Status.",
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
        $('#inputDescription').summernote(),
        $('#inputSpecs').summernote(),
        $('#inputFeature').summernote()
    });
</script>

@endsection