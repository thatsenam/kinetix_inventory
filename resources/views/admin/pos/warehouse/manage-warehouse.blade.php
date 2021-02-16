@extends('admin.pos.master')
@section('title', 'গুদাম')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Warehouse</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main Page</a></li>
              <li class="breadcrumb-item active">Manage Warehouse</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Warehouse</h3>
                    </div>
                    <!-- /.card-header -->
                        <div class="card-body">
                            @if (session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                            @livewire('warehouse')
                        </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<style>
    
</style>

@endsection
@section('page-js-script')
<script src="{{ asset('js/conversion.js')}}"></script>
<script>
    
</script>
@stop