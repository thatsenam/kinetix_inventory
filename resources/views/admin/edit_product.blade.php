@extends('layouts.admin.app_pos')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Product Edit</li>
            </ol>
          </div>
        </div>
        @if ($success = Session::get('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $success }}</strong>
            </div>
        @endif
        @foreach($products as $product)

            @php( $cat_id = $product->cat_id )
            @php( $brand_id = $product->brand_id )
            @php( $name = $product->product_name )
            @php( $desc = $product->product_desc)
            {{-- @php( $specs = $product->product_specs ) --}}
            @php( $price = $product->before_price )
            {{-- @php( $dprice = $product->after_pprice ) --}}
            @php( $code = $product->product_code )
            @php( $sku = $product->sku )
            @php( $stock = $product->stock )
            @php( $weight = $product->product_size )
            {{-- @php( $featured = $product->is_featured ) --}}
            @php( $image = $product->product_img )
            @php( $barcode = $product->barcode )
            @php( $serial = $product->serial )
            @php( $warranty = $product->warranty )
            @php( $reorder = $product->reorder )

        @endforeach
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
<section class="content pb-5">
    <form action="{{ url('/admin/edit_product/'.$id) }}" id="editProduct" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">General</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputBarcode">Barcode</label>
                            <input type="text" name="inputBarcode" id="inputBarcode" class="form-control" value="{{$barcode}}">
                        </div>
                        <div class="form-group">
                            <label for="inputName">Product Title</label>
                            <input type="text" name="inputName" id="inputName" class="form-control" value="{{$name}}">
                        </div>
                        <div class="form-group">
                            <label for="inputDescription">Product Description</label>
                            <textarea name="inputDescription" id="inputDescription" class="form-control" rows="8">{{$desc}}</textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="inputSpecs">Product Specification</label>
                            <textarea name="inputSpecs" id="inputSpecs" class="form-control" rows="8">{{$specs}}</textarea>
                        </div> --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Regular Price</label>
                                    <input type="text" name="inputPrice" value="{{$price}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputWarranty">Warranty</label>
                                    <div class="input-group">
                                        <input type="text" value="{{ $warranty }}" name="inputWarranty" id="inputWarranty" placeholder="Enter Warranty" class="form-control"/>
                                        <div class="input-group-append">
                                            <div class="input-group-text">Month</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Discounted Price</label>
                                    <input type="text" name="DiscountPrice" class="form-control" value="{{$dprice}}">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Other Details</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputCategory">Select Category</label>
                                    <select id="inputCategory" name="inputCategory" class="form-control custom-select">
                                        @for ($i = 0; $i < count($catArray);)
                                            <option value="{{ $catArray[$i + 1] }}" {{ $product->cat_id ==$catArray[$i + 1]?'selected':'' }}>{{ $catArray[$i] }}</option>
                                                {{ $i = $i + 2 }}
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="inputBrand">Select Brand</label>
                                    <select id="inputBrand" name="inputBrand" class="form-control custom-select">
                                        @for ($i = 0; $i < count($bransArray);)
                                            <option value="{{ $bransArray[$i + 1] }}" {{ $product->brand_id ==$bransArray[$i + 1]?'selected':'' }}>{{ $bransArray[$i] }}</option>
                                                {{ $i = $i + 2 }}
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group" style="display: none">
                                    <label for="inputSKU">SKU</label>
                                    <input type="text" name="inputSKU" id="inputSKU" value="{{$sku}}" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-6" style="display: none">
                                <div class="form-group">
                                    <label for="inputSize">Product Weight</label>
                                    <input type="text" name="inputSize" id="inputSize" class="form-control" value="{{$weight}}">
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
                                <div class="form-group" style="display: none">
                                    <label>Product Code</label>
                                    <input type="text" name="inputCode" class="form-control" value="{{$code}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" style="display: none">
                                    <label for="inputImage">Product Image</label> <br>
                                    @if($image)
                                        <img src="/images/products/{{$image}}" alt="" width="70px" style="border-radius: 100%;">
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" name="inputImage" class="custom-file-input" id="inputImage">
                                        <label class="custom-file-label" for="inputImage">Choose file</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="form-check text-center col-sm-6">
                                <input name="serial" type="hidden" value="0">
                                <input name="serial" class="form-check-input" type="checkbox" value="1" id="SerialCheckbox" {{ $serial == '1' ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="SerialCheckbox">
                                  SERIAL NUMBER ?
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Re-Order Number</label>
                                <input type="number" name="reorder" id="reorder" value="{{$reorder}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12">
                <a href="" class="btn btn-secondary">Cancel</a>
                <input type="submit" value="Update Product" class="btn btn-success float-right">
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
