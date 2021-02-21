@extends('layouts.admin.app_pos')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ব্যয় দেখুন</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">হোম</a></li>
              <li class="breadcrumb-item active">ব্যয় দেখুন</li>
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
                <h3 class="card-title">সকল ব্যয় দেখুন</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="ProductViewTable" class="table text-center table-sm table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>তারিখ</th>
                    <th>খাত</th>
                    <th>ব্যয়ের কারন</th>
                    <th>পরিমান</th>
                    <th>অপশন</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php 
                        $i = 1;
                    @endphp
                    @foreach($costs as $cost)
                    <tr>
                        <td>{{$i++}}</td>
                        <td style="width: 30%;">{{$cost->date}}</td>
                        <td>{{ $cost->head}}</td>
                        <td>
                            {{$cost->description}}
                        </td>
                        <td>
                            {{$cost->debit}}
                        </td>
                        <td class="project-actions text-center">
                          <button class="btn btn-danger btn-sm" onclick="deleteConfirmation('{{$cost->vno}}')" title="ডিলিট"><i class="fas fa-trash"></i></button>
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
            title: "ডিলিট?",
            text: "ডিলিট করতে চান?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "হ্যাঁ!",
            cancelButtonText: "না!",
            reverseButtons: !0
        }).then(function (e) {
            if (e.value === true) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{url('/admin/delete_cost')}}/" + id,
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