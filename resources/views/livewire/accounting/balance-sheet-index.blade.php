<div>
    @section('title', 'Balance Sheet')

    <div class="content-wrapper">
        <h1>Balance Sheet</h1>
        <div class="text-dark mx-auto p-4 w-full p-4 rounded-lg bg-white border">
            <button onclick="makePDF('printSection')" class="btn btn-success rounded-0" style="float: right">Print</button>
            <div id="printSection" class="text-dark">
                <div class="text-center mb-2" id="heading" style="display: none">
                    <h1>{{ $setting->site_name ?? ''  }}</h1>
                    <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                    <h4>Balance Sheet</h4>
                </div>
                <hr>
        
                    <div class="row text-dark" style="width: 100%">
                        <div class="col-md">
                            <h3>Assets</h3>
                            <hr>
                            @php($totalAsset = 0)
        
                            @foreach($assets as $assetName => $assetValue)
                                <div class="ml-4">
                                    <h4 class="font-weight-bold">{{ $assetName }}</h4>
                                    @foreach(\App\AccHead::query()->where('sub_head',$assetName)->where('client_id', auth()->user()->client_id)->get() as $item)
                                        <div class="row ml-4">
                                            <h4 class="col-md">{{ $item->head }}</h4>
                                            @php($totalAsset += App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit'))
                                            <h4 class="col-md">{{ App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit') }}</h4>
                                        </div>
                                    @endforeach
                                    <div class="row ml-4" style="font-style: italic">
                                        <h4 class="col-md">Total {{ $assetName }}</h4>
                                        <h4 class="col-md"> {{ $assetValue }}</h4>
                                    </div>
                                    <hr>
                                </div>
                            @endforeach
                        </div>
        
                        <div class="col-md">
                            <h3>Liabilities</h3>
                            <hr>
                            @php($totalLib = 0)
        
                            @foreach($liabilities as $assetName => $assetValue)
                                <div class="ml-4">
                                    <h4 class="font-weight-bold">{{ $assetName }}</h4>
                                    @foreach(\App\AccHead::query()->where('sub_head',$assetName)->where('client_id', auth()->user()->client_id)->get() as $item)
                                        <div class="row ml-4">
                                            <h4 class="col-md">{{ $item->head }}</h4>
                                            @php($totalLib += App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit'))
                                            <h4 class="col-md">{{ App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit')  }}</h4>
                                        </div>
                                    @endforeach
                                    <div class="row ml-4" style="font-style: italic">
                                        <h4 class="col-md">Total {{ $assetName }}</h4>
                                        <h4 class="col-md"> {{ $totalLib }}</h4>
                                    </div>
                                    <hr>
        
                                </div>
                            @endforeach
        
                            <h3>Owner Equity</h3>
                            <hr>
        
                            @php($totalOE=0)
                            @foreach($equity as $assetName => $assetValue)
                                @php($OE = 0)
                                <div class="ml-4">
                                    <h4 class
                                    ="font-weight-bold">{{ $assetName }}</h4>
                                    @foreach(\App\AccHead::query()->where('sub_head',$assetName)->where('client_id', auth()->user()->client_id)->get() as $item)
                                        <div class="row ml-4">
                                            <h4 class="col-md">{{ $item->head }}</h4>
                                            @php($OE += App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit'))
        
                                            <h4 class="col-md">{{ App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('credit')-App\AccTransaction::query()->where('head', $item->head)->where('client_id', auth()->user()->client_id)->sum('debit')  }}</h4>
                                        </div>
                                    @endforeach
                                    <div class="row ml-4" style="font-style: italic">
                                        <h4 class="col-md">Total {{ $assetName }}</h4>
                                        <h4 class="col-md"> {{ $OE }}</h4>
                                    </div>
                                    <hr>
                                    @php($totalOE = $totalOE +$OE)
        
                                </div>
                            @endforeach
        
                            <div class="row ml-4">
                                <h4 class="col-md">Net Profit </h4>
                                <h4 class="col-md"> {{ $netProfit }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
        
                        <div class="col-md d-flex ml-4 " style="font-weight: bolder">
                            <h4 class="col-md font-weight-bold">Total </h4>
                            <h4 class="col-md font-weight-bold"> = {{ $totalAsset }}</h4>
                        </div>
        
                        <div class="col-md d-flex ml-4 " style="font-weight: bolder">
                            <h4 class="col-md font-weight-bold">Total </h4>
                            <h4 class="col-md font-weight-bold"> = {{ $totalLib + $totalOE + $netProfit }}</h4>
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
