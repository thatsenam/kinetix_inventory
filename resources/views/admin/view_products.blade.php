@extends('layouts.admin.app_pos')
@section('content')
@if($AccHeads <= 0 || $GenSettings ==null)
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                        <h1>Please, Configure General Settings and create Acoounts demo heads from before proceed.</h1>
                    </div>
                </div>
            </div>
        </section>
    </div>
@else
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Products</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
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
                <h3 class="card-title">View All Products</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="ProductViewTable" class="table table-sm table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Unit</th>
                    <th>Sub-Unit</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach($products as $product)
                    <tr>
                        <td>{{$i++}}</td>
                        <td style="width: 30%;">{{$product->product_name}}</td>
                        <td>
                            @if(empty($product->after_pprice))
                                {{$product->before_price}}
                            @else
                            {{$product->after_pprice}} <small style="color:crimson;text-decoration: line-through;">{{$product->before_price}}</small>
                            @endif
                        </td>
                        <td>{{$product->stock}}</td>
                        <td><?php if($product->is_featured == 0){ echo '<span class="badge badge-warning">Regular</span>';}elseif($product->is_featured == 1){ echo "<span class='badge badge-success'>Featured</span>";} ?></td>

                        <td>{{$product->unit}}</td>
                        <td>{{$product->sub_unit}}</td>
                        <td class="project-actions text-center">
{{--                          <a class="btn btn-info btn-sm mb-1" href="{{url('/products/'.$product->id)}}" target="_blank" title="View"><i class="fas fa-eye"></i></a>--}}
                          <a class="btn btn-info btn-sm mb-1" href="{{url('/admin/edit_product/'.$product->id)}}" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                          <a class="btn btn-info btn-sm mb-1" href="{{url('/admin/create_attribute/'.$product->id)}}" title="Add Attribute">+Attribute</a>
                          <!--<a class="btn btn-info btn-sm mb-1" href="{{url('/admin/create_attribute/'.$product->id)}}" title="Add Images For This Product">+ Images</a>-->
                          <button class="btn btn-danger btn-sm" onclick="deleteConfirmation({{$product->id}})" title="Delete Prodduct"><i class="fas fa-trash"></i></button>
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
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(function () {
      $("#ProductViewTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "desc" ]]
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
                    url: "{{url('/admin/delete_product')}}/" + id,
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

@endif
@endsection
