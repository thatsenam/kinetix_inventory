@extends('layouts.admin.app')
@section('content')

<div class="content-wrapper" style="min-height: 1360.44px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">All Users</li>
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
            @if(session()->has('message'))
              <div class="alert alert-success">
                <span> {{ session('message') }}</span>
              </div>@endif
            <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            <table class="table  table-hover table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>eMail</th>
                                    <th>Access</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @php($sl = 1)

                                @foreach($users as $user)

                                    <tr>
                                        <td id="{{$user->id}}">{{$sl}}</td>
                                        <td class="text_td">
                                            <span>{{$user->name}}</span>
                                            <input type="text" id="name" class="form-control text_change"
                                                   value="{{$user->name}}" style="display: none;">
                                        </td>
                                        <td class="text_td">
                                            <span>{{$user->phone}}</span>
                                            <input type="text" id="phone" class="form-control text_change"
                                                   value="{{$user->phone}}" style="display: none;">
                                        </td>
                                        <td class="text_td">
                                            <span>{{$user->email}}</span>
                                            <input type="text" id="email" class="form-control text_change"
                                                   value="{{$user->email}}" style="display: none;">
                                        </td>
                                        <td class="text_td">
                                            <span>{{$user->address}}</span>
                                            @php( $accessCount = count(json_decode($user->access)??[]))
                                            <h4 class="badge badge-outline-success badge-pill m-1">
                                                <b>{{ $accessCount === count(PermissionAccess::getAccessList())?'Full':'Limited' }}
                                                    access</b>
                                            </h4>
                                            <br>

                                        </td>
                                        <td>


                                            <?php if($user->type == 'customer'){?>
                                            <label
                                                class="badge badge-outline-secondary badge-pill"><b>Customer</b></label>
                                            <?php }else if($user->type == 'admin'){?>
                                            <label class="badge badge-outline-info badge-pill"><b>Admin</b></label>
                                            <?php }else { ?>

                                                <?php } ?>


                                        </td>
                                        <td>

                                            <?php if($user->status == '1'){?>
                                            <label class="badge badge-outline-info badge-pill"><b>Active</b></label>

                                            <?php }else if($user->status == '0' || $user->status == NULL){?>
                                            <label
                                                class="badge badge-outline-secondary badge-pill"><b>Inactive</b></label>

                                            <?php } ?>

                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-info" href="{{ route('users.edit',$user->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i>
                                            </button>
                                    </tr>

                                    @php($sl = $sl + 1)

                                @endforeach

                            </table>

                            <span>{{$users->links()}}</span>

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

@endsection