@extends('admin.pos.master')
@section('title', 'Stock Report By Warehouse')
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

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Report By Warehouse</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main Page</a></li>
              <li class="breadcrumb-item active">Stock Report By Warehouse</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row input-daterange mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Reports</h3>
                    </div>
                <!-- /.card-header -->
                    <div class="card-body">
                        <?php
                            $length = count($trow);
                            for($i=0; $i< $length; $i++){
                                echo $trow[$i];
                            }
                        ?>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

@endif
<style>
    table {border-collapse: collapse;border-spacing: 0;width: 100%;border: 1px solid #ddd;}
    th, td {text-align: left;padding: 8px;}
    tr:nth-child(even){background-color: #f2f2f2}
</style>
@endsection
@section('page-js-script')
<script>
    $(document).ready(function() {
        $('table.display').DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@stop