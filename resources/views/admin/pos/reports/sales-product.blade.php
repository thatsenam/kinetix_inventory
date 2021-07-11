@extends('admin.pos.master')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales Report By Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">Sales By Product</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <form action="">
                  <div class="row input-daterange mb-3">
                      <div class="col-md-3">
                          <select id="select_product" name="select_product" class="form-control select2" required>
                              <option value="" >Select Product</option>
                              @foreach($products as $product)
                                  <option value="{{$product->pid}}">{{$product->product_name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-3">
                          <input type="date" name="from_date" id="from_date" value="{{$formtime}}"  class="form-control" placeholder="From Date" required>
                      </div>
                      <div class="col-md-3">
                          <input type="date" name="to_date" id="to_date"  value="{{$lastTime}}" class="form-control" placeholder="To Date" required>
                      </div>
                      <div class="col-md-3">
                          <input type="submit" class="btn btn-primary" value="Filter">
                          <a href="{{route('salesby.product')}}" class="btn btn-default">Refresh</a>
                      </div>
                  </div>

              </form>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sales Report By Product</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>View Invoice</th>
                        <th>Quantity</th>
                        <th>box</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                  </thead>
                    <tbody>

                    @foreach($data as $sales)
                        <tr>
                            <td>{{$sales->product->product_name ?? " "}}</td>
                            <td><a data-id="INV-1202106061" href="/dashboard/sales_invoice/{{$sales->invoice_no}}"><span class="fa fa-eye"></span> View</a></td>
                            <td>{{$sales->qnt}}</td>
                            <td>{{intdiv($sales->qnt ,$sales->product->per_box_qty ?? 1)}} {{$sales->product->sub_unit ?? " "}}  {{$sales->qnt % $sales->product->per_box_qty ?? 1}}</td>
                            <td>{{$sales->price}}</td>
                            <td>{{$sales->total}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                  </tfoot>
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
</div>

@endsection
@section('page-js-files')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        } );

        $(document).ready(function() {
            $('#select_product').select2();
        });
    </script>
    @endsection


