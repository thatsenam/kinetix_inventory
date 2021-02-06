@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">POS Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">POS Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
            
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Overview
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#daily-overview" data-toggle="tab">TODAY</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#weekly-overview" data-toggle="tab">WEEKLY</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#monthy-overview" data-toggle="tab">MONTHLY</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="daily-overview">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>৳ {{$todaysPurchase}}</h3>
                            <p>TOTAL PURCHASE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3>৳ {{$todaysSales}}</h3>
                            <p>TOTAL SALES</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3>৳ {{$todaysPurchasePayment}}</h3>
                            <p>PURCHASE PAYMENTS</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-person-add"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3>৳ {{$todaysSalesDue}}</h3>
                            <p>SALES DUE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <div class="chart tab-pane" id="weekly-overview">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>৳ {{$weeklyPurchase}}</h3>
                            <p>TOTAL PURCHASE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3>৳ {{$weeklySales}}</h3>
                            <p>TOTAL SALES</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3>৳ {{$weeklyPurchasePayment}}</h3>
                            <p>PURCHASE PAYMENTS</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-person-add"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3>৳ {{$weeklySalesDue}}</h3>
                            <p>SALES DUE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                    </div>
                    <!-- /.row --> 
                  </div>
                  <div class="chart tab-pane" id="monthly-overview">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <h3>৳ {{$monthlyPurchase}}</h3>
                            <p>TOTAL PURCHASE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-bag"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                          <div class="inner">
                            <h3>৳ {{$monthlySales}}</h3>
                            <p>TOTAL SALES</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <h3>৳ {{$monthlyPurchasePayment}}</h3>
                            <p>PURCHASE PAYMENTS</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-person-add"></i>
                          </div>
                          <a href="/dashboard/purchase_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                      <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                          <div class="inner">
                            <h3>৳ {{$monthlySalesDue}}</h3>
                            <p>SALES DUE</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                          </div>
                          <a href="/dashboard/sales_report_date" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                      </div>
                      <!-- ./col -->
                    </div>
                    <!-- /.row -->
                  </div>  
                </div>
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                      <div class="inner">
                        <h3>{{$total_stock}}</h3>
                        <p>TOTAL STOCK</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="/dashboard/reports/stock-report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                      <div class="inner">
                        <h3>৳ {{$stock_amount}}</h3>
                        <p>STOCK AMOUNT</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="/dashboard/reports/stock-report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="row">
              <div class="col-md-6">
                <div class="card card-success">
                  <div class="card-header">
                    <h3 class="card-title">Weekly Sales</h3>
    
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="wkSaleChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <div class="col-md-6">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title">Monthly Sales</h3>
    
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="moSaleChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>

              {{-- Reports --}}
              <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title">Today's Sale</h3>
                </div>
                <div class="card-body">
                    <table id="reports" class="table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>VAT</th>
                                <th>S.Charge</th>
                                <th>Discount</th>
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Profit</th>
                                <th>Payment</th>
                                <th>Due</th>
                                {{-- <th>Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">Total</th>
                                <th colspan="1"></th>
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
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<script>
  
  $(function () {
    var ctx = $('#wkSaleChart').get(0).getContext('2d');
    var labels = {!! json_encode($WeekDayArray) !!};
    var data = {!! json_encode($SaleByDateArray) !!};

    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: labels,
          datasets: [{
            label: 'Label',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: '#2c361f',
            data: data
          }]
      },
        
      options: {
          legend: {
              display: false
          },
          tooltips: {
              callbacks: {
                  label: function(tooltipItem) {
                        return tooltipItem.yLabel;
                  }
              }
          }
      }
    });

    //Monthly Sales
    var ctx = $('#moSaleChart').get(0).getContext('2d');

    var labels = {!! json_encode($monthsArray) !!};
    var data = {!! json_encode($SaleByMonthArray) !!};

    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: labels,
          datasets: [{
            label: 'Label',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: '#2c361f',
            data: data
          }]
      },
        
      options: {
          legend: {
              display: false
          },
          tooltips: {
              callbacks: {
                label: function(tooltipItem) {
                  return tooltipItem.yLabel;
                }
              }
          }
      }
    });

  });

</script>

<script>
  $(document).ready(function(){
    load_data ();
    function load_data(from_date = '', to_date = ''){
        $("#reports").DataTable({
            "responsive": true,
            "autoWidth": false,
            "precessing": true,
            "serverSide": true,
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
            dom: 'frtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "pageLength": 50,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                amount = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                total = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                profit = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                // Payment Total
                payTotal = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                due = total - payTotal;
    
                // Update footer
                $( api.column( 6 ).footer() ).html(
                    amount
                );
                $( api.column( 7 ).footer() ).html(
                    total
                );
                $( api.column( 8 ).footer() ).html(
                        profit
                );
                $( api.column( 9 ).footer() ).html(
                    payTotal
                );
                $( api.column( 10 ).footer() ).html(
                    due
                );
            },
            ajax: {
            url: '{{ route("get_sales_report_date") }}',
            data:{from_date:from_date, to_date:to_date},
            },
            columns: [
                {data:'date',},
                {data:'invoice_no',},
                {data:'cname',},
                {data:'vat',},
                {data:'scharge',},
                {data:'discount',},
                {data:'amount',},
                {data:'gtotal',},
                {data:'profit',},
                {data:'payment',},
                {data:'due',},
                // {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
      }
  });
</script>
        
@endsection