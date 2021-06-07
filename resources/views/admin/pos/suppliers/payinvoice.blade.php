@extends('admin.pos.master')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>মেমো</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">হোম</a></li>
              <li class="breadcrumb-item active">মেমো</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="hr mb-4 bg-info" style="height: 30px;"></div>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> {{$GenSettings->site_name ?? " "}}
                    <small class="float-right">তারিখ: <?php echo date('Y-m-d'); ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  প্রতিষ্ঠান
                  <address>
                    <strong>{{$GenSettings->site_name ?? " "}}</strong><br>
                    {{$GenSettings->site_address ?? " "}} <br>
                    {{$GenSettings->phone ?? " "}}<br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  প্রাপক
                  <address>
                    @foreach($supp_details as $detail)
                    <input type="hidden" name="custid" id="custid" value="{{$detail->id}}">
                    <strong>{{$detail->name}}</strong><br>
                    {{$detail->address}}<br>
                    Phone: {{$detail->phone}}<br>
                    @endforeach
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <br>
                  <b>মেমো #{{$get_supplier->pur_inv}}</b><br>
                  <b>পরিশোধের তারিখ:</b> {{ $get_supplier->date }}<br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-sm table-striped">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>পণ্য</th>
                      <th>পরিমান</th>
                      <th>মূল্য</th>
                      <th>মোট</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i = 1)
                      @foreach($details as $d)
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$d->name ?? " "}}</td>
                        <td>{{$d->qnt ?? " "}}</td>
                        <td>{{$d->price ?? " "}}</td>
                        <td>{{$d->price * $d->qnt }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="3" class="text-right"><strong>মোট</strong></td>
                        <td colspan="2" class="text-right">{{$get_supplier->amount }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->


              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="javascript:window.print();" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection

@section('page-js-script')
<script type="text/javascript">
  window.addEventListener("load", window.print());
</script>
@stop
<style>
  .hr{display: none}
  @media print {
  .hr{display: block}
  .callout { display: none }
  }
</style>
