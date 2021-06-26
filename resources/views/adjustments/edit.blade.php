@extends('layouts.admin.app_pos')

@section('content')

    <div class="card content-wrapper">

        <div class="card-header">

            <h5 class="my-1 float-left">{{ !empty($title) ? $title : 'Adjustment' }}</h5>

            <div class="btn-group btn-group-sm float-right" role="group">

                <a href="{{ route('adjustments.adjustment.index') }}" class="btn btn-primary mr-2"
                   title="Show All Adjustment">
                    <i class=" fas fa-fw fa-th-list" aria-hidden="true"></i>
                    Show All Adjustment
                </a>

                <a href="{{ route('adjustments.adjustment.create') }}" class="btn btn-success"
                   title="Create New Adjustment">
                    <i class=" fas fa-fw fa-plus" aria-hidden="true"></i>
                    Create New Adjustment
                </a>

            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('adjustments.adjustment.update', $adjustment->id) }}"
                  id="edit_adjustment_form" name="edit_adjustment_form" accept-charset="UTF-8" class="form-horizontal">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="PUT">
                @include ('adjustments.form', [
                                            'adjustment' => $adjustment,
                                          ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('page-js-script')
    <script>
        let addRowBtn = document.getElementById('addRowBtn')
        let tableBody = document.getElementById('content')
        let skeleton = document.getElementById('from')
        var sl = 1;
        addRowBtn.addEventListener('click', function () {
            var count = $("#content tr").length;

            let id = Math.random();
            let copied = $(`<tr>`).append($(skeleton).clone());
            copied.find('tr').attr('id', id)
            copied.find('select').addClass('selectable')
            copied.find('select').prop('required', true)
            copied.find('button').addClass('delete')
            copied.closest('td').text(sl)
            $(tableBody).append(copied.html())
            $('.selectable').select2({placeholder: "--Select--"})

            $('#content tr').each(function (e, item) {
                console.log(e, $(item).closest('sl').html('slkdflsd'))
            })

            $('.delete').on('click', function (e) {
                $(this).closest('tr').remove()
            })
        })


        $(function () {
            $('.selectable').select2({placeholder: "--Select--"})
            $('#content tr').each(function (e, item) {
                console.log(e, $(item).closest('sl').html('slkdflsd'))
            })

            $('.delete').on('click', function (e) {
                $(this).closest('tr').remove()
            })

        })

    </script>
@endsection
