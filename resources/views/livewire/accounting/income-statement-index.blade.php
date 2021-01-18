<div>
    @section('title', 'Income Statement')
    <div class="content-wrapper">
        <h1>Income Statement</h1>
        <div class="card text-dark">
            <div class="card-body">
                <form wire:submit.prevent="gatherProfitLoss" class="d-flex justify-content-center">
                    <div class="d-flex justify-content-center">
                        <div class="form-group">
                            <label class=""><b>Start Date</b></label>
                            <input class="form-control" wire:model="start_date" type="date" required>
                        </div>
                        <div class="form-group ml-2">
                            <label class=""><b>End Date</b></label>
                            <input class="form-control" wire:model="end_date" type="date" required>
                        </div>
                            
                        <div class="form-group mt-auto ">
                            <button type="submit" class="btn btn-primary rounded-0 ml-2">Show Result</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
            
        <div class="card mt-3 text-dark">
            <div class="card-body">
            <button onclick="makePDF('printSection')" class="btn btn-success rounded-0" style="float: right">Print</button>
                
            <div id="printSection" class="printSection">
                <div class="text-center mb-2" id="heading" style="display: none">
                    <h1>{{ $setting->site_name ?? ''  }}</h1>
                    <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                    <h4>Income Statement</h4>
                </div>
                <div class="p-2 text-center font-weight-bold">
                    <h3> {{  \Illuminate\Support\Carbon::createFromDate($start_date)->format('d-M-Y') }}
                        - {{ \Illuminate\Support\Carbon::createFromDate($end_date)->format('d-M-Y') }}</h3>
                </div>
                <table class="table table-bordered w-full ">
        
                    <tr>
                        <th  colspan="3" scope="colgroup">Income</th>
                        <th  colspan="3" scope="colgroup">Expense</th>
                    </tr>
                    <tr>
                        <th  scope="col">#</th>
                        <th class="text-center" scope="col">Income Account</th>
                        <th  scope="col">Amount</th>
        
                        <th class="text-center" scope="col">#</th>
                        <th  class="text-center" scope="col">Expense Account</th>
                        <th  scope="col">Amount</th>
        
        
                    </tr>
        
                    @foreach($i as $incomeHead => $income )
                        @php($index = $loop->index)
                        <tr class="h-6">
                            <th class="text-center"
                                scope="row">{{ $loop->index+1 }}</th>
                            <td class="text-center" scope="row"> {{ $incomeHead }} </td>
                            <td class="text-center">{{ $income===0?'-':$income }} </td>
                            <th class="text-center"
                                scope="row">{{ $loop->index+1 }}</th>
                            @foreach($e as $expenseHead=> $expense)
                                @if ($index === $loop->index)
                                    <td class="text-center" scope="row">{{ $expenseHead }}</td>
                                    <td class="text-center"
                                        scope="row">{{ $expense===0?'-':$expense }}</td>
                                @endif
                            @endforeach
        
                        </tr>
                    @endforeach
                    
                    <tr>
                        <th  class="text-center" scope="row"></th>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-center">{{ $t_income }}</td>
                        <td class="text-center font-weight-bold">Total Profit <br> {{ $profit }} </td>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-center">{{ $t_expense }}</td>
                    </tr>
                </table>
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
