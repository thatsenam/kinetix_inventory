@extends('admin.pos.master')

@section('title', 'Sales Invoice')


@section('content')

    @php
        $ledger = 5;
        $pos = 5;
        $showStock = 0;
        $purchasePrice = 0;
        $preventSale = 0;
        $settings = \App\GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        if ($settings) {
            $showStock = $settings->product_stock;
            $purchasePrice = $settings->purchase_price;
            $preventSale = $settings->prevent_sale;

            if ($settings->print_opt == 1) {
                $ledger = 1;
            } else {
                $pos = 1;
            }
            $vat = $settings->vat;
            $scharge = $settings->scharge;
        }
    @endphp

    <?php $user_name = Auth::user()->name; ?>

    <div class="main-panel">
        <div class="content-wrapper">
            <section class="content">
                <h3 class="ml-2">Sales Invoice</h3>

                <div class="box-body">
                    <div class="row">
                        <div class="col-12" style="position: relative;">
                            <form action="{{ route('sales_invoice_save') }}" method="POST">
                                @csrf
                                <div class="card" style="min-height: 500px;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="row">
                                                    @if($warehouses->count()>1)
                                                        <div class="col-3">
                                                            <select name="warehouse_id" id="warehouse_id"
                                                                    class="form-control">
                                                                </option>
                                                                @foreach($warehouses as $warehouse)
                                                                    <option
                                                                        value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_phone" id="cust_phone"
                                                                       class="form-control" placeholder="Customer Phone"
                                                                       autocomplete="off">
                                                                <div id="cust_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                                                </div>

                                                                <input type="hidden" name="cust_id" id="cust_id"
                                                                       value="0"
                                                                       class="form-control">

                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_name" id="cust_name"
                                                                       class="form-control" placeholder="Customer Name">
                                                                <div id="memo_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 45px; left: 0; z-index: 999;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_address" id="cust_address"
                                                                       class="form-control"
                                                                       placeholder="Customer Address">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="warehouse_id" id="warehouse_id"
                                                               value="{{ $warehouse_id }}">
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_phone" id="cust_phone"
                                                                       class="form-control" placeholder="Customer Phone"
                                                                       autocomplete="off">
                                                                <div id="cust_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                                                </div>

                                                                <input type="hidden" name="cust_id" id="cust_id"
                                                                       value="0"
                                                                       class="form-control">

                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_name" id="cust_name"
                                                                       class="form-control" placeholder="Customer Name">
                                                                <div id="memo_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 45px; left: 0; z-index: 999;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_address" id="cust_address"
                                                                       class="form-control"
                                                                       placeholder="Customer Address">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-4" style="display: none">
                                                        <div class="form-group" style="position: relative;">
                                                            <input type="text" name="barcode" id="barcode"
                                                                   class="form-control"
                                                                   placeholder="Barcode" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group" style="position: relative;">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Search Product"
                                                                   id="search" autocomplete="off">
                                                            <div id="products_div"
                                                                 style="display: none; position: absolute; top: 30px; left: 0; width: 100%; z-index: 999;">
                                                            </div>
                                                            <input type="hidden" name="pid_hid" id="pid_hid">
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-------------------------------->


                                                <div id="printdiv"
                                                     style="margin:0 auto; font-family:Franklin Gothic Medium; ">


                                                    <table id="print_add"
                                                           style="width: 332px; margin: 0px auto; padding: 10px; text-align:left; display:none;">

                                                        <tr>
                                                            <td style='width:70%;'>

                                                <span id="company"
                                                      style='font-size:42px'>{{ $settings->site_name ?? '' }}</span><br/>

                                                                <span style='font-size:16px'
                                                                      id="company_add">{{ $settings->address ?? '' }}</span><br/>


                                                                <span style='font-size:14px'><b>{{ $settings->phone ?? '' }}
                                                                        {{ $settings->email ?? '' }}</b></span>
                                                            </td>

                                                            <td id="logoimage" style='width:30%; text-align:right;'>

                                                                <!--<img src='/images/logo_ccb.png' style='width:100px; height:auto;'>-->

                                                            </td>
                                                        </tr>
                                                    </table>


                                                    <table id="mid_section"
                                                           style="width: 332px; font-size:16px; display:none;">

                                                        <tr>
                                                            <td style="text-align:center; font-size:22px" colspan="2">
                                                                <b>INVOICE
                                                                    / BILL</b></td>
                                                        </tr>

                                                        <tr>
                                                            <td id="cust_add" style="width: 50%; padding-left:10px;">

                                                            </td>
                                                            <td id="others_info" style="text-align: right;">

                                                            </td>
                                                        </tr>

                                                    </table>


                                                    <div id="prodlistDiv" class="row"
                                                         style="height:350px; overflow-y: auto; ">
                                                        <div class="col-12" style="padding-right: 0 !important;">
                                                            <table id="prodlist" class="price-table custom-table"
                                                                   style="width: 100%">
                                                                <tr>
                                                                    <th>Item</th>
                                                                    <th>Sub-unit</th>
                                                                    <th>Unit</th>
                                                                    <th>Price</th>

                                                                    <th class="{{ $GenSettings->vat_type == \App\VatType::$GLOBAL_BASE?'d-none':'' }}">
                                                                        VAT
                                                                    </th>
                                                                    <th>Total</th>
                                                                    <th>Delete</th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <table id="bottom_section"
                                                           style="margin-top:40px; width: 94%; font-size:16px; display:none;">

                                                        <tr>
                                                            <td id="bottom_left" style="width:70%; padding-left:30px;">

                                                            </td>
                                                            <td id="bottom_right" style="width:30%;">

                                                            </td>
                                                        </tr>

                                                    </table>

                                                    <table id="footer_section"
                                                           style="margin-top:40px; width: 94%; font-size:16px; display:none;">

                                                        <tr>
                                                            <td id="footer1" style="text-align:left; padding:20px;">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td id="footer2"
                                                                style="text-align:right; padding-top:80px;">
                                                                <b>Authorized Signature & Company Stamp</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td id="footer3" style="text-align:center; padding:20px;">
                                                                Note: warranty voide if sticker removed item, burn case
                                                                and
                                                                physical damage of goods, warranty not cover mouse,
                                                                keyboard,
                                                                cable adopter and powe supply unit of casing.
                                                            </td>
                                                        </tr>

                                                    </table>

                                                </div>


                                                <!--------------------------------->


                                            </div>

                                            <div class="col-md-5">

                                                <div class="row">
                                                    @if($purchasePrice == 1)
                                                        <div class="col">
                                                            <div class="bg-warning text-center rounded h4"
                                                                 id="purchase_price_show"></div>
                                                        </div>
                                                    @endif
                                                    @if($showStock == 1)
                                                        <div class="col">
                                                            <div class="bg-info text-center rounded h4"
                                                                 id="product_stock"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="price" id="price"
                                                                   class="form-control"
                                                                   placeholder="Price">

                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="qnt" id="qnt" class="form-control"
                                                                   placeholder="Quantity">

                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="date" id="date"
                                                                   class="form-control"
                                                                   placeholder="date"
                                                                   value="<?php echo date('Y-m-d'); ?>"
                                                                   style="padding: 0.94rem 0.5rem;">

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Amount</label>
                                                            <input type="text" name="amount" id="amount"
                                                                   class="form-control bg-white" placeholder=""
                                                                   value="0" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group" style="margin-top:-10px;">
                                                            <label>Grand Total</label>
                                                            <input type="text" name="show_grand_total"
                                                                   id="show_grand_total"
                                                                   class="form-control bg-white" placeholder=""
                                                                   disabled>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6" style="display: none;">
                                                    <div class="form-group">
                                                        <label>Total</label>
                                                        <input type="text" name="total" id="total" class="form-control"
                                                               placeholder="" value="0">
                                                        <input type="hidden" name="hid_total" id="hid_total"
                                                               class="form-control" placeholder="" value="0">

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Discount</label>
                                                            <input type="text" name="discount" id="discount"
                                                                   class="form-control" placeholder="" value="0">

                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group" style="margin-top:-10px;">
                                                            <label>S. Charge</label>
                                                            <input type="text" name="scharge" id="scharge"
                                                                   class="form-control"
                                                                   placeholder="" value="{{ $scharge ?? 0 }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Total VAT</label>
                                                            <input type="text" name="total_vat" id="total_vat"
                                                                   class="form-control" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Payment</label>
                                                            <input type="text" name="payment" id="payment"
                                                                   class="form-control"
                                                                   placeholder="" value="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Due</label>
                                                            <input type="text" name="due" id="due"
                                                                   class="form-control"
                                                                   placeholder="Due" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Payment Type</label>
                                                            <select name="paytype" id="paytype" class="form-control"
                                                                    placeholder="" value="0">
                                                                <option value='cash'>Cash Payment</option>
                                                                <option value='card'>Card</option>
                                                                <option value='mobile'>Mobile</option>
                                                                <option value='check'>Check</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">


                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Sale By</label>
                                                            <input type="text" name="sby" id="sby" class="form-control"
                                                                   placeholder=""
                                                                   value="<?php echo $user_name; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <label>Remarks</label>
                                                            <input type="text" name="remarks" id="remarks"
                                                                   class="form-control"
                                                                   placeholder="" value="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">


                                                </div>
                                                <div class="row">
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div style="width: 80px; margin: 20px auto;">
                                                            <input type="button" class="btn btn-danger btn-lg"
                                                                   id="cancel"
                                                                   value="Cancel">
                                                        </div>
                                                    </div>
                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div style="width: 80px; margin: 20px auto;">
                                                            <input type="button" class="btn btn-success btn-lg"
                                                                   id="save"
                                                                   value="Save">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group">
                                                            <div style="width: 50px; margin: 0 auto;">
                                                                <input type="button" class="btn btn-primary btn-md"
                                                                       id="reprint"
                                                                       value="Reprint">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-6" style="margin-top:-10px;">
                                                        <div class="form-group" style="position: relative;">

                                                            <input type="text" name="rep_invoice" id="rep_invoice"
                                                                   class="form-control" placeholder="Enter Invoice No"
                                                                   style="display: none;">

                                                            <div id="rep_div"
                                                                 style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="square_foot_modal" tabindex="-1" role="dialog"
                                 aria-labelledby="square_foot_modalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title float-center" id="square_foot_modalLabel">
                                                Quantity - <span id="qty_type">  </span></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row">
                                                <label for="quantity" class="col-sm-4 col-form-label">Quantity </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="quantity">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">
                                                Cancel
                                            </button>
                                            {{-- <button type="button" class="btn btn-primary">OK</button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </section>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="serial_modal" tabindex="-1" role="dialog" aria-labelledby="serial_modalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title w-100" id="serial_modalLabel">Product Serial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <select id="serialInput" class="form-control" name="serials[]" multiple="multiple">
                                <option value="AL">Alabama</option>
                                <option value="WY">Wyoming</option>
                            </select>
                            <div id="serial_input">

                            </div>
                            <div class="float-right">
                                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                <button type="button" id="serial_save" class="btn btn-primary">SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->

    </div>

    <div id="check_info" class="custom-modal">

        <div class="card" style="width:500px; margin: 0 auto; position:relative;">
            <input type="button" class="btn btn-danger close-modal" value="X"
                   style="position: absolute; top:15px; right: 5px;">
            <div class="card-body">
                <h3 style="text-align: center;">Check Info</h3>

                <form action="" id="check_info_modal">
                    <table class=""
                           style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">
                        <tr>
                            <td><label style="padding:10px;">Client's Bank</label></td>
                            <td>
                                <input type="text" class="form-control" id="clients_bank" name="clients_bank">

                            </td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">Client's Bank Account</label></td>
                            <td><input type="text" class="form-control" id="clients_bank_acc" name="clients_bank_acc"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">Check No</label></td>
                            <td>
                                <input type="text" class="form-control" id="check_no" name="check_no">

                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Check Type</label></td>
                            <td>
                                <select class="form-control" id="check_type" name="check_type">
                                    <option value="pay_cash">Cash</option>
                                    <option value="pay_account">Account</option>
                                </select>
                            </td>
                        </tr>

                        <tr class="hide_tr" style="display: none;">
                            <td><label style="padding:10px;">Shop's Bank</label></td>
                            <td style="position: relative;">
                                {{--                            <input type="text" class="form-control" id="shops_bank" name="shops_bank">--}}
                                <select id="shops_bank"   name="shops_bank" class="form-control">
                                    @foreach($bank as $b)
                                        <option value="{{$b->acc_name}},{{$b->account->name?? "n/a"}}">{{$b->account->name?? " "}}({{$b->acc_name}})</option>
                                    @endforeach
                                </select>
                                <div id="shop_bank_div"
                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                </div>
                                <input type="hidden" id="bank_id" name="bank_id" value="0">
                            </td>
                        </tr>

                        <tr class="hide_tr" style="display: none;">
                            <td><label style="padding:10px;"></label></td>
                            <td style="position: relative;">
                                <input type="hidden" class="form-control" value="0" id="shops_bank_account"
                                       name="shops_bank_account">
                                <div id="shop_account_div"
                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                </div>
                                <input type="hidden" id="account_id" name="account_id" value="0">
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Amount</label></td>
                            <td><input type="text" class="form-control" id="check_amount" name="check_amount"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">Cash (Partial Payment)</label></td>
                            <td><input type="text" class="form-control" id="check_cash" name="check_cash"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">Check Date</label></td>
                            <td><input type="text" class="form-control" id="check_date" name="check_date"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">Remarks</label></td>
                            <td><input type="text" class="form-control" id="check_remarks" name="check_remarks"></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width:80px; margin-left: 20px;" class="btn btn-primary check_total"></div>
                            </td>
                            <td>
                                <div style="width:50px; margin: 20px auto;">
{{--                                    <input type="button" class="btn btn-success" id="check_ok" value="OK">--}}
                                    <input type="submit" value="ok" class="btn btn-success">
                                </div>
                            </td>
                        </tr>
                    </table>

                </form>
            </div>
        </div>
    </div>

    <div id="mobile_info" class="custom-modal">

        <div class="card" style="width:500px; margin: 0 auto; position:relative;">
            <input type="button" class="btn btn-danger close-modal" value="X"
                   style="position: absolute; top:15px; right: 5px;">
            <div class="card-body">
                <h3 style="text-align: center;">Mobile Banking Info</h3>

                <table class=""
                       style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">

                    <tr>
                        <td><label style="padding:10px;">Bank Name</label></td>
                        <td style="position: relative;"><input type="text" class="form-control" id="mobile_bank"
                                                               name="mobile_bank">
                            <div id="shop_mobile_div"
                                 style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                            </div>
                            <input type="hidden" id="mobile_bank_id" name="mobile_bank_id" value="0">
                        </td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Shop Number</label></td>
                        <td style="position: relative;"><input type="text" class="form-control" id="mobile_bank_account"
                                                               name="mobile_bank_account">
                            <div id="mobile_bank_acc_id_div"
                                 style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                            </div>
                            <input type="hidden" id="mobile_bank_acc_id" name="mobile_bank_acc_id" value="0">
                        </td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Csutomer's Number</label></td>
                        <td><input type="text" class="form-control" id="mobile_bank_acc_cust"
                                   name="mobile_bank_acc_cust">

                        </td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Amount</label></td>
                        <td><input type="text" class="form-control" id="mobile_amount" name="mobile_amount"></td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Cash (Partial Payment)</label></td>
                        <td><input type="text" class="form-control" id="mobile_cash" name="mobile_cash"></td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Tranx Id</label></td>
                        <td><input type="text" class="form-control" id="tranxid" name="tranxid"></td>
                    </tr>

                    <tr>
                        <td><label style="padding:10px;">Remarks</label></td>
                        <td><input type="text" class="form-control" id="mobile_remarks" name="mobile_remarks"></td>
                    </tr>

                    <tr>
                        <td>
                            <div style="width:80px; margin-left: 20px;" class="btn btn-primary mobile_total"></div>
                        </td>
                        <td>
                            <div style="width:50px; margin: 20px auto;"><input type="button"
                                                                               class="btn btn-success btn-lg"
                                                                               id="mobile_ok" value="OK"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    <!-------Card Information----------->

    <div id="card_info" class="custom-modal">

        <div class="card" style="width:500px; margin: 0 auto; position:relative;">
            <input type="button" class="btn btn-danger close-modal" value="X"
                   style="position: absolute; top:15px; right: 5px;">
            <div class="card-body">
                <h3 style="text-align: center;">Credit/ Debit Card Info</h3>

                <form action="" id="card_payment_modal">
                    <table class=""
                           style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">

                        <tr>
                            <td><label style="padding:10px;">Card Type</label></td>
                            <td>
                                <select class="form-control" id="card_type" name="card_type">
                                    <option value="visa">Visa Card</option>
                                    <option value="master">Masters Card</option>
                                    <option value="dbbl">DBBL Nexus Card</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Shop Bank <span style="color: red">*</span></label></td>
                            <td style="position: relative;"><input type="text" class="form-control" id="card_bank"
                                                                   name="card_bank">
                                <div id="shop_card_div"
                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                </div>
                                <input type="hidden" id="card_bank_id" name="card_bank_id" value="0">
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Shop Account <span style="color: red">*</span></label></td>
                            <td style="position: relative;"><input type="text" class="form-control"
                                                                   id="card_bank_account"
                                                                   name="card_bank_account">
                                <div id="card_bank_acc_id_div"
                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;">
                                </div>
                                <input type="hidden" id="card_bank_acc_id" name="card_bank_acc_id" value="0">
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Amount</label></td>
                            <td><input type="text" class="form-control" id="card_amount" name="card_amount"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Cash (Partial Payment)</label></td>
                            <td><input type="text" class="form-control" id="card_cash" name="card_cash"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">Remarks</label></td>
                            <td><input type="text" class="form-control" id="card_remarks" name="card_remarks"></td>
                        </tr>

                        <tr>
                            <td>
                                <div style="width:80px; margin-left: 20px;" class="btn btn-primary card_total"></div>
                            </td>
                            <td>
                                <div style="width:50px; margin: 20px auto;">
{{--                                    <input type="button" class="btn btn-success btn-lg" id="card_ok" value="OK">--}}
                                    <input type="submit" class="btn btn-success btn-lg" value="ok">
                                </div>
                            </td>
                        </tr>
                    </table>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('page-js-script')


    <script type="text/javascript">

        // $(document).ready(function()
        // {
        //     $("#clients_bank").change(function()
        //     {
        //         var clients_bank=$(this).val();
        //         // var post_id = 'id='+ country_id;
        //         alert(clients_bank);
        //         $.ajax
        //         ({
        //             type: "POST",
        //             url: "ajax.php",
        //             data: post_id,
        //             cache: false,
        //             success: function(cities)
        //             {
        //                 $(".city").html(cities);
        //             }
        //         });
        //
        //     });
        // });

        $(document).ready(function () {
            $("#check_info_modal").submit(function (e) {
                event.preventDefault();
                var check_amount = $('#check_amount').val();
                var check_date = $('#check_date').val();


                if (check_date == "") {
                    alert('Date Can Not Empty')
                }
                if (check_amount == "") {
                    alert('Amount Can Not Empty')
                }else{
                    $('#check_info').hide();
                }



            });


            $("#card_payment_modal").submit(function (e) {
                event.preventDefault();
                var shop_bank = $('#card_bank').val();
                var card_bank_acc_id = $('#card_bank_account').val();



                if (shop_bank == 0) {
                    alert('Bank Name Can Not Empty')
                }
                if (card_bank_acc_id == 0) {
                    alert('Bank Account Can Not Empty')
                }
                else{
                    $('#card_info').hide();
                }

            });
        });

        var per_box_qty;
        var sub_unit;
        var unit;
        var box = 0;
        var fraction = 0;
        var product_id;
        var product_serial;
        var serial_qty;
        var serial_array = {};
        var serial_unsold;
        var warranty;
        var product_stock;
        var product_vat;
        var vat_type = '{{ $GenSettings->vat_type }}'

        $(document).ready(function () {
            $("#payment").change(function () {
                var total = $('#show_grand_total').val();
                var payment = $('#payment').val();

                var due = total - payment
                $("#due").val(due);


            });

            $("#discount").change(function () {
                var total = $('#show_grand_total').val();
                var payment = $('#payment').val();

                var due = total - payment
                $("#due").val(due);


            });
        });

        $(document).ready(function () {

            $("#price").keyup(function (e) {
                if (e.which == 13) {

                    $('#qnt').trigger(e);
                    // $('#qnt').trigger(jQuery.Event('keypress', { keycode: 13 }));
                    var total = $('#show_grand_total').val();
                    $("#due").val(total);
                }
            });


            $('#date').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            $('#check_date').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            $('#check_date').click(function () {

                $('#ui-datepicker-div').css('top', '400px');
            });

            $("#cust_phone").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#cust_phone").blur();

                    $('.customer-list').find("li:first").focus().addClass('active').siblings()
                        .removeClass();

                    $('.customer-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.customer-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.customer-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.customer-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var phone = $(this).find(".active").attr("data-phone");
                            var name = $(this).find(".active").attr("data-name");
                            var address = $(this).find(".active").attr("data-address");
                            var id = $(this).find(".active").attr("data-id");

                            $('#cust_phone').val(phone);
                            $('#cust_name').val(name);
                            $('#cust_address').val(address);
                            $('#cust_id').val(id);

                            $("#search").focus();
                            $("#cust_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_customer') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {

                        $('#cust_div').show();
                        $('#cust_div').html(ts.responseText);
                        //alert((ts.responseText));
                    },
                    success: function (data) {

                        $('#cust_div').show();
                        $('#cust_div').html(ts.responseText);
                        //alert(data);

                    }

                });

            });


            $("#barcode").keypress(function (e) {
                if (e.which == 13) {
                    var s_text = $(this).val();
                    var formData = new FormData();
                    formData.append('s_text', s_text);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ URL::route('get_barcode') }}",
                        method: 'post',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        error: function (ts) {
                            // alert(ts.responseText);
                        },
                        success: function (data) {
                            var obj = data;

                            var name = obj.name;
                            var id = obj.id;
                            var price = obj.price;
                            product_id = id;
                            product_serial = obj.serial;
                            warranty = obj.warranty;
                            product_stock = obj.stock;

                            $('#product_stock').html(product_stock);
                            $('#search').val(name);
                            $('#pid_hid').val(id);
                            $('#price').val(price);
                            $("#price").focus();
                        }

                    });
                }
            });

            $('#quantity').on("keyup", function (e) {
                if (e.which == 13) {
                    var qty_box = $('#quantity').val();
                    var total_kg = per_box_qty * qty_box;
                    box = qty_box;
                    fraction = 0;
                    $('#qnt').val(total_kg);
                    $('#quantity').val('');
                    $('#square_foot_modal').modal('hide');
                    $('#price').focus();
                }
            });

            $("#search").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#search").blur();

                    $('.products-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.products-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.products-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.products-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.products-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var id = $(this).find(".active").attr("data-id");
                            var name = $(this).find(".active").attr("data-name");
                            var price = $(this).find(".active").attr("data-price");
                            var pbq = $(this).find(".active").attr("data-pbq");
                            var pur_price = $(this).find(".active").attr("data-pur");
                            sub_unit = $(this).find(".active").attr("data-sub_unit");
                            unit = $(this).find(".active").attr("data-unit");

                            console.log(pbq)
                            if (pbq) {
                                $('#search').val(name);
                                $('#square_foot_modal').modal('toggle');
                                if (!sub_unit) {
                                    $('#qty_type').text(unit)

                                } else {
                                    $('#qty_type').text(sub_unit)

                                }

                                per_box_qty = pbq;
                                box = 0;
                                $('#square_foot_modal').on('shown.bs.modal', function () {
                                    $('#quantity').trigger('focus')
                                });
                            } else {
                                $('#search').val(name);
                                per_box_qty = 0;
                                box = 0;
                                $('#qnt').val('');
                            }

                            product_id = id;
                            product_serial = $(this).find(".active").attr("data-serial");
                            warranty = $(this).find(".active").attr("data-warranty");
                            product_stock = $(this).find(".active").attr("data-stock");
                            product_vat = $(this).find(".active").attr("data-vat");

                            $('#product_stock').html(product_stock);
                            $('#search').val(name);
                            $('#pid_hid').val(id);

                            $('#purchase_price_show').html(pur_price);

                            $('#price').val(price);

                            $("#price").focus();
                            $("#products_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_products') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        console.log(ts);
                        $('#products_div').show();
                        $('#products_div').html(ts.responseText);
                        //alert((ts.responseText));
                    },
                    success: function (data) {
                        console.log(data);

                        $('#products_div').show();
                        $('#products_div').html(ts.responseText);
                        //alert(data);

                    }

                });

            });

            $("#shops_bank").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#shops_bank").blur();

                    $('.bank-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.bank-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-list').scrollTop($this.index() * $this.outerHeight());
                    });
                    $('.bank-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });
                    $('.bank-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#shops_bank').val(name);
                            $('#bank_id').val(id);

                            $(function () {
                                $("#shops_bank_account").focus();
                            });

                            $("#shop_bank_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#shop_bank_div').show();
                        $('#shop_bank_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#shop_bank_div').show();
                        $('#shop_bank_div').html(data);


                    }

                });

            });


            $("#shops_bank_account").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#shops_bank_account").blur();

                    $('.bank-acc-list').find("li:first").focus().addClass('active').siblings()
                        .removeClass();

                    $('.bank-acc-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-acc-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.bank-acc-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.bank-acc-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#shops_bank_account').val(name);
                            $('#account_id').val(id);

                            $("#check_amount").focus();
                            $("#shop_account_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();
                var bank_id = $('#bank_id').val();

                var formData = new FormData();
                formData.append('s_text', s_text);
                formData.append('bank_id', bank_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_account') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#shop_account_div').show();
                        $('#shop_account_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#shop_account_div').show();
                        $('#shop_account_div').html(data);


                    }

                });

            });


            $("#mobile_bank").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#mobile_bank").blur();

                    $('.bank-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.bank-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-list').scrollTop($this.index() * $this.outerHeight());
                    });

                    $('.bank-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.bank-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#mobile_bank').val(name);
                            $('#mobile_bank_id').val(id);

                            $("#mobile_bank_account").focus();
                            $("#shop_mobile_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#shop_mobile_div').show();
                        $('#shop_mobile_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#shop_mobile_div').show();
                        $('#shop_mobile_div').html(data);

                    }

                });

            });


            $("#mobile_bank_account").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#mobile_bank_account").blur();

                    $('.bank-acc-list').find("li:first").focus().addClass('active').siblings()
                        .removeClass();

                    $('.bank-acc-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-acc-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.bank-acc-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.bank-acc-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#mobile_bank_account').val(name);
                            $('#mobile_bank_acc_id').val(id);

                            $("#mobile_bank_acc_cust").focus();
                            $("#mobile_bank_acc_id_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();
                var bank_id = $('#mobile_bank_id').val();

                var formData = new FormData();
                formData.append('s_text', s_text);
                formData.append('bank_id', bank_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_account') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#mobile_bank_acc_id_div').show();
                        $('#mobile_bank_acc_id_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#mobile_bank_acc_id_div').show();
                        $('#mobile_bank_acc_id_div').html(data);


                    }

                });

            });


            $("#card_bank").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#card_bank").blur();

                    $('.bank-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.bank-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-list').scrollTop($this.index() * $this.outerHeight());
                    });

                    $('.bank-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.bank-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#card_bank').val(name);
                            $('#card_bank_id').val(id);

                            $("#card_bank_account").focus();
                            $("#shop_card_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#shop_card_div').show();
                        $('#shop_card_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#shop_card_div').show();
                        $('#shop_card_div').html(data);

                    }

                });

            });


            $("#card_bank_account").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#card_bank_account").blur();

                    $('.bank-acc-list').find("li:first").focus().addClass('active').siblings()
                        .removeClass();

                    $('.bank-acc-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.bank-acc-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.bank-acc-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.bank-acc-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#card_bank_account').val(name);
                            $('#card_bank_acc_id').val(id);

                            $('#card_amount').focus();

                            $("#card_bank_acc_id_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

                        }
                    });

                    return false;
                }

                var s_text = $(this).val();
                var bank_id = $('#card_bank_id').val();

                var formData = new FormData();
                formData.append('s_text', s_text);
                formData.append('bank_id', bank_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_account') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {
                        //alert((ts.responseText));
                        $('#card_bank_acc_id_div').show();
                        $('#card_bank_acc_id_div').html(ts.responseText);

                    },
                    success: function (data) {
                        //alert(data);
                        $('#card_bank_acc_id_div').show();
                        $('#card_bank_acc_id_div').html(data);

                    }

                });
            });

            $('#serial_save').click(function (e) {
                var i, val = [];
                var qnt = $('#qnt').val();
                var access = 0;
                let selectedSerials = $('#serialInput').val();
                console.log(selectedSerials)
                if (serial_qty != selectedSerials.length) {
                    alert('Please Select Serial Number of : ' + serial_qty);
                    return;

                }

                serial_array[product_id] = selectedSerials;

                var result = selectedSerials.join();
                result = '<br>Serial: ' + result;
                if (warranty) {
                    result = result + '<br>Warranty: ' + warranty + ' Month';
                }

                var tdval = $(".price-table tr").find(`[data-prodid='${product_id}']`);
                tdval.append(result);
                $('#serial_modal').modal('hide');

                console.log(JSON.stringify(serial_array));
            });


            $('#qnt').on('keyup', function (e) {

                e.preventDefault();

                if (e.which == 13) {

                    var id = $('#pid_hid').val();
                    var name = $('#search').val();
                    var qnt = Number($(this).val());
                    var price = Number($('#price').val());
                    var totalPrice = Number($('#hid_total').val());
                    var pp = box;
                    var warehouse_id = $('#warehouse_id').val();
                    if (warehouse_id == null) {
                        alert('Please Select Warehouse');
                        return;
                    }

                    var show = {!! json_encode($preventSale) !!};
                    if (show == 1) {
                        if (qnt > product_stock) {
                            alert('Insufficient Stock');
                            return;
                        }
                    }

                    serial_qty = qnt;

                    if (product_serial == 1) {


                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "/get_serial/" + product_id,
                            method: 'get',
                            data: '',
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            beforeSend: function () {
                            },
                            error: function (ts) {
                            },
                            success: function (response) {
                                // var obj = JSON.parse(JSON.stringify(response));
                                serial_unsold = response;
                                console.log(serial_unsold);

                                $('#serial_modal').modal('toggle');

                                $('#serial_modal').on('shown.bs.modal', function () {
                                    $('#serial-' + 0).trigger('focus')
                                });

                                $('#serial_modal').on('hidden.bs.modal', function () {

                                    var inputs = $(".serialfield");
                                    let selectedSerials = $('#serialInput').val();
                                    if (Number(qnt) != selectedSerials.length) {
                                        var tdval = $(".price-table tr").find(`[data-prodid='${id}']`);
                                        tdval.parent().find('.delete').click();


                                    }

                                });

                                $('#serialInput').empty();
                                $('#serialInput').select2({placeholder: "Please Select Serial Number"});

                                console.log(serial_unsold.length)
                                for (let i = 0; i < serial_unsold.length; i++) {
                                    var data = {
                                        id: serial_unsold[i],
                                        text: serial_unsold[i]
                                    };

                                    var newOption = new Option(data.text, data.id, false, false);
                                    $('#serialInput').append(newOption).trigger('change');
                                }


                            }
                        });
                    }

                    if (id == '') {
                        alert("Invalid product. Please select product or add product before proceed!!");
                        return false;
                    }

                    if (name == '' || qnt == '' || price == '') {

                        alert("Please Fillup All Fields ");
                        return false;
                    }

                    if (sub_unit) {
                        if (pp == 0) {
                            price_per_kg = price;
                            totalPrice = (qnt * price);

                            var box_qty = parseInt(qnt / per_box_qty);
                            var fraction = qnt - (box_qty * per_box_qty);
                            if (fraction != 0) {
                                var box_value = box_qty + " " + sub_unit + " " + fraction + unit;
                            } else {
                                var box_value = box_qty + " " + sub_unit;
                            }
                        } else {
                            var box_qty = parseInt(qnt / per_box_qty);
                            var fraction = qnt - (box_qty * per_box_qty);
                            price_per_kg = price / per_box_qty;
                            // alert(per_box_qty)
                            console.log(price_per_kg)

                            if (fraction != 0) {
                                var price_per_kg = price / per_box_qty;
                                totalPrice = (box_qty * price) + (price_per_kg * fraction);
                                var box_value = box_qty + " " + sub_unit + " " + fraction + unit;

                            } else {
                                totalPrice = (box_qty * price);
                                var box_value = box_qty + " " + sub_unit;

                            }
                        }

                    } else {
                        totalPrice = (qnt * price);
                        var box_value = qnt + " " + unit;
                        price_per_kg = price / per_box_qty;
                    }


                    add_product_to_table(id, name, qnt, price, totalPrice, 0, 0, price_per_kg, box_value);

                }

            });

            $("#scharge").on("change keyup paste", function () {

                // var hid_total = Number($('#hid_total').val());

                var discount = Number($("#discount").val());
                var vat = Number($("#total_vat").val());

                var scharge = Number($(this).val());
                var amount = Number($('#amount').val());

                $('#show_grand_total').val(amount + vat + scharge - discount);

            });

            $("#vat_percent").on("change keyup paste", function () {

                var discount = Number($('#discount').val());
                var scharge = Number($('#scharge').val());
                var amount = Number($('#amount').val());

                var vat_percent = Number($('#vat_percent').val());
                var vat_amount = Number($('#vat_amount').val());

                var total_vat = amount * (vat_percent / 100);
                $('#vat_amount').val(total_vat);
                $('#show_grand_total').val(amount + total_vat + scharge - discount);
            });

            $("#vat_amount").on("change keyup paste", function () {

                var discount = Number($('#discount').val());
                var scharge = Number($('#scharge').val());
                var amount = Number($('#amount').val());

                var vat_percent = Number($('#vat_percent').val());
                var vat_amount = Number($('#vat_amount').val());

                var amt_to_vat = (vat_amount / amount) * 100;
                $('#vat_percent').val(amt_to_vat);
                $('#show_grand_total').val(amount + vat_amount + scharge - discount);

            });

            $("#discount").on("change keyup paste", function () {

                var discount = Number($(this).val());
                var scharge = Number($('#scharge').val());
                var vat = Number($('#total_vat').val());
                var amount = Number($('#amount').val());

                $('#show_grand_total').val(amount + vat + scharge - discount);

            });

            $("#payment").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());

                var scharge = Number($('#scharge').val());

                var payment = Number($(this).val());

                $('#total').val(hid_total - payment - discount + scharge);

            });

            $("#check_amount").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());
                var check_cash = Number($('#check_cash').val());
                var scharge = Number($('#scharge').val());
                var check_amount = Number($(this).val());

                $('#total').val(hid_total - check_amount - discount + scharge);
                $('#payment').val(check_amount + check_cash);

            });

            $("#mobile_amount").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());
                var mobile_cash = Number($('#mobile_cash').val());
                var scharge = Number($('#scharge').val());

                var mobile_amount = Number($(this).val());

                $('#total').val(hid_total - mobile_amount - discount + scharge);
                $('#payment').val(mobile_amount + mobile_cash);

            });

            $("#card_amount").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());

                var scharge = Number($('#scharge').val());

                var card_amount = Number($(this).val());

                $('#total').val(hid_total - card_amount - discount + scharge);

            });

            $("#mobile_cash").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());

                var scharge = Number($('#scharge').val());

                var mobile_amount = Number($('#mobile_amount').val());

                var mobile_cash = Number($(this).val());

                var total_tk = (mobile_amount + mobile_cash);

                $('#total').val(hid_total - total_tk - discount + scharge);
                $('#payment').val(total_tk);

            });

            $("#card_cash").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());

                var discount = Number($('#discount').val());

                var scharge = Number($('#scharge').val());

                var card_amount = Number($('#card_amount').val());

                var card_cash = Number($(this).val());

                var total_tk = (card_amount + card_cash);

                $('#total').val(hid_total - total_tk - discount + scharge);

            });

            $("#check_cash").on("change keyup paste", function () {

                var hid_total = Number($('#hid_total').val());
                var discount = Number($('#discount').val());
                var scharge = Number($('#scharge').val());
                var check_amount = Number($('#check_amount').val());
                var check_cash = Number($(this).val());
                var total_tk = (check_amount + check_cash);

                $('#total').val(hid_total - total_tk - discount + scharge);

                $('#payment').val(total_tk);

            });

            $('#cancel').click(function () {

                $('#cust_phone').val("");
                $('#cust_name').val("");
                $('#cust_address').val("");
                $('#cust_id').val("0");
                $('#vat').val("0");
                $('#disc').val("0");
                $('#scharge').val("0");
                $('#discount').val("0");
                $('#amount').val("0")
                $('#total').val("0");
                $('#payment').val("0");
                $('#paytype').val("cash");
                $('#hid_total').val("0");
                $('#total_vat').val("0");

                $('#card_bank').val("");
                $('#card_bank_id').val("");
                $('#card_bank_account').val("");
                $('#card_bank_acc_id').val("0");
                $('#card_type').val("visa");
                $('#card_amount').val("");
                $('#card_cash').val("");
                $('#tranxid').val("");
                $('#card_remarks').val("");

                $('#mobile_bank').val("");
                $('#mobile_bank_id').val("");
                $('#mobile_bank_account').val("");
                $('#mobile_bank_acc_id').val("0");
                $('#mobile_bank_acc_cust').val("");
                $('#mobile_amount').val("");
                $('#mobile_cash').val("");
                $('#tranxid').val("");
                $('#mobile_remarks').val("");


                $('#clients_bank').val("");
                $('#clients_bank_acc').val("");
                $('#check_no').val("");
                $('#check_type').val("pay_cash");
                $('#shops_bank').val("");
                $('#bank_id').val("0");
                $('#shops_bank_account').val("");
                $('#account_id').val("0");
                $('#check_amount').val("");
                $('#check_cash').val("");
                $('#check_date').val("");
                $('#check_remarks').val("");

                location.reload();

            });

            $('#paytype').change(function () {

                if ($(this).val() == 'check') {

                    $('#check_info').show();

                    $('.check_total').html($('#show_grand_total').val());
                }

            });

            $('#paytype').change(function () {

                if ($(this).val() == 'mobile') {

                    $('#mobile_info').show();

                    $('.mobile_total').html($('#show_grand_total').val());
                }

            });

            $('#paytype').change(function () {

                if ($(this).val() == 'card') {

                    $('#card_info').show();

                    $('.card_total').html($('#show_grand_total').val());
                }

            });

            $('#check_type').change(function () {

                if ($(this).val() == 'pay_account') {

                    $('.hide_tr').show();

                    $('#shops_bank').focus();
                }

                if ($(this).val() == 'pay_cash') {

                    $('.hide_tr').hide();
                }

            });


            $('#check_ok').click(function () {

                $('#check_info').hide();

            });

            $('#mobile_ok').click(function () {
                $('#mobile_info').hide();
            });

            $('#card_ok').click(function () {
                $('#card_info').hide();

            });

            $('.close-modal').click(function () {
                $('.custom-modal').hide();
            });

            //////Save///////////
            $('input[name="qnt"]').focus(function () {
                $("#save").removeAttr('disabled');
            });

            $('#save').click(function (e) {

                if ($('#paytype').val() == 'check') {

                    if ($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $(
                        '#check_amount').val() == '' || $('#check_date').val() == '') {
                        alert("Please Fillup All Check Information");
                        return false;
                    }

                    if ($('#check_type').val() == 'pay_account') {

                        if ($('#shops_bank').val() == '' || $('#shops_bank_account').val() == '') {
                            alert("Please Fillup All Check Information");
                            return false;
                        }
                    }

                }

                if ($('#paytype').val() == 'mobile') {

                    if ($('#mobile_bank').val() == '' || $('#mobile_bank_account').val() == '' || $(
                        '#mobile_bank_acc_cust').val() == '' || $('#mobile_amount').val() == '') {
                        alert("Please Fillup All Mobile Information");
                        return false;
                    }
                }

                var show_grand_total = $('#show_grand_total').val();
                var payment = $('#payment').val();
                cust_name = $('#cust_name').val();
                cust_phone = $('#cust_phone').val();
                var warehouse_id = $('#warehouse_id').val();

                if (warehouse_id == null) {
                    alert('Please Select Warehouse');
                    return;
                }

                if (payment < show_grand_total) {
                    if (cust_name == '' || cust_phone == '') {
                        alert('Customer Name & Phone Required.');
                        return;
                    }
                }

                var i = 0;

                var cartData = [];

                $(this).attr('disabled', true);

                $('.price-table tr td').each(function () {

                    var take_data = $(this).html();

                    if ($(this).attr('data-prodid') != '') {
                        prodid = $(this).attr('data-prodid');
                        cartData.push(prodid);
                    }

                    if ($(this).attr("class") == 'qnty') {
                        quantity = $(this).html();
                        cartData.push(quantity);
                    }

                    if ($(this).attr("class") == 'uprice') {
                        uprice = $(this).html();
                        cartData.push(uprice);
                    }

                    if ($(this).attr("class") == 'totalPriceTd') {
                        totalPriceTd = $(this).html();
                        cartData.push(totalPriceTd);
                    }

                    if ($(this).attr("class") == 'prod_vat') {
                        prod_vat = $(this).html();
                        cartData.push(prod_vat);
                    }

                    if ($(this).attr("class") == 'box') {
                        var box_item = $(this).html();
                        cartData.push(box_item);
                    }

                    i = i + 1;
                });

                cartData = cartData.filter(item => item);

                if (i < 5) {
                    alert("Please make a product list.");
                    $(this).attr('disabled', true);
                    return false;
                }

                var fieldValues = {};

                fieldValues.warehouse_id = $('#warehouse_id').val();
                fieldValues.cust_id = $('#cust_id').val();
                fieldValues.cust_name = $('#cust_name').val();
                fieldValues.cust_phone = $('#cust_phone').val();
                fieldValues.cust_address = $('#cust_address').val();
                fieldValues.vat = $('#total_vat').val();
                fieldValues.scharge = $('#scharge').val();
                fieldValues.discount = $('#discount').val();
                fieldValues.amount = $('#amount').val();
                fieldValues.hid_total = $('#hid_total').val();
                fieldValues.total = $('#total').val();
                fieldValues.paytype = $('#paytype').val();
                fieldValues.payment = $('#payment').val();
                fieldValues.remarks = $('#remarks').val();
                fieldValues.date = $('#date').val();


                fieldValues.clients_bank = $('#clients_bank').val();
                fieldValues.clients_bank_acc = $('#clients_bank_acc').val();
                fieldValues.check_amount = $('#check_amount').val();
                fieldValues.check_cash = $('#check_cash').val();
                fieldValues.check_date = $('#check_date').val();
                fieldValues.check_type = $('#check_type').val();
                fieldValues.shops_bank = $('#shops_bank').val();
                fieldValues.bank_id = $('#bank_id').val();
                fieldValues.shops_bank_account = $('#shops_bank_account').val();
                fieldValues.account_id = $('#account_id').val();
                fieldValues.check_remarks = $('#check_remarks').val();

                fieldValues.card_type = $('#card_type').val();
                fieldValues.card_bank = $('#card_bank').val();
                fieldValues.card_bank_id = $('#card_bank_id').val();
                fieldValues.card_bank_account = $('#card_bank_account').val();
                fieldValues.card_bank_acc_id = $('#card_bank_acc_id').val();
                fieldValues.card_amount = $('#card_amount').val();
                fieldValues.card_cash = $('#card_cash').val();
                fieldValues.card_remarks = $('#card_remarks').val();

                fieldValues.mobile_bank = $('#mobile_bank').val();
                fieldValues.mobile_bank_id = $('#mobile_bank_id').val();
                fieldValues.mobile_bank_acc_cust = $('#mobile_bank_acc_cust').val();
                fieldValues.mobile_bank_account = $('#mobile_bank_account').val();
                fieldValues.mobile_bank_acc_id = $('#mobile_bank_acc_id').val();
                fieldValues.mobile_amount = $('#mobile_amount').val();
                fieldValues.mobile_cash = $('#mobile_cash').val();
                fieldValues.tranxid = $('#tranxid').val();
                fieldValues.mobile_remarks = $('#mobile_remarks').val();

                var formData = new FormData();

                formData.append('fieldValues', JSON.stringify(fieldValues));
                formData.append('cartData', JSON.stringify(cartData));
                formData.append('serialArray', JSON.stringify(serial_array));

                product_id = '';
                product_serial = '';
                serial_qty = '';
                serial_array = {};
                serial_unsold = '';
                warranty = '';
                product_stock = '';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('sales_invoice_save') }}",
                    method: 'post',
                    data: formData,
                    //data: cartData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        $("#wait").show();
                    },
                    error: function (ts) {
                        window.location.href = "/dashboard/sales_invoicemain/" + ts.responseText;

                        // alert(ts.responseText);
                        //
                        // var invoice = ts.responseText;
                        //
                        // printPos(invoice);
                        // $('#cust_phone').val("");
                        // $('#cust_name').val("");
                        // $('#cust_id').val("0");
                        // $('#vat').val("0");
                        // $('#disc').val("0");
                        // $('#scharge').val("0");
                        // $('#discount').val("0");
                        // $('#amount').val("0")
                        // $('#total').val("0");
                        // $('#payment').val("0");
                        // $('#paytype').val("cash");
                        // $('#hid_total').val("0");
                        // $('#total_vat').val("0");
                        //
                        // $('#card_bank').val("");
                        // $('#card_bank_id').val("");
                        // $('#card_bank_account').val("");
                        // $('#card_bank_acc_id').val("0");
                        // $('#card_type').val("visa");
                        // $('#card_amount').val("");
                        // $('#card_cash').val("");
                        // $('#card_remarks').val("");
                        //
                        // $('#mobile_bank').val("");
                        // $('#mobile_bank_id').val("");
                        // $('#mobile_bank_account').val("");
                        // $('#mobile_bank_acc_id').val("0");
                        // $('#mobile_bank_acc_cust').val("");
                        // $('#mobile_amount').val("");
                        // $('#mobile_cash').val("");
                        // $('#tranxid').val("");
                        // $('#mobile_remarks').val("");
                        //
                        //
                        // $('#clients_bank').val("");
                        // $('#clients_bank_acc').val("");
                        // $('#check_no').val("");
                        // $('#check_type').val("pay_cash");
                        // $('#shops_bank').val("");
                        // $('#bank_id').val("0");
                        // $('#shops_bank_account').val("");
                        // $('#account_id').val("0");
                        // $('#check_amount').val("");
                        // $('#check_cash').val("");
                        // $('#check_date').val("");
                        // $('#check_remarks').val("");
                        //
                        //
                        // $('.price-table td').remove();
                        //
                        // $("#wait").hide();
                        //
                        // $('#save').attr('disabled', false);
                        //
                        // location.reload();


                    },
                    success: function (data) {

                        alert(data);

                    }
                });


                e.preventDefault();
            });

            $('body').on('click', '.delete', function (e) {

                var totalPriceTd = Number($(this).closest('tr').find('.totalPriceTd').html());
                var productID = Number($(this).closest('tr').find("td").attr('data-prodid'));
                var prod_vat = Number($(this).closest('tr').find('.prod_vat').html());


                delete serial_array[productID];

                var amount = $('#amount').val();
                var total_vat = Number($('#total_vat').val());
                var scharge = Number($('#scharge').val());
                var discount = Number($('#discount').val());
                var scharge = Number($('#scharge').val());

                var grandTotalPrice = Number(amount - totalPriceTd);
                $('#total_vat').val(Number(total_vat - prod_vat));

                if (vat_type === 'Global Base') {
                    globalVat = parseFloat('{{ $GenSettings->vat??0 }}')
                    vatAmount = (globalVat / 100) * grandTotalPrice;
                    $('#total_vat').val(Number(vatAmount));

                }

                $('#show_grand_total').val(grandTotalPrice + scharge - discount);
                $('#amount').val(grandTotalPrice);
                $(this).closest('tr').remove();

            });


            // Query For Reprint Invoice

            $('#reprint').click(function () {
                $("#rep_invoice").show();
            });

            $("#rep_invoice").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#rep_invoice").blur();

                    $('.invoice-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.invoice-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.invoice-list').scrollTop($this.index() * $this
                            .outerHeight());
                    });

                    $('.invoice-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.invoice-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var invoice = $(this).find(".active").attr("data-invoice");

                            $('#rep_invoice').val(invoice);

                            $("#rep_invoice").focus();
                            $("#rep_div").hide();

                            //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);
                        }
                    });

                    return false;
                }

                var s_text = $(this).val();

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_invoice') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {

                        $('#rep_div').show();
                        $('#rep_div').html(ts.responseText);
                        //alert((ts.responseText));
                    },
                    success: function (data) {

                        $('#rep_div').show();
                        $('#rep_div').html(ts.responseText);
                        //alert(data);

                    }

                });

            });


            $("#rep_invoice").keypress(function (e) {

                if (e.which == 13) {

                    var s_text = $(this).val();

                    var formData = new FormData();
                    formData.append('s_text', s_text);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ URL::route('get_invoice_details') }}",
                        method: 'post',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        error: function (ts) {


                        },
                        success: function (data) {

                            var obj = JSON.parse(JSON.stringify(data));

                            var invoice = obj.invoice;
                            var trow = obj.trow;
                            var company = obj.company;
                            var company_add = obj.company_add;
                            var tcname = obj.cust_name;
                            var tcphone = obj.cust_phone;
                            var tcaddress = obj.cust_address;
                            var amount = obj.amount;
                            var vat = obj.vat;
                            var scharge = obj.scharge;
                            var discount = obj.discount;
                            var gtotal = obj.gtotal;
                            var payment = obj.payment;
                            var due = obj.due;
                            var date = obj.date;

                            rePrintPos(invoice, company, company_add, tcname, tcphone, tcaddress, trow,
                                amount, vat, scharge, discount, gtotal, payment, due, date);

                            location.reload();
                        }

                    });
                }
            });


            $('body').on('click', function () {

                $('#products_div').hide();
                $('#cust_div').hide();

            });
        });


        function selectCustomer(id, phone, name, address) {

            $('#cust_phone').val(phone);
            $('#cust_name').val(name);
            $('#cust_address').val(address);
            $('#cust_id').val(id);

            $("#search").focus();
            $("#cust_div").hide();
        }

        function selectProducts(id, name, price, serial, warranty, stock, vat, pbq, su, u, pur_price) {
            sub_unit = su;
            unit = u;
            if (pbq) {
                $('#search').val(name);
                $('#square_foot_modal').modal('toggle');

                per_box_qty = pbq;
                box = 0;

                $('#square_foot_modal').on('shown.bs.modal', function () {
                    $('#quantity').trigger('focus')
                });
            } else {
                $('#search').val(name);
                per_box_qty = 0;
                box = 0;
                $('#qnt').val('');
            }

            $('#pid_hid').val(id);

            // var show = {!! json_encode($purchasePrice) !!};

            $('#purchase_price_show').html(pur_price);

            $('#price').val(price);

            product_id = id;
            product_serial = serial;
            warranty = warranty;
            product_stock = stock;
            product_vat = vat;

            $('#product_stock').html(product_stock);
            $("#price").focus();
            $("#products_div").hide();

        }

        function selectInvoice(invoice) {

            $('#rep_invoice').val(invoice);

            $("#rep_invoice").focus();
            $("#rep_div").hide();

        }

        function selectBank(id, name) {

            $('#shops_bank').val(name);
            $('#bank_id').val(id);

            $(function () {
                $("#shops_bank_account").focus();
            });


            $("#shop_bank_div").hide();

        }

        function selectAccount(id, name) {

            $('#shops_bank_account').val(name);
            $('#account_id').val(id);

            $("#check_amount").focus();
            $("#shop_account_div").hide();
        }

        function add_product_to_table(id, name, qnt, price, totalPrice, pvat, vat, price_per_kg, box) {

            var id = id;
            var name = name;
            var qnt = Number(qnt);
            var price = Number(price);
            price = Number(price.toFixed(2));
            var pvat = Number(pvat);
            var vat = Number(vat);
            var totalPrice = Number(totalPrice);

            calculate_vat = ((price * product_vat) / 100) * qnt;
            calculate_vat = Number(calculate_vat.toFixed(2));

            if (!product_vat) {
                product_vat = 0;
            }

            vat = (vat + calculate_vat);

            var total = (price * qnt);
            var all_total = total + calculate_vat;
            all_total = Number(all_total.toFixed(2));

            $('.price-table').show();
            var hidden_class = '';

            if (vat_type === 'Global Base') {
                hidden_class = 'hidden';
            } else {

            }

            $('.price-table').append("<tr data-vat='" + calculate_vat + "'><td data-prodid='" + id + "' style='width:200px;'>" + name + "</td><td class='box'>" + box + "</td><td class='qnty'>" + qnt + "</td><td class='uprice'>" + price + "</td><td class='prod_vat'" + hidden_class + ">" + calculate_vat + "</td><td class='totalPriceTd'>" + total + "</td><td><i class='delete mdi mdi-delete'></i></td></tr>");

            var totalPriceVal = Number($('#hid_total').val());
            var totalVatFieldVal = Number($('#total_vat').val());
            totalPrice = Number(totalPriceVal + Number(total));

            if (vat_type === 'Global Base') {
                globalVat = parseFloat('{{ $GenSettings->vat??0 }}')
                vatAmount = (globalVat / 100) * totalPrice;
                vatField = Number(vatAmount);
                $('#total_vat').val(vatField)
                grandTotalPrice = Number(totalPrice + vatField);
            } else {
                vatField = Number(totalVatFieldVal + calculate_vat);
                $('#total_vat').val(vatField)
                grandTotalPrice = Number(totalPrice + vatField);
            }


            var scharge = $('#scharge').val();
            var discount = Number($('#discount').val());
            scharge = Number(scharge);

            var showGTotal = grandTotalPrice + scharge - discount;
            showGTotal = Number(showGTotal.toFixed(2));
            $('#show_grand_total').val(showGTotal);

            $('#hid_total').val(totalPrice);

            $('#amount').val(totalPrice);

            $('#total').val(totalPrice);

            $('#vat').val(vat);

            $('#search').val("");

            $('#price').val("");

            $('#qnt').val("");

            $('#barcode').val("");

            $('#search').focus();
        }


        function printPos(invoice) {

            $('#printdiv').css('width', '332px');

            $('#print_add').show();

            $('#print_add').css('width', '332px').css('text-align', 'center');

            $("#mid_section").show();

            var tcname = $('#cust_name').val();
            var tcaddress = $('#cust_address').val();

            //var tcadd = $('#add_tcaddress').val();

            //var invoice = $('#sid').val();

            var tcphone = $('#cust_phone').val();

            $("#cust_add").append("Customer: " + tcname + "<br>");
            //$("#cust_add").append("Address: "+tcadd+"<br>");
            $("#cust_add").append("Phone: " + tcphone + "<br>");
            $("#cust_add").append("Address: " + tcaddress + "<br>");
            $("#cust_add").append("Invoice: " + invoice);
            $("#cust_add").append(" &nbsp; ");


            $("#prodlist").css('border-collapse', 'collapse');

            $("#prodlist tbody tr").each(function () {

                $(this).find("th:eq(6)").remove();
                $(this).find("td:eq(6)").remove();

            });


            $("#prodlist th").css('font-size', '14px');

            $("#prodlist td").css('font-size', '14px').css('border', '1px solid #000');


            var amount = $('#amount').val();
            var vat = $('#total_vat').val();
            var scharge = $('#scharge').val();
            var discount = $('#discount').val();
            var show_grand_total = $('#show_grand_total').val();
            var payment = $('#payment').val();
            var due = show_grand_total - payment;
            var date = $('#date').val();


            $('#printdiv td').css('width', '332px');

            $("#cust_add").show();

            $('#prodlistDiv').css("width", "330px").css("height", "").css("clear", "float").css("background", "#FFF").css(
                "overflow", "");

            $('#prodlist').css("width", "330px");

            $('#logoimage').css("display", "none");

            $('#company').css("font-size", "26px");

            $('#printdiv').append(
                "<table id='printRest' style='width:332px; border-collapse: collapse;'><tr><td>Total Tk: </td><td>" +
                amount + "</td><td> VAT: </td><td>" + vat + "</td></tr><tr><td>Scharge: </td><td>" + scharge +
                "</td><td>Discount: </td><td>" + discount + "</td></tr><tr><td> All Total: </td><td>" +
                show_grand_total + "</td><td> payment: </td><td>" + payment + "</td></tr><tr><td> Due: </td><td>" +
                due + "</td><td> Date: </td><td>" + date + "</td></tr></table>");

            $("#printRest tr td").css('font-size', '12px').css('border', '1px solid #000').css('border-collapse',
                'collapse');

            $('#printdiv').append(
                '<table style="width:332px; margin: 10px auto;"><tr><td style="text-align:center;">Thanks For Business</td></tr></table>'
            );

            //////////////printReceipt///////////

            var ledger = {{ $ledger }};

            if (ledger == 1) {
                ledgerPrint(invoice);
            } else {
                var prtContent = document.getElementById("printdiv");
                var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
                WinPrint.close();
            }
        }

        function printPosDriver(invoice) {


            $('#printRest').hide()
            $("#prodlist tbody tr").each(function () {
                // $(this).find("th:eq(1)").remove();
                $(this).find("th:eq(1)").hide();
                $(this).find("th:eq(3)").hide();
                $(this).find("th:eq(4)").hide();
                $(this).find("th:eq(5)").hide();

                // $(this).find("td:eq(1)").hide();
                $(this).find("td:eq(1)").hide();
                $(this).find("td:eq(3)").hide();
                $(this).find("td:eq(4)").hide();
                $(this).find("td:eq(5)").hide();

            });

            $('#printdiv').append(
                '<table style="width:332px; margin: 10px auto;"><tr><td style="text-align:center;">Have a Safe Journey</td></tr></table>'
            );

            //////////////printReceipt///////////

            var ledger = {{ $ledger }};

            if (ledger == 1) {
                ledgerPrint(invoice);
            } else {
                var prtContent = document.getElementById("printdiv");
                var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
                WinPrint.close();
            }
        }

        function rePrintPos(invoice, company, company_add, tcname, tcphone, tcaddress, trow, amount, vat, scharge, discount, gtotal,
                            payment, due, date) {

            $('#printdiv').css('width', '332px');

            $('#print_add').css('text-align', 'center');

            $('#print_add').css('width', '332px');

            $('#print_add').show();

            $('#logoimage').css("display", "none");

            $('#company').css("font-size", "26px");

            $('#company').html(company);

            $('#company_add').html(company_add);

            $("#mid_section").show();

            $('#mid_section').css('width', '332px');

            $("#cust_add").show();

            $("#cust_add").append("Customer: " + tcname + "<br>");
            $("#cust_add").append("Phone: " + tcphone + "<br>");
            $("#cust_add").append("Address: " + tcaddress + "<br>");
            $("#cust_add").append("Invoice: " + invoice);
            $("#cust_add").append(" &nbsp; ");

            $("#prodlist").css('border-collapse', 'collapse');

            $("#prodlist tbody tr").each(function () {

                $(this).find("th:eq(6)").remove();
                $(this).find("th:eq(6)").remove();
            });

            $("#prodlist").append(trow);

            $("#prodlist th").css('font-size', '14px');

            $("#prodlist td").css('font-size', '14px').css('border', '1px solid #000');

            $('#prodlistDiv').css("width", "330px").css("height", "").css("clear", "float").css("background", "#FFF").css(
                "overflow", "");

            $('#prodlist').css("width", "330px");

            $('#printdiv').append(
                "<table id='printRest' style='width:332px; border-collapse: collapse;'><tr><td>Total Tk: </td><td>" +
                amount + "</td><td> Discount: </td><td>" + discount + "</td></tr><tr><td>VAT: </td><td>" + vat +
                "</td><td> SCharge: </td><td>" + scharge + "</td></tr><tr><td>All Total: </td><td>" + gtotal +
                "</td><td>Recieved: </td><td>" + discount + "</td></tr><tr><td> Due: </td><td>" + due +
                "</td><td> Payment: </td><td>" + payment + "</td></tr><tr><td> Date: </td><td>" + date +
                "</td><td> &nbsp; </td><td>&nbsp;</td></tr></table>");

            $("#printRest tr td").css('font-size', '12px').css('border', '1px solid #000').css('border-collapse',
                'collapse');

            $('#printdiv').append(
                '<table style="width:332px; margin: 10px auto;"><tr><td style="text-align:center;">Thanks For Business</td></tr></table>'
            );

            //////////////printReceipt///////////

            var ledger = {{ $ledger }};

            if (ledger == 1) {

                ledgerPrint(invoice);
            } else {
                var prtContent = document.getElementById("printdiv");
                var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
                WinPrint.close();
            }
        }

        var flag = 1;

        function ledgerPrint(invoice) {

            document.getElementById("printdiv").style.width = "100%";
            document.getElementById("printdiv").style.color = "black";
            document.getElementById("prodlist").style.width = "100%";

            $('#mid_section').css('width', '100%');
            $('#prodlistDiv').css("width", "100%");
            $('#print_add').css('width', '100%');
            $('#prodlist').css("width", "100%");
            $('#printRest').css("width", "100%");

            var printContents = document.getElementById("printdiv").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            if (flag == 1) {
                flag = 0;
                printPosDriver(invoice);
            }
            location.reload();
        }


    </script>
@stop

<style>
    .custom-modal {
        position: fixed;
        left: 0;
        top: 50px;
        width: 100%;
        height: 100%;
        background-color: #e6e6e6;
        z-index: 999;
        display: none;
    }

    #printRest {
        width: 330px;
    }

    #printRest tr td {
        font-size: 12px;
        border: 1px solid #000;
    }

    .sugg-list {
        width: 100%;
        background-color: #e6e6e6;
        padding: 0;
    }

    .sugg-list li {
        width: 100%;
        border-bottom: #FFF;
        color: #6a6a6a;
        list-style-type: none;
        padding: 5px;
    }

    .sugg-list li:hover {
        width: 100%;
        background-color: #006fd2;
        color: #FFF;
        padding: 0;
    }

    .custom-table {

        width: 100%;
        border-collapse: collapse;
    }

    .custom-table tr th {
        background-color: #1bcfb4;
        color: #FFF;
        text-align: center;
        padding: 5px;
    }

    .custom-table tr td {
        padding: 5px;
        border: 1px solid #e6e6e6;
        text-align: center;
        font-size: 14px;
    }

    .custom-text {

        width: 150px;
        border: 1px solid #e6e6e6;
        border-radius: 2px;
        outline: none;
        padding: 5px;
        box-sizing: border-box;
        text-align: center;
    }

    .custom-text:focus {
        border-color: dodgerBlue;
    }

    .card .card-body {
        padding: 0.5rem 0.5rem !important;
    }

    .content-wrapper {

        padding: 0.25rem 0.25rem !important;
    }

    .box-body {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding: 10px;
        background-color: #FFF;
    }

    .box.box-success {
        border-top-color: #6da252;
    }

    .input-group .input-group-addon {
        border-radius: 0;
        border-color: #d2d6de;
        background-color: #fff;
    }

    .input-group-addon:first-child {
        border-right: 0;
        border-right-color: currentcolor;
    }

    .input-group .form-control:first-child,
    .input-group-addon:first-child,
    .input-group-btn:first-child > .btn,
    .input-group-btn:first-child > .btn-group > .btn,
    .input-group-btn:first-child > .dropdown-toggle,
    .input-group-btn:last-child > .btn-group:not(:last-child) > .btn,
    .input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group-addon {
        min-width: 41px;
    }

    .input-group-addon {
        padding: 6px 12px;
        font-size: 14px;
        font-weight: 400;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-top-color: rgb(204, 204, 204);
        border-right-color: rgb(204, 204, 204);
        border-right-style: solid;
        border-right-width: 1px;
        border-bottom-color: rgb(204, 204, 204);
        border-left-color: rgb(204, 204, 204);
        border-radius: 4px;
    }

    .input-group-addon,
    .input-group-btn {
        width: 1%;
        white-space: nowrap;
        vertical-align: middle;
    }

    .input-group .form-control,
    .input-group-addon,
    .input-group-btn {
        display: table-cell;
    }

    .select2-hidden-accessible {
        border: 0 !important;
        clip: rect(0 0 0 0) !important;
        height: 1px !important;
        margin: -1px !important;
        overflow: hidden !important;
        padding: 0 !important;
        position: absolute !important;
        width: 1px !important;
    }

    .input-group-btn > .btn {
        position: relative;
    }

    .btn-icon {
        height: 34px;
    }

    .form-group .select2-container {
        width: 100% !important;
    }

    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
    }

    .form-group .select2-container .select2-selection--single {
        height: 34px;
        border: 1px solid #d2d6de;
    }

    .form-group .select2-container--default .select2-selection--single {
        border-radius: 0px;
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 28px;
        user-select: none;
        -webkit-user-select: none;
    }

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        border: 1px solid #d2d6de;
        border-radius: 0;
        padding: 6px 12px;
        height: 34px;
    }

    .form-group .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 26px;
        position: absolute;
        top: 1px;
        right: 1px;
        width: 20px;
    }

    .form-group .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #555 transparent transparent transparent;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #888 transparent transparent transparent;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
        top: 50%;
        width: 0;
    }

    .active {
        color: #FF0;
        background-color: #FFF;
    }

    #tblTotal tr td {
        border-top: 0;
    }

    .fancy-file {
        display: block;
        position: relative;
    }

    .fancy-file div {
        position: absolute;
        top: -1px;
        left: 0px;
        z-index: 1;
        height: 36px;
    }

    .fancy-file input[type="text"],
    .fancy-file button,
    .fancy-file .btn {
        display: inline-block;
        margin-bottom: 0;
        vertical-align: middle;
    }

    }

</style>
