<div>
    @section('title', 'Voucher History')

    <div class="content-wrapper">
        <h1>Voucher History</h1>
        @if (session('success'))
        <div class="alert alert-success font-weight-bold">
            {{ session('success') }}
        </div>
    @endif
    @if (session('danger'))
        <div class="alert alert-danger font-weight-bold">
            {{ session('danger') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="row col-md-5">
                    <label for="SelectVoucher" class="w-25 text-dark">
                        Voucher No
                    </label>
                    <div class="w-50">
                        <select wire:model="voucher_no" id="SelectVoucher" class="custom-select">
                            <option></option>
                            @foreach ($all_vouchers as $voucher)
                                <option value="{{ $voucher }}">{{ $voucher }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row col-md-7">
                    <div class="col-md row">
                        <label for="start" class="col-form-label text-dark">Start</label>
                        <div class="col-md-9">
                            <input wire:model.defer="start" type="date" class="form-control" id="start"
                                placeholder="Voucher Date">
                        </div>
                    </div>
                    <div class="col-md row">
                        <label for="end" class="col-form-label text-dark">End</label>
                        <div class="col-md-9">
                            <input wire:model.defer="end" type="date" class="form-control" id="end"
                                placeholder="Voucher Date">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button wire:click.prevent="dateSearch" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3 text-dark">
        <div class="card-body">
            @foreach ($voucher_entries as $key => $voucher_entry)
                @php $debit = 0; $credit = 0; @endphp
                <div>
                    <div style="float: right;">
                        <button onclick="makePDF('{{ $key }}')" class="btn btn-success rounded-0">Print</button>
                        <button wire:click.prevent="destroy('{{ $key }}')" class="btn btn-danger rounded-0">Delete</button>
                    </div>
                </div>
                
                <div id="print{{ $key }}">
                    <div class="text-center mb-2" id="heading{{ $key }}" style="display: none">
                        <h1>{{ $setting->site_name ?? ''  }}</h1>
                        <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                        <h4>Voucher</h4>
                    </div>
                    <h3 style="float:left;">Voucher : {{ $key }}</h3>
                    <table class="table table-bordered" style="width:100%" id="table{{ $key }}">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Voucher No</th>
                                <th>Head</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($voucher_entry as $entry)
                                {{-- @if( $entry )
                                @endif --}}
                                    @php
                                        $debit = $debit + $entry->debit;
                                        $credit = $credit + $entry->credit;
                                    @endphp
                                    <tr class="w-full">
                                        <th>{{ $entry->date ?? '' }}</th>
                                        <th>{{ $entry->vno ?? '' }}</th>
                                        <th>{{ $entry->head ?? '' }}</th>
                                        <th>{{ $entry->credit ?? '' }}</th>
                                        <th>{{ $entry->debit ?? '' }}</th>
                                        <th>{{ $entry->description ?? '' }}</th>
                                    </tr>
                            @endforeach

                            <tr class="w-full">
                                <th></th>
                                <th></th>
                                <th></th>

                                <th class="">{{ $credit }}</th>
                                <th class="">{{ $debit }}</th>

                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
            @endforeach
            {{-- {{ $voucher_entries->links() }}
            @include('admin.partials.print_footer2') --}}
        </div>
    </div>
    </div>
</div>

<script>
    window.addEventListener("livewire:load", function(event) {

        $('#SelectVoucher').select2({
            placeholder: 'Choose Voucher No',
        });
        $(document).on('change', '#SelectVoucher', function(e) {
            @this.set('voucher_no', e.target.value);
        });

    });

</script>



<script>
    function makePDF(printSection) {
        var head = document.getElementById("heading" + printSection);
        head.style.display = "block";
        head.style.color = "black";
        var printContents = document.getElementById('print' + printSection).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
