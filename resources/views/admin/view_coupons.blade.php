@extends('layouts.admin.app')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>View Coupons</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View Coupons</li>
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
                <h3 class="card-title">View All Coupons</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="ViewTable" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Coupon Code</th>
                    <th>Coupon Type</th>
                    <th>Coupon Rate/Amount</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>{{$coupon->id}}</td>
                        <td>{{$coupon->coupon_code}}</td>
                        <td>{{$coupon->amount_type}}</td>
                        <td>{{$coupon->amount}} @if($coupon->amount_type=="percentage") % @else BDT. @endif</td>
                        <td>{{$coupon->expiry_date}}</td>
                        <td class="text-center"><?php
                            if($coupon->status == 1){
                                echo '<span class="badge badge-success">Active</span>';
                            }elseif($coupon->status == 0){echo '<span class="badge badge-danger">Inactive</span>';}
                        ?></td>
                        <td class="project-actions text-right">
                          <a class="btn btn-info btn-sm" href="{{url('/admin/edit_coupon/'.$coupon->id)}}">
                              <i class="fas fa-pencil-alt">
                              </i>
                              Edit
                          </a>
                          <button class="btn btn-danger btn-sm" onclick="deleteConfirmation({{$coupon->id}})">
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
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script type="text/javascript">
    $(function () {
      $("#ViewTable").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });
    function deleteConfirmation(id) {
        swal.fire({
            title: "Are You Sure?",
            text: "Please ensure and then confirm!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            confirmButtonColor: "#dc3545",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/admin/delete_coupon')}}/" + id,
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