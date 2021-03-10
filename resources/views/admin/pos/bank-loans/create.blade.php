@extends('admin.pos.master')
@section('title', 'ব্যাংক ঋণ গ্রহণ')
@section('content')
    @if($AccHeads <= 0 || $GenSettings ==null)
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                            <h1>অনুগ্রহপূর্বক সাধারণ সেটিংস্‌ এবং হিসাবরক্ষণ খাত থেকে ডেমো খাত যুক্ত করুন!</h1>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="content-wrapper pb-5">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>ব্যাংক ঋণ গ্রহণ</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">মূলপাতা</a></li>
                                <li class="breadcrumb-item active">ব্যাংক ঋণ গ্রহণ</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @if ($success = Session::get('flash_message_success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $success }}</strong>
                            </div>
                        @endif
                        @if ($error = Session::get('flash_message_error'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $error }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <form action="{{ route('admin.pos.bank-loans.create') }}" id="" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-9 m-auto">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">ঋণের তথ্য ফর্ম</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="bank_id" id="bank_id" class="form-control" required>
                                                    <option selected disabled value="">ব্যাংক নির্বাচন করুন</option>
                                                    @foreach($banks as $row)
                                                        <option value= {{$row->id}}>{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select name="account_id" id="account_id" class="form-control" required>
                                                    <option selected disabled value="">ব্যাংক হিসাব নির্বাচন করুন
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputLoanDate">ঋণদানের তারিখ</label>
                                                <input type="text"
                                                       value="{{ date('Y-m-d') }}"
                                                       id="inputLoanDate" name="inputLoanDate" class="form-control"
                                                       placeholder="ঋণদানের তারিখ" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputLoanExpireDate">ঋণ সমাপ্তির তারিখ</label>
                                                <input type="text"
                                                       value="{{ date('Y-m-d') }}"
                                                       id="inputLoanExpireDate" name="inputLoanExpireDate"
                                                       class="form-control" placeholder="ঋণ সমাপ্তির তারিখ" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputLoanAmount">পরিমাণ</label>
                                                <input type="text" id="inputLoanAmount" name="inputLoanAmount"
                                                       class="form-control" placeholder="ঋণের পরিমাণ (টাকায়)" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputInterestRate">সুদের হার (%)</label>
                                                <input type="text" id="inputInterestRate" name="inputInterestRate"
                                                       class="form-control" placeholder="শতকরা সুদের হার" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inputTotalAmount">মোট ঋণ</label>
                                                <input type="text" id="inputTotalAmount" name="inputTotalAmount"
                                                       class="form-control" placeholder="সুদ-সহ ঋণের পরিমাণ (টাকায়)"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="installmentAmount">কিস্তির পরিমাণ</label>
                                                <input type="text" id="installmentAmount" name="installmentAmount"
                                                       class="form-control" placeholder="কিস্তির পরিমাণ" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 m-auto">
                            <a href="" class="btn btn-secondary">বাতিল</a>
                            <input type="submit" value="সম্পন্ন করুন" class="btn btn-success float-right">
                        </div>
                    </div>
                </form>
            </section>
            <!-- /.content -->
        </div>
    @endif
@endsection
@section('page-js-script')
    <script>
        $(function () {
            $('.select2').select2()
        });

        $(document).ready(function () {
            $("#inputLoanDate").datepicker({
                dateFormat: 'yy-mm-dd'
            })
            $("#inputLoanExpireDate").datepicker({
                dateFormat: 'yy-mm-dd'
            })
            $('#inputLoanDate').on('change', function () {
                $val = $('#inputLoanDate').val();
                $('#inputLoanDate').val(etob($val));
            })
            $('#inputLoanExpireDate').on('change', function () {
                $val = $('#inputLoanExpireDate').val();
                $('#inputLoanExpireDate').val(etob($val));
            })

            $('#bank_id').change(function () {
                var bank_id = $(this).val();
                var formData = new FormData();
                formData.append('bank_id', bank_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_acc') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        $("#overlay").show();
                    },
                    complete: function () {
                        $("#overlay").hide();
                    },
                    error: function (ts) {
                        //alert(ts.responseText)
                        $('#account_id option').remove();
                        $('#account_id').append(ts.responseText);
                    },
                    success: function (data) {
                        alert(data);
                    }
                });
            });
        });
    </script>
@stop
