@extends('layouts.admin.app')
@section('content')

<div class="content-wrapper" style="min-height: 1360.44px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit User</li>
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
            <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.update') }}" method="POST">
                            @csrf
                            <input name="id" value="{{ $user->id }}" type="text" hidden>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="label"> Name </label>
                                        <input type="text" class="form-control" value="{{ $user->name }}" name="name">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="label"> Email </label>
                                        <input type="text" class="form-control" value="{{ $user->email }}" name="email">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="label"> User Type </label>
                                        <select type="text" class="form-control"
                                                name="userType">
                                            <option value="admin" {{ $user->type == 'admin'?'selected':'' }}> Admin
                                            </option>
                                            <option value="customer" {{ $user->type == 'Customer'?'selected':'' }}>
                                                Customer
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="label mr-5">Permissions </label> <label> <input type="checkbox"
                                                                                                      id="checkAll">
                                            Grant
                                            All Permission</label>
                                        <select class="access-select form-control" name="access[]" multiple="multiple">
                                            @foreach($access as $a)
                                                <option value="{{ $a }}">{{ $a }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          <!-- /.col -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

    <script>
        $(document).ready(function () {
            let state = false;
            let PRESELECTED_ACCESS = {!! json_encode($user->access, JSON_HEX_TAG) !!};
            var All_ACCESS = {!! json_encode(PermissionAccess::getAccessList(), JSON_HEX_TAG) !!};
            console.log(All_ACCESS)
            PRESELECTED_ACCESS = JSON.parse(PRESELECTED_ACCESS);
            // var  ALL_ACCESS_ARRAY = JSON.parse(All_ACCESS);
            // console.log(PRESELECTED_ACCESS,PRESELECTED_ACCESS)
            $('.access-select').select2({});
            $('.access-select').val(PRESELECTED_ACCESS).trigger('change');
            $('#checkAll').change((e) => {
                console.log('Change', $('#checkAll').val(), e)
                state = !state;
                if (state) {
                    $('.access-select').val(All_ACCESS).trigger('change');
                } else {
                    $('.access-select').val(PRESELECTED_ACCESS).trigger('change');
                }
            })
        });
    </script>

@endsection