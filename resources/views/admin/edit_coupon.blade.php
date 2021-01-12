@extends('layouts.admin.app')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Coupon</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
              <li class="breadcrumb-item active">Edit Coupon</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
              @if ($success = Session::get('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $success }}</strong>
                </div>
              @endif
            <div class="row">
                <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title">Edit Coupon</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/admin/edit_coupon/'.$couponData->id) }}" id="createCoupon" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Amount Type</label>
                                <select class="form-control select2" name="inputType">
                                    <option @if($couponData->amount_type=="percentage") selected @endif value="percentage">Percentage</option>
                                    <option @if($couponData->amount_type=="fixed") selected @endif value="fixed">Fixed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputCode">Coupon Code</label>
                                <input type="text" name="inputCode" id="inputCode" value="{{$couponData->coupon_code}}" class="form-control" minlength="5" maxlength="15" required>
                            </div>
                            <div class="form-group">
                                <label for="inputAmount">Amount/Percentage Rate</label>
                                <input type="number" name="inputAmount" value="{{$couponData->amount}}" id="inputCode" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="ExpiryDate">Expiry Date</label>
                                <input type="text" name="ExpiryDate" value="{{$couponData->expiry_date}}" id="datepicker" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="inputEnable" value="1" @if($couponData->status=="1") checked @endif class="form-check-input" id="inputEnable">
                                <label class="form-check-label" for="exampleCheck1">Enable/Disable Coupon</label>
                            </div>
                            <div class="form-group mt-5">
                                <a href="{{ url('/admin/edit_coupn/'.$couponData->id) }}" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Update Coupon" class="btn btn-success float-right">
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
<script type="text/javascript">
  
  $( function() {
    $( "#datepicker" ).datepicker({
      minDate: 0,
      dateFormat: 'yy-mm-dd'
    });
  });

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>

<style>
 
</style>

@endsection