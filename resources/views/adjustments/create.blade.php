@extends('layouts.admin.app_pos')

@section('content')

    <div class="card content-wrapper">

        <div class="card-header">

            <h5 class="my-1 float-left">Create New Adjustment</h5>

            <div class="btn-group btn-group-sm float-right" role="group">
                <a href="{{ route('adjustments.adjustment.index') }}" class="btn btn-primary"
                   title="Show All Adjustment">
                    <i class=" fas fa-fw fa-th-list" aria-hidden="true"></i>
                    Show All Adjustment
                </a>
            </div>

        </div>

        <div class="card-body">


            <form method="POST" action="{{ route('adjustments.adjustment.store') }}" accept-charset="UTF-8"
                  id="create_adjustment_form" name="create_adjustment_form" class="form-horizontal">
                {{ csrf_field() }}
                @include ('adjustments.form', [
                                            'adjustment' => null,
                                          ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
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
            copied.find('tr').attr('id',id)
            copied.find('select').addClass('selectable')
            copied.find('select').prop('required',true)
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

        })

    </script>
@endsection

