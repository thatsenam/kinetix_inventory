<div>

    @section('title', 'Ledger Report')
    
    <div class="content-wrapper">
        <h1>Ledger Report</h1>
        <div class="container">
            <form wire:submit.prevent="submit">
                <div class="justify-content-center d-flex text-dark">
                    <div class="option">
                        <label class="pl-1"> <b> Select Head</b></label>
                        <select class="custom-select" name="option" wire:model="selectedHead" required>
                            <option value="Choose your option" disabled selected>Choose your option</option>
                            @foreach($heads as $head)
                                <option value="{{ $head->head }}">{{ $head->head }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mr-4"></p>
    
                    <div>
                        <label class=""><b>Start Date</b></label>
                        <input class="form-control" wire:model.defer="startDate" type="date" required>
                    </div>
                    <p class="mr-4"></p>
                    <div>
                        <label class=""><b>End Date</b></label>
                        <input class="form-control" wire:model.defer="endDate" type="date" required>
                    </div>
                    <div class="mt-auto ml-2">
                        <button type="submit" class="btn btn-primary rounded-0">Show Data</button>
                    </div>
                </div>
            </form>
        </div>
    
        <div class="card mt-2">
            <div class="card-body">
            
            <button onclick="makePDF('printSection')" class="btn btn-success rounded-0 mb-2">Print</button>
        
                <div id="printSection" class="printArea">
                    <div class="text-center mb-2" id="heading" style="display: none">
                        <h1>{{ $setting->site_name ?? ''  }}</h1>
                        <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                        <h4>Ledger Report</h4>
                    </div>
            
                    <div class="">
            
                        <table class="table table-bordered table-stripped">
                            <tr>
                                <th scope="col">Previous Balance</th>
                                <th colspan="4"></th>
                                <th scope="col">{{ $previousBalance }}</th>
                            </tr>
            
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Voucher No#</th>
                                <th scope="col">Description</th>
                                <th scope="col">Debit Taka</th>
                                <th scope="col">Credit Taka</th>
                                <th class="text-center" scope="col">Balance</th>
                            </tr>
                            @php($pBalance = 0)
                            @foreach($reports as $index => $trail)
            
                                @php($pBalance = $pBalance + $trail->debit - $trail->credit )
        
                                <tr class="h-6">
                                    <th class="text-center"
                                        scope="row">{{ $trail->date  }}</th>
                                    <td scope="row">{{ $trail->vno  }}</td>
                                    <td scope="row">{{ $trail->description  }}</td>
                                    <td scope="row">{{ $trail->debit  }}</td>
                                    <td scope="row">{{ $trail->credit  }}</td>
                                    <th scope="row">{{ $pBalance  }}</th>
                                </tr>
                            @endforeach
            
                        </table>
            
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
