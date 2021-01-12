@extends('layouts.admin.app')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Product Images</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
              <li class="breadcrumb-item active">Product Images</li>
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
                @if ($error = Session::get('flash_message_error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $error }}</strong>
                </div>
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title">Create Attribute</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/create_attribute/'.$product->id) }}" id="addattribute" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="inputID" id="inputID" value="{{$product->id}}">
                            <div class="form-group row">
                                <label for="inputName" class="col-md-2 m-auto">Product Name</label>
                                <input type="text" name="inputName" id="inputName" class="form-control col-md-10" value="{{$product->product_name}}" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="inputName" class="col-md-2 m-auto">Product Price</label>
                                <input type="text" name="inputName" id="inputName" class="form-control col-md-10" value="{{$product->after_pprice}}" disabled>
                            </div>
                            <div class="form-group row">
                                <label for="inputCode" class="col-md-2 m-auto">Product Code</label>
                                <input type="text" name="inputCode" id="inputCode" class="form-control col-md-10" value="{{$product->product_code}}" disabled>
                            </div>
                            <div class="form-group row field_wrapper">
                                <label for="inputAttribute" class="col-md-2 m-auto">Attribute</label>
                                <div class="col-md-10 row pl-0">
                                    <input type="text" name="sku[]" id="sku" class="form-control col-md-3 m-1" placeholder="SKU" required>
                                    <input type="text" name="weight[]" id="weight" class="form-control col-md-2 m-1" placeholder="Weight" required>
                                    <input type="text" name="price[]" id="price" class="form-control col-md-2 m-1" placeholder="Price" required>
                                    <input type="text" name="stock[]" id="stock" class="form-control col-md-2 m-1" placeholder="Stock" required>
                                    <a href="javascript:void(0);" class="add_button col-md-2 m-auto" title="Add field">Add New</a>
                                </div>
                            </div>
                            <div class="form-group mt-5">
                                <a href="{{ url('/admin/create_attribute/'.$product->id) }}" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Create New Attribute" class="btn btn-success float-right">
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                </div>
            </div>

            <div class="row">
          <div class="col-12">
            @if ($success = Session::get('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $success }}</strong>
                </div>
            @endif
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">View All Attributes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="attTableView" class="table table-bordered table-hover table-sm">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>WEIGHT</th>
                    <th>PRICE</th>
                    <th>STOCK</th>
                    <th>ACTION</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($product['attributes'] as $attribute)
                    <tr>
                        <td>{{$attribute->id}}</td>
                        <td>{{$attribute->sku}}</td>
                        <td>{{$attribute->weight}}</td>
                        <td>{{$attribute->price}}</td>
                        <td>{{$attribute->stock}}</td>
                        <td class="text-center">
                          <button class="btn btn-danger btn-sm" onclick="deleteConfirmation({{$attribute->id}})">
                              <i class="fas fa-trash">
                              </i>
                              Delete
                          </button>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script type="text/javascript">
    $(document).ready(function(){
        var maxField = 10;
        var addButton = $('.add_button');
        var wrapper = $('.field_wrapper');
        var fieldHTML = '<div class="col-md-10 row offset-md-2"><input type="text" name="sku[]" id="sku" class="form-control col-md-3 m-1" placeholder="SKU" required><input type="text" name="weight[]" id="weight" class="form-control col-md-2 m-1" placeholder="Weight" required><input type="text" name="price[]" id="price" class="form-control col-md-2 m-1" placeholder="Price" required><input type="text" name="stock[]" id="stock" class="form-control col-md-2 m-1" placeholder="Stock" required><a href="javascript:void(0);" class="remove_button col-md-2 m-auto text-danger" title="Remove Field">Remove</a></div>'; 
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
      $("#attTableView").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
    function deleteConfirmation(id) {
        swal.fire({
            title: "Delete?",
            text: "Please ensure and then confirm!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/admin/delete_attribute')}}/" + id,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            swal.fire("Done!", results.message, "success");
                        } else {
                            swal.fire("Error!", results.message, "error");
                        }
                        window.setTimeout(function(){ 
                            location.reload();
                        } ,3000);
                    }
                });
            } else {
                e.dismiss;
            }
        }, function (dismiss) {
            return false;
        })
    }
</script>

@endsection