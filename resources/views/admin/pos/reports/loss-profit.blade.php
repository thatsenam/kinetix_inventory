@extends('admin.pos.master')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Loss/Profit Reports</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Loss-Profit Reports</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Expenses</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Opening Stock <span class="small">(By purchase price):</span></th>
                            <td>{{$total}}</td>
                        </tr>
                        <!-- <tr>
                            <th>Opening Stock <span class="small">(By sale price):</span></th>
                            <td>{{$get_sales}}</td>
                        </tr> -->
                        <tr>
                            <th>Total Purchase: </th>
                            <td>{{$total_purchase}}</td>
                        </tr>
                        <tr>
                            <th>Total Expense:</th>
                            <td>{{0.00}}</td>
                        </tr>
                        <tr>
                            <th>Total Sell discount:</th>
                            <td>{{$totalSell_discount}}</td>
                        </tr>
                        <tr>
                            <th>Total Sell Return:</th>
                            <td>{{$totalSell_return}}</td>
                        </tr>
                        <tr>
                            <th>Total Payroll:</th>
                            <td>{{0.00}}</td>
                        </tr>
                    </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Income</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                        <th>Closing stock <span class="small">(By purchase price):</span></th>
                        <td>{{$opening}}</td>
                    </tr>
                    <!-- <tr>
                        <th>Closing stock <span class="small">(By sale price):</span></th>
                        <td>{{$get_sales}}</td>
                    </tr> -->
                    <tr>
                        <th>Total Sales:</th>
                        <td>{{$get_sales}}</td>
                    </tr>
                    <tr>
                        <th>Total sell shipping charge:</th>
                        <td>{{$totalSCharge}}</td>
                    </tr>
                    <tr>
                        <th>Total Stock Recovered:</th>
                        <td>{{0.00}}</td>
                    </tr>
                    <tr>
                        <th>Total Purchase Return:</th>
                        <td>{{$purchaseReturn}}</td>
                    </tr>
                    <tr>
                        <th>Total Purchase discount:</th>
                        <td>{{$purchaseDiscount}}</td>
                    </tr>
                    <!-- <tr>
                        <th>Total sell round off:</th>
                        <td>0.00</td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <h3>Gross Profit: <?php $gProfit = $get_sales - $purchaseTotal; echo $gProfit; ?> </h3>
                <span class="small">(Total sell price - Total purchase price)</span>
                <h3>Net Profit: <?php $netProfit = ($opening + $get_sales + $totalSCharge + $purchaseReturn + $purchaseDiscount) - ($total + $total_purchase + $totalSell_discount + $totalSell_return); echo $netProfit; ?> </h3>
                <span class="small">(Closing stock + Total Sales + Total sell shipping charge + Total Purchase Return + Total Purchase discount) - (Opening Stock + Total purchase + Total Expense + Total purchase shipping charge + Total transfer shipping charge + Total Sell discount)</span>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

          <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Profit By Product</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Profit By Customer</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Profit By Date</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                    <table id="sales" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th>Gross Profit</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($get_products as $product)
                          <tr>
                            <td>{{$product->product_name}} <br> <input type="hidden" name="product_id" value=""></td>
                            <td>{{$product->grossProfitProduct}}</td>
                          </tr>
                          @endforeach
                        </tobody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                    <table id="sales" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Customer</th>
                            <th>Gross Profit</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($get_customers as $customer)
                          <tr>
                            <td>{{$customer->name}} <br> <input type="hidden" name="product_id" value=""></td>
                            <td>{{$customer->profitByCustomer}}</td>
                          </tr>
                          @endforeach
                        </tobody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                    <table id="sales" class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Sale Amount</th>
                            <th>Gross Profit</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($getsales as $sales)
                          <tr>
                            <td>{{$sales->created_at}} <br> <input type="hidden" name="product_id" value=""></td>
                            <td>{{$sales->salekk}}</td>
                            <td>{{$sales->profitbydate ?? 0}}</td>
                          </tr>
                          @endforeach
                        </tobody>
                    </table>
                </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection

@section('page-js-script')
@stop
