@extends('layouts.admin.app')
@section('content')

<?php $WeekDayArray = $data['WeekDayArray']; $SaleByDateArray = $data['SaleByDateArray']; 
        $monthsArray = $data['monthsArray']; $SaleByMonthArray = $data['SaleByMonthArray'];?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$todays_order->count()}}</h3>
                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$todays_pending->count()}}</h3>
                <p>Todays Pending</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="/orders/get_pending" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$data['totalShipped']}}</h3>
                <p>Total Shipped</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/orders/get_shipped" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$data['totalDelivered']}}</h3>
                <p>Total Delivered</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="/orders/get_delivered" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
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

        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-header">
                      Today's Order
                  </div>
                  <div class="card-body">
                  <table id="Pendings" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"></th>
                      <th>Order Number</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($todays_order as $pendings)
                    <tr>
                        <td><input type="checkbox" class="slct" value="{{$pendings->order_number}}"></td>
                        <td><a href="/admin/get_invoice/{{$pendings->order_number}}">{{$pendings->order_number}}</a></td>
                        <td>{{$pendings->name}}</td>
                        <td>{{$pendings->email}}</td>
                        <td>{{$pendings->grand_total}}</td>
                        <td>{{$pendings->order_date}}</td>
                        <td><a href="/admin/get_invoice/{{$pendings->order_number}}"><i class="fa fa-eye"></i> View</a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                  </div>
                </div>
              </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
  $(function () {
    var ctx = $('#wkSaleChart').get(0).getContext('2d');
    var data = <?= json_encode($SaleByDateArray); ?>;
    var labels = <?= json_encode($WeekDayArray); ?>; 
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
    var data = <?= json_encode($SaleByMonthArray); ?>;
    var labels = <?= json_encode($monthsArray); ?>; 
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

    $('#Pendings').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  })

</script>

@endsection
