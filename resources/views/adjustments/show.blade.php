@extends('layouts.admin.app_pos')

@section('content')

<div class="card">
    <div class="card-header">

        <h5  class="my-1 float-left">{{ isset($title) ? $title : 'Adjustment' }}</h5>

        <div class="float-right">

            <form method="POST" action="{!! route('adjustments.adjustment.destroy', $adjustment->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('adjustments.adjustment.index') }}" class="btn btn-primary mr-2" title="Show All Adjustment">
                        <i class=" fas fa-fw fa-th-list" aria-hidden="true"></i>
                        Show All Adjustment
                    </a>

                    <a href="{{ route('adjustments.adjustment.create') }}" class="btn btn-success mr-2" title="Create New Adjustment">
                        <i class=" fas fa-fw fa-plus" aria-hidden="true"></i>
                        Create New Adjustment
                    </a>

                    <a href="{{ route('adjustments.adjustment.edit', $adjustment->id ) }}" class="btn btn-primary mr-2" title="Edit Adjustment">
                        <i class=" fas fa-fw fa-pencil-alt" aria-hidden="true"></i>
                        Edit Adjustment
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Adjustment" onclick="return confirm(&quot;Click Ok to delete Adjustment.?&quot;)">
                        <i class=" fas fa-fw fa-trash-alt" aria-hidden="true"></i>
                        Delete Adjustment
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="card-body">
        <dl class="dl-horizontal">
            <dt>Warehouse</dt>
            <dd>{{ optional($adjustment->warehouse)->id }}</dd>
            <dt>Date</dt>
            <dd>{{ $adjustment->date }}</dd>
            <dt>Note</dt>
            <dd>{{ $adjustment->note }}</dd>

        </dl>

    </div>
</div>

@endsection
