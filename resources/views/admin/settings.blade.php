@extends('layouts.admin.app_pos')
@section('content')

   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Settings</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            @if ($error = Session::get('flash_message_error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $error }}</strong>
            </div>
            @endif
            @if ($success = Session::get('flash_message_success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $success }}</strong>
            </div>
            @endif
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Site Settings</h3>
              </div>
              <div class="card-body">
                <form class="forms-sample" action="{{route('general_settings_save')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="form-group">
                        <label for="siteTagline">Website Tagline</label>
                        <input type="text" name="siteTagline" class="form-control" id="siteTagline" value="<?php echo $settings->site_tagline ?? "N/A";?>">
                    </div> --}}
                    <div class="form-group">
                        <label for="siteName">Shop Name</label>
                        <input type="text" name="siteName" class="form-control" id="siteName" value="<?php echo $settings->site_name ?? "N/A";?>">
                    </div>
                    <div class="form-group">
                        <label for="sitephone">Phone Number</label>
                        <input type="text" name="phone" class="form-control" id="phone"  value="<?php echo $settings->phone ?? "N/A";?>">
                    </div>
                    <div class="form-group">
                        <label for="siteemail">Email Address</label>
                        <input type="email" name="email" class="form-control" id="email"  value="<?php echo $settings->email ?? "N/A";?>">
                    </div>
                    <div class="form-group">
                      <label for="siteAddress">Address</label>
                      <input type="text" name="siteAddress" class="form-control" id="siteAddress"  value="<?php echo $settings->site_address ?? "N/A";?>">
                    </div>
                    <div class="row">
                      <div class="form-group col-md">
                        <label for="vat">Vat</label>
                        <div class="input-group">
                          <input type="text" name="vat" class="form-control" id="vat"  value="<?php echo $settings->vat ?? "N/A";?>">
                          <div class="input-group-append">
                            <span class="input-group-text font-weight-bold">%</span>
                          </div>
                        </div>
                      </div>
                      <div class="form-group col-md">
                        <label for="scharge">Service Charge</label>
                        <input type="text" name="scharge" class="form-control" id="scharge"  value="<?php echo $settings->scharge ?? "N/A";?>">
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm">
                        <div class="form-group">
                          @php
                              $ledger = false;
                              $pos = false;
                              if( $settings ){
                                if($settings->print_opt == '1')
                                {
                                  $ledger = true;
                                }
                                elseif($settings->print_opt == '2')
                                {
                                  $pos = true;
                                }
                              }
                          @endphp
                          <label for="print_opt">Print Type</label>
                          <select id="print_opt" name="print_opt" class="form-control custom-select">
                              <option value="1" {{ $ledger ? 'selected' : '' }}>Ledger Print</option>
                              <option value="2" {{ $pos ? 'selected' : '' }}>Pos Print</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm">
                        <div class="form-group">
                          @php
                              $avg = false;
                              $latest = false;
                              if( $settings ){
                                if($settings->profit_clc == '1')
                                {
                                  $avg = true;
                                }
                                elseif($settings->profit_clc == '2')
                                {
                                  $latest = true;
                                }
                              }
                          @endphp
                            <label for="profit_clc">Profit Calculation</label>
                            <select id="profit_clc" name="profit_clc" class="form-control custom-select">
                                <option value="1" {{ $avg ? 'selected' : '' }}>Average Price</option>
                                <option value="2" {{ $latest ? 'selected' : '' }}>Latest Price</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>Favicon</label>
                        <input type="file" name="favicon" class="form-control">
                        <img src="{{asset('/images/theme')}}/<?php echo $settings->favicon ?? "N/A";?>" style="width: 100px; height: auto; margin: 20px 0;">
                    </div>
                    <div class="form-group">
                        <label>Logo Small</label>
                        <input type="file" name="logoSmall" class="form-control">
                        <img src="{{asset('/images/theme')}}/<?php echo $settings->logo_small ?? "N/A";?>" style="width: 100px; height: auto; margin: 20px 0;">
                    </div>
                    <div class="form-group">
                        <label>Logo Big</label>
                        <input type="file" name="logoBig" class="form-control">
                        <img src="{{asset('/images/theme')}}/<?php echo $settings->logo_big ?? "N/A";?>" style="width: 100px; height: auto; margin: 20px 0;">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </div>
                </form>
              </div>
              <!-- form start -->

            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
$(function () {
  $('#quickForm').validate({
    rules: {
      current_password: {
        required: true,
        minlength: 5
      },
      password: {
        required: true,
        minlength: 5
      },
      confirm_password: {
        required: true,
        equalTo: "#change_password"
      },
    },
    messages: {
      current_password: {
        required: "Please provide current password",
        minlength: "Your password must be at least 5 characters long"
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      confirm_password: {
        required: "Please confirm password",
        equalTo: "Password didn't matched!"
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
@endsection
