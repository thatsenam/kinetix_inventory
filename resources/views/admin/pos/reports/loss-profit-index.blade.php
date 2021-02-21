@extends('admin.pos.master')

@section('content')
    @if($AccHeads <= 0 || $GenSettings ==null)
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                            <h1>Please, Configure General Settings and create Acoounts demo heads from before proceed.</h1>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else

    @livewire('loss-profit-index')

    @endif

@endsection