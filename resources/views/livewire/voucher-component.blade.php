
                            <form wire:submit.prevent="save">
                                <!-- Page Title Header Starts-->
                                @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <div class="row page-title-header">
                                    <div class="col-6">
                                        {{$voucherType}}
                                        <label class="page-title">Voucher Type</label>
                                        <select class="form-control" id="voucher_type" style="width: 180px;" wire:model="voucherType">
                                            <option disabled>Select Voucher Type</option>
                                            <option value="Debit">Debit</option>
                                            <option value="Credit">Credit</option>
                                            <option value="Journal">Journal</option>
                                        </select>
                                        @error('voucherType') <span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <br>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 required">
                                            <label for="invoice_number" class="control-label">Voucher Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><i class="fa fa-sort-numeric-asc input-group-text" aria-hidden="true"></i></div>
                                                <input class="form-control" required="required" type="text" id="vnumber" value="{{$vno}}" wire:model="vno">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 required ">
                                            <label for="invoiced_at" class="control-label">Voucher Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><i class="input-group-text fa fa-calendar"></i></div>
                                                <input class="form-control datepicker" value="{{$date}}" id="vdate" type="text" wire:model="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 required ">
                                            <label for="customer_id" class="control-label">Head</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><i class="fa fa-user input-group-text"></i></div>
                                                <select  class="form-control select2" id="vhead" wire:model="head">
                                                    <option value="">Select Head</option>
                                                    @foreach($heads as $head)
                                                        <option value="{{$head->head}}">{{$head->head}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="button" id="btn_addHead" class="btn btn-default btn-icon input-group-text" data-toggle="modal" data-target="#addHead" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i></button>
                                                </div>
                                            </div>
                                            @error('head') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="form-group col-md-6 required">
                                            <label for="vdescription" class="control-label">Description</label>
                                            <input class="form-control" type="text" id="vdescription" wire:model="description">
                                            @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 required">
                                            <label for="invoice_number" class="control-label">Transaction Type</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><i class="fa fa-file-text-o input-group-text"></i></div>
                                                <select  class="form-control" id="vtype" wire:model="transaction">
                                                    <option value="">Select Type</option>
                                                    <option value="Debit">Debit</option>
                                                    <option value="Credit">Credit</option>
                                                </select>
                                            </div>
                                            @error('transaction') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="order_number" class="control-label">Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><i class="fa fa-money input-group-text" aria-hidden="true"></i></div>
                                                <input wire:model="amount" wire:keydown.enter="addItem()" class="form-control" placeholder="Enter Amount" type="text">
                                            </div>
                                            @error('amount') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="items" class="control-label">Items</label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm" id="items">
                                                    <thead>
                                                        <tr style="background-color: #f9f9f9;">
                                                            <th class="text-center">Bill No</th>
                                                            <th class="text-center">Head</th>
                                                            <th class="text-center">Debit</th>
                                                            <th class="text-center">Credit</th>
                                                            <th class="text-center">Description</th>
                                                            <th class="text-center">Delete</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($transaction_items as $index => $t)
                                                        <tr>
                                                            <td class="text-center">{{ $t['vno'] }}</td>
                                                            <td class="text-center">{{ $t['head'] }}</td>
                                                            <td class="text-center">{{ $t['debit'] }}</td>
                                                            <td class="text-center">{{ $t['credit'] }}</td>
                                                            <td class="text-center">{{ $t['description'] }}</td>
                                                            <td class="text-center"><button class="btn btn default">Delete</button></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <table class="table table-bordered table-sm" id="tblTotal">
                                                    <tr><td style="text-align:center;"><b>Total Debit</b></td><td id="totalDebit" style="text-align:center; width:10%;"></td><td style="text-align:center;"><b>Total Credit</b></td><td id="totalCredit" style="text-align:center; width:10%;"></td></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12  " style="min-height: 59px">
                                            <label for="attachment" class="control-label">Attachment</label>
                                            <input class="form-control" type="file" id="attachment" style="width: 327px; background: #e6e6e6;" wire:model="image"></div>
                                            @error('image') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="notes" class="control-label">Notes</label>
                                            <textarea class="form-control" placeholder="Enter Notes" rows="3" cols="50" wire:model="note"></textarea>
                                            @error('note') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-12 ">
                                            <button class="btn btn-success" wire:click="processToDatabase" type='submit' {{ count($transaction_items)==0?'disabled':'' }}>Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- The Modal -->
                            <div class="modal fade" id="addHead" tabindex="-1" aria-labelledby="addHeadLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Add Head</h4>
                                            <button type="button" class="close" wire:click="updatedHeads" data-dismiss="modal">&times;</button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            @livewire('accheads')
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="updatedHeads">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>