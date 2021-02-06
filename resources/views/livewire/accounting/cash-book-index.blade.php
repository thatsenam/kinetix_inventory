<div>

    @section('title', 'Cash Book')

    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">
                <h1>Cash Book</h1>
        <div class="container pb-4">
            <form wire:submit.prevent="gatherCashbook">
    
                <div class="justify-content-center d-flex text-dark">
                    <div>
                        <label class="w3-text-teal"><b>Start Date</b></label>
                        <input class="form-control " wire:model.defer="start_date" type="date" required>
                    </div>
                    <p class="mr-4"></p>
                    <div>
                        <label class="w3-text-teal"><b>End Date</b></label>
                        <input class="form-control" wire:model.defer="end_date" type="date" required>
                    </div>
                    <div class="mt-auto ml-2">
                        <button type="submit" class="btn btn-primary rounded-0">Show Result</button>
                    </div>
                </div>
            </form>
        </div>
    
        <button onclick="makePDF('printSection')" class="btn btn-success rounded-0 mb-2 float-right">Print</button>
    
        <div id="printSection" class="text-dark">
    
            <div class="text-center mb-2" id="heading" style="display: none">
                <h1>{{ $setting->site_name ?? ''  }}</h1>
                <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                <h4>Cash Book</h4>
            </div>
    
            <div class="p-2 text-center text-dark font-weight-bold">
                <h2> {{ \Illuminate\Support\Carbon::createFromDate($start_date)->format('d-M-Y') }}
                    - {{ \Illuminate\Support\Carbon::createFromDate($end_date)->format('d-M-Y') }}</h2>
            </div>
    
            {{-- Header--}}
            <div class="row text-dark font-weight-bold border text-center">
                <div class="col-md">
                    <h1>Debit</h1>
                </div>
                <div class="col-md">
                    <h1>Credit</h1>
                </div>
            </div>
    
            {{-- Body--}}
        <div class="row">
            <div class="col-md-6 table-responsive pl-0">
                <table class="table table-bordered">
                    <tr>
                        <th>Date</th>
                        <th>VNO</th>
                        <th>Account</th>
                        <th>Particulars</th>
                        <th>Cash</th>
                    </tr>
                @php($totalDr = 0)
                    @foreach ($debits as $dr)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromDate($dr->date)->format('d-M-Y') }}</td>
                            <td>{{ $dr->vno }}</td>
                            <td>To {{ $dr->head }} A/c</td>
                            <td>{{ $dr->description ?? '-' }}</td>
                            <td>{{ $dr->credit }}</td>
                        </tr>
                        @php($totalDr = $totalDr + $dr->credit)
                        @endforeach
                        <tr>
                            <td colspan="5" class="font-weight-bold text-right"> {{ $totalDr }}</td>
                        </tr>
                    </table>
                </div>
        <div class="col-md-6 table-responsive pr-0">
            <table class="table table-bordered">
                <tr class="">
                    <th>Date</th>
                    <th>VNO</th>
                    <th>Account</th>
                    <th>Particulars</th>
                    <th>Cash</th>
                </tr>
                @php($totalCr = 0)
                    @foreach ($credits as $cr)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromDate($cr->date)->format('d-M-Y') }}</td>
                            <td>{{ $cr->vno }}</td>
                            <td>By {{ $cr->head }} A/c</td>
                            <td>{{ $dr->description ?? '-' }}</td>
                            <td>{{ $cr->debit }}</td>
                        </tr>
                        @php($totalCr = $totalCr + $cr->debit)
    
                        @endforeach
                        <tr>
                            <td colspan="5" class="font-weight-bold text-right"> {{ $totalCr }}</td>
                        </tr>
    
                    </table>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>

<script>
    function makePDF(printSection) {
        var head = document.getElementById("heading");
        head.style.display = "block";
        head.style.color = "black";
        var printContents = document.getElementById(printSection).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

</script>
