<div>
    @section('title', 'Trial Balance')
    
    <div class="content-wrapper">
        <h1>Trial Balance</h1>
        <div class="mb-16">

            <div class="container">
                <form wire:submit.prevent="submit">
        
                    <div class="justify-content-center d-flex">
                        <div>
                            <label class="text-dark"><b>Start Date</b></label>
                            <input class="form-control" wire:model="startDate" type="date" required>
                        </div>
                        <p class="mr-4"></p>
                        <div>
                            <label class="text-dark"><b>End Date</b></label>
                            <input class="form-control" wire:model="endDate" type="date" required>
        
                        </div>
                        <div class="mt-auto ml-2">
                            <button type="button" wire:click="submit" class="btn btn-primary rounded-0">Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        
            <div class="card bg-white mt-4">
                <div class="card-body">
                    <table class="table table-striped w-full ">
        
                        <tr>
                            <th class="border text-center   " scope="col">Ledger Name</th>
                            <th class="border" scope="col">Debit Taka</th>
                            <th class="border" scope="col">Credit Taka</th>
                            <th class="border text-center" scope="col">Closing Balance</th>
                        </tr>
                        @foreach($trails as $index=>$trail)
                            <tr class="h-6">
                                <th class="border text-center"
                                    scope="row">{{ $trail['head']  }}</th>
                                <td class="border" scope="row">{{ $trail['debit']  }}</td>
                                <td class="border" scope="row">{{ $trail['credit']  }}</td>
                                <th class="border text-center"
                                    scope="row">{{ $trail['bal']  }}</th>
                            </tr>
                        @endforeach
            
                    </table>
                </div>
        
            </div>
        </div>
        
        </div>
    </div>
</div>
