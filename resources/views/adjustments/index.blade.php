@extends('layouts.admin.app_pos')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <i class=" fas fa-fw fa-check" aria-hidden="true"></i>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="card content-wrapper">

        <div class="card-header">

            <h5 class="my-1 float-left">Adjustments</h5>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('adjustments.adjustment.create') }}" class="btn btn-success"
                   title="Create New Adjustment">
                    <i class="fas fa-fw fa-plus" aria-hidden="true"></i>
                    Create New Adjustment
                </a>
            </div>
            <form action="{{ route('adjustments.adjustment.index') }}">
                <div class="justify-content-center d-flex mb-2">
                    <div>
                        <label class="text-dark"><b>From Date</b></label>
                        <input class="form-control" name="from_date" id="from_date" type="date" required
                               autocomplete="off" value="{{ $from_date }}">
                    </div>
                    <p class="mr-4"></p>
                    <div>
                        <label class="text-dark"><b>To Date</b></label>
                        <input class="form-control" name="to_date" id="to_date" type="date" required autocomplete="off"
                               value="{{ $to_date }}">
                    </div>
                    <div class="mt-auto ml-2">
                        <button type="submit" id="filter" class="btn btn-primary">Filter</button>
                        <a type="button" href="{{ route('adjustments.adjustment.index') }}" class="btn btn-default">Refresh</a>
                    </div>
                </div>
            </form>

        </div>

        @if(count($adjustments) == 0)
            <div class="card-body text-center">
                <h4>No Adjustments Available within {{ $from_date }} - {{ $to_date  }}</h4>
            </div>
        @else
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Ref</th>
                            <th>Warehouse</th>
                            <th>Total Quantity</th>
                            <th>Date</th>

                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($adjustments as $index => $adjustment)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>ADJ_{{ $adjustment->id }}</td>
                                <td>{{ optional($adjustment->warehouse)->name }}</td>
                                <td> {{ $adjustment->details->sum('add_qnt') + $adjustment->details->sum('sub_qnt') }}</td>
                                <td>{{ $adjustment->date }}</td>

                                <td>

                                    <form method="POST"
                                          action="{!! route('adjustments.adjustment.destroy', $adjustment->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        <div class="btn-group btn-group-sm float-right " role="group">
                                            <a href="{{ route('adjustments.adjustment.show', $adjustment->id ) }}"
                                               title="Show Adjustment">
                                                <i class="fa fa-eye text-info" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('adjustments.adjustment.edit', $adjustment->id ) }}"
                                               class="mx-4" title="Edit Adjustment">
                                                <i class="fas fa-edit text-primary" aria-hidden="true"></i>
                                            </a>

                                            <button type="submit" style="border: none;background: transparent"
                                                    title="Delete Adjustment"
                                                    onclick="return confirm(&quot;Click Ok to delete Adjustment.&quot;)">
                                                <i class=" fas  fa-trash text-danger" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        @endif

    </div>
@endsection

@section('page-js-script')

    <script>
        $(document).ready(function () {
            $('table').DataTable({
                responsive: true,
                "order": [],
                dom: 'lBfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ]

            });
        });
    </script>

    <style>
        .dataTables_filter {
            float: right;
        }

        i:hover {
            color: #0248fa !important;
        }

    </style>


@endsection


