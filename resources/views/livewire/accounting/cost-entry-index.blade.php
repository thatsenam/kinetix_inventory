<div>
    @section('title', 'ব্যয় যুক্তকরণ')

        <div class="content-wrapper">
            <h1 class="ml-3">ব্যয় যুক্ত করুন</h1>
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
                    <!-- <div class="row">
                        <div class="col-4 mx-auto">
                            <div class="form-group text-center">
                                <label for="voucher_type" class="font-weight-bold text-dark text-uppercase">Voucher Type</label>
                                <select wire:model.defer="voucher_type" id="voucher_type" class="custom-select @error('voucher_type') is-invalid @enderror">
                                    <option selected>Choose...</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                    <option value="Journal">Journal</option>
                                </select>
                                @error('voucher_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="vno"
                                class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">ভাউচার নং</label>
                            <div class="col-sm-8">
                                <input wire:model.defer="vno" id="vno" type="text"
                                    class="form-control @error('vno') is-invalid @enderror" id="vno"
                                    placeholder="Voucher No" readonly>
                                @error('vno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="date"
                                class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">ভাউচার
                                তারিখ</label>
                            <div class="col-sm-8">
                                <input wire:model.defer="date" type="text" class="form-control" id="date" placeholder=""
                                    readonly>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="head" class="col-sm-4 font-weight-bold text-dark text-uppercase">
                                ব্যয়ের খাত
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                    data-target=".bd-example-modal-lg">+</button>
                            </label>
                            <div class="col-sm-8">
                                <select wire:model="head" id="head"
                                    class="custom-select @error('head') is-invalid @enderror">
                                    <option value="null" selected>নির্বাচন</option>
                                    @foreach ($all_heads as $all_head)
                                        <option value="{{ $all_head }}">{{ $all_head }}</option>
                                    @endforeach
                                </select>
                                @error('head')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="description"
                                class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">ব্যয়ের
                                কারন</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="description" placeholder="বিস্তারিত">
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="type"
                                class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">পরিশোধের
                                ধরন</label>
                            <div class="col-sm-8">
                                <select id="paytype" class="form-control custom-select">
                                    <option value="ক্যাশ">ক্যাশ </option>
                                    <option value="ব্যাংক">ব্যাংক </option>
                                    <option value="মোবাইল">মোবাইল </option>
                                    <option value="কার্ড">কার্ড </option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="amount"
                                class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">ব্যয়ের
                                পরিমান</label>
                            <div class="col-sm-8">
                                <input type="text" id="amount" data-toggle="tooltip" title="Press Enter"
                                    data-placement="top" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="ব্যয়ের পরিমান">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="text-center mt-3">
                        <button type="submit" id="save" class="btn btn-success rounded-0">সাবমিট</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="check_info" class="custom-modal" wire:ignore.self>

            <div class="card" style="width:500px; margin: 0 auto; position:relative;">
                <input type="button" class="btn btn-danger close-modal" value="X"
                    style="position: absolute; top:15px; right: 5px;">
                <div class="card-body" style="height:500px;overflow:scroll">
                    <h3 style="text-align: center;">চেকের তথ্য</h3>

                    <table class=""
                        style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">
                        <tr>
                            <td><label style="padding:10px;">গ্রাহকের ব্যাংক</label></td>
                            <td><input type="text" class="form-control" id="clients_bank" name="clients_bank"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">গ্রাহকের ব্যাংক একাউন্ট</label></td>
                            <td><input type="text" class="form-control" id="clients_bank_acc" name="clients_bank_acc"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">চেক নাম্বার</label></td>
                            <td><input type="text" class="form-control" id="check_no" name="check_no"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">প্রতিষ্ঠানের ব্যাংক</label></td>
                            <td>
                                <select class="form-control" id="shops_bank_account" name="shops_bank_account" required>
                                    <?php for ($i = 0; $i < count($banks); ) { $j=$i; $j1=$i + 1; $j2=$i + 2;
                                        $j3=$i + 3; ?> <option value="{{ $banks[$j1] . ',' . $banks[$j2] }}">
                                        {{ $banks[$j] . ',' . $banks[$j3] }}</option>
                                        <?php $i = $i + 4;
                                        } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">পরিমান</label></td>
                            <td><input type="text" class="form-control" id="check_amount" name="check_amount"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">ক্যাশ (আংশিক)</label></td>
                            <td><input type="text" class="form-control" id="check_cash" name="check_cash"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">চেকের তারিখ</label></td>
                            <td><input type="text" class="form-control" id="check_date" name="check_date"></td>
                        </tr>
                        <tr>
                            <td><label style="padding:10px;">নোট</label></td>
                            <td><input type="text" class="form-control" id="check_remarks" name="check_remarks"></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width:80px; margin-left: 20px;" class="btn btn-primary check_total"></div>
                            </td>
                            <td>
                                <div style="width:50px; margin: 20px auto;"><input type="button" class="btn btn-success"
                                        id="check_ok" value="ওকে"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-------Mobile Bank Information----------->

        <div id="mobile_info" class="custom-modal" wire:ignore.self>

            <div class="card" style="width:500px; margin: 0 auto; position:relative;">
                <input type="button" class="btn btn-danger close-modal" value="X"
                    style="position: absolute; top:15px; right: 5px;">
                <div class="card-body">
                    <h3 style="text-align: center;">মোবাইল ব্যাংক তথ্য</h3>

                    <table class=""
                        style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">

                        <tr>
                            <td><label style="padding:10px;">ব্যাংকের নাম</label></td>
                            <td>
                                <select class="form-control" id="mobile_bank_account" name="mobile_bank_account" <?php $infos=$bank_infos; for ($i=0; $i < count($infos); ) { $j=$i; $j1=$i +
                                    1; $j2=$i + 2; $j3=$i + 3; ?> <option
                                    value="{{ $infos[$j1] . ',' . $infos[$j2] }}">{{ $infos[$j] . ',' . $infos[$j3] }}</option>
                                    <?php $i = $i + 4;
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>


                        <tr>
                            <td><label style="padding:10px;">গ্রাহকের ব্যাংক নাম্বার</label></td>
                            <td><input type="text" class="form-control" id="bt_clients_bank" name="bt_clients_bank">

                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">পরিমান</label></td>
                            <td><input type="text" class="form-control" id="mobile_amount" name="mobile_amount"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">ক্যাশ (আংশিক)</label></td>
                            <td><input type="text" class="form-control" id="mobile_cash" name="mobile_cash"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">ট্রানজেকশন নাম্বার</label></td>
                            <td><input type="text" class="form-control" id="tranxid" name="tranxid"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">নোট</label></td>
                            <td><input type="text" class="form-control" id="mobile_remarks" name="mobile_remarks"></td>
                        </tr>

                        <tr>
                            <td>
                                <div style="width:80px; margin-left: 20px;" class="btn btn-primary mobile_total"></div>
                            </td>
                            <td>
                                <div style="width:50px; margin: 20px auto;"><input type="button"
                                        class="btn btn-success btn-lg" id="mobile_ok" value="ওকে"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


        <!-------Card Information----------->

        <div id="card_info" class="custom-modal" wire:ignore.self>

            <div class="card" style="width:500px; margin: 0 auto; position:relative;">
                <input type="button" class="btn btn-danger close-modal" value="X"
                    style="position: absolute; top:15px; right: 5px;">
                <div class="card-body">
                    <h3 style="text-align: center;">ক্রেডিট / ডেবিট কার্ড তথ্য</h3>

                    <table class=""
                        style="width: 100%; border: 1px solid #e6e6e6; padding: 5px; border-collapse: collapse;">

                        <tr>
                            <td><label style="padding:10px;">কার্ডের ধরন</label></td>
                            <td>
                                <select class="form-control" id="card_type" name="card_type">
                                    <option value="visa">ভিসা কার্ড</option>
                                    <option value="master">মাস্টার কার্ড</option>
                                    <option value="dbbl">নেক্সাস কার্ড</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">প্রতিষ্ঠানের ব্যাংক</label></td>
                            <td>
                                <select class="form-control" id="card_bank_account" name="card_bank_account">
                                    <?php for ($i = 0; $i < count($banks); ) { $j=$i; $j1=$i + 1; $j2=$i + 2;
                                        $j3=$i + 3; ?> <option value="{{ $banks[$j1] . ',' . $banks[$j2] }}">
                                        {{ $banks[$j] . ',' . $banks[$j3] }}</option>
                                        <?php $i = $i + 4;
                                        } ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">পরিমান</label></td>
                            <td><input type="text" class="form-control" id="card_amount" name="card_amount"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">ক্যাশ (আংশিক)</label></td>
                            <td><input type="text" class="form-control" id="card_cash" name="card_cash"></td>
                        </tr>

                        <tr>
                            <td><label style="padding:10px;">নোট</label></td>
                            <td><input type="text" class="form-control" id="card_remarks" name="card_remarks"></td>
                        </tr>

                        <tr>
                            <td>
                                <div style="width:80px; margin-left: 20px;" class="btn btn-primary card_total"></div>
                            </td>
                            <td>
                                <div style="width:50px; margin: 20px auto;"><input type="button"
                                        class="btn btn-success btn-lg" id="card_ok" value="ওকে"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Account Head Modal --}}
    @include('livewire.accounting.cost-entry.modal')
@section('js')
    {{-- <script src="/js/conversion.js"></script> --}}
    <script>
        window.addEventListener("livewire:load", function(event) {

            $('#head').select2({
                placeholder: 'Choose Head',
            });
            $(document).on('change', '#head', function(e) {
                @this.set('head', e.target.value);
            });


        });
        $('#paytype').change(function() {

            if ($(this).val() == 'ব্যাংক') {

                $('#check_info').show();

                $('.check_total').html($('#total').val());
            }

        });

        $('#paytype').change(function() {

            if ($(this).val() == 'মোবাইল') {

                $('#mobile_info').show();

                $('.mobile_total').html($('#total').val());
            }

        });

        $('#paytype').change(function() {

            if ($(this).val() == 'কার্ড') {

                $('#card_info').show();

                $('.card_total').html($('#total').val());
            }

        });

        $('#check_type').change(function() {

            if ($(this).val() == 'pay_account') {

                $('.hide_tr').show();

                $('#shops_bank').focus();
            }

            if ($(this).val() == 'pay_cash') {

                $('.hide_tr').hide();
            }

        });


        $('#check_ok').click(function() {

            $('#check_info').hide();

        });

        $('#mobile_ok').click(function() {

            $('#mobile_info').hide();

        });

        $('#card_ok').click(function() {

            $('#card_info').hide();

        });

        $('.close-modal').click(function() {

            $('.custom-modal').hide();
        });

        $('#shops_bank_account').on('change', function() {
            var bank_ac = $('#shops_bank_account').val();
            var bank_name = $('#shops_bank_account option:selected').text();
            Livewire.emit('bank_add', bank_ac, bank_name);
        })
        $('#mobile_bank_account').on('change', function() {
            var bank_ac = $('#mobile_bank_account').val();
            var bank_name = $('#mobile_bank_account option:selected').text();
            Livewire.emit('bank_add', bank_ac, bank_name);
        })
        $('#card_bank_account').on('change', function() {
            var bank_ac = $('#card_bank_account').val();
            var bank_name = $('#card_bank_account option:selected').text();
            Livewire.emit('bank_add', bank_ac, bank_name);
        })

        $("#check_amount").on("change keyup paste", function() {

            var check_amount = Number(btoe($(this).val()));

            $('#amount').val(etob(check_amount));

        });

        $("#mobile_amount").on("change keyup paste", function() {

            var mobile_amount = Number(btoe($(this).val()));
            $('#amount').val(etob(mobile_amount));

        });

        $("#card_amount").on("change keyup paste", function() {

            var card_amount = Number(btoe($(this).val()));


            $('#amount').val(etob(card_amount));

        });

        $("#mobile_cash").on("change keyup paste", function() {

            var mobile_cash = Number(btoe($(this).val()));

            var mobile_amount = Number(btoe($('#mobile_amount').val()));


            $('#amount').val(etob(mobile_amount + mobile_cash));

        });

        $("#card_cash").on("change keyup paste", function() {

            var card_cash = Number(btoe($(this).val()));

            var card_amount = Number(btoe($('#card_amount').val()));

            $('#amount').val(etob(card_amount + card_cash));

        });

        $("#check_cash").on("change keyup paste", function() {

            var check_cash = Number(btoe($(this).val()));

            var check_amount = Number(btoe($('#check_amount').val()));


            $('#amount').val(etob(check_amount + check_cash));

        });

        $('#save').click(function(e) {
            if ($('#paytype').val() == 'check') {
                if ($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $('#check_amount')
                .val() == '' || $('#check_date').val() == '') {
                    alert("চেকের তথ্য পুরন করুন");
                    return false;
                }
                if ($('#check_type').val() == 'pay_account') {
                    if ($('#shops_bank_account').val() == '') {
                        alert("চেকের তথ্য পুরন করুন");
                        return false;
                    }
                }
            }
            if ($('#amount').val() == '' || $('#head').val() == 'null' || $('#description').val() == '') {
                alert('সকল তথ্য পুরন করুন');
                return false;
            }
            var fieldValues = {};

            fieldValues.vno = $('#vno').val();
            fieldValues.date = $('#date').val();
            fieldValues.head = $('#head').val();
            fieldValues.description = $('#description').val();
            fieldValues.paytype = $('#paytype').val();
            fieldValues.amount = $('#amount').val();

            if ($('#paytype').val() == 'cash') {
                $('#card_type').val() == 'Cash';
            }
            fieldValues.clients_bank = $('#clients_bank').val();
            fieldValues.clients_bank_acc = $('#clients_bank_acc').val();
            fieldValues.check_amount = $('#check_amount').val();
            fieldValues.check_cash = $('#check_cash').val();
            fieldValues.check_no = $('#check_no').val();
            fieldValues.check_date = $('#check_date').val();
            fieldValues.check_type = $('#check_type').val();
            fieldValues.shops_bank_account = $('#shops_bank_account').val();
            fieldValues.shops_bank_name = $('#shops_bank_account option:selected').text();
            fieldValues.check_remarks = $('#check_remarks').val();
            fieldValues.card_type = $('#card_type').val();
            fieldValues.card_bank_account = $('#card_bank_account').val();
            fieldValues.card_bank_name = $('#card_bank_account option:selected').text();
            fieldValues.card_amount = $('#card_amount').val();
            fieldValues.card_cash = $('#card_cash').val();
            fieldValues.card_remarks = $('#card_remarks').val();


            fieldValues.mobile_bank_acc_cust = $('#mobile_bank_acc_cust').val();
            fieldValues.mobile_bank_account = $('#mobile_bank_account').val();
            fieldValues.mobile_bank_name = $('#mobile_bank_account option:selected').text();
            fieldValues.mobile_amount = $('#mobile_amount').val();
            fieldValues.mobile_cash = $('#mobile_cash').val();
            fieldValues.tranxid = $('#tranxid').val();
            fieldValues.mobile_remarks = $('#mobile_remarks').val();
            var formData = new FormData();

            formData.append('fieldValues', JSON.stringify(fieldValues));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ URL::route('add_cost') }}",
                method: 'post',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    alert("ব্যয় যুক্তকরণ সফল হয়েছে!")
                    location.reload();

                },
                error: function(ts) {
                    alert("ব্যয় যুক্তকরণ সফল হয়েছে!")
                    location.reload();
                },
            });
        });

    </script>
@endsection
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
    .input-group-btn:first-child>.btn,
    .input-group-btn:first-child>.btn-group>.btn,
    .input-group-btn:first-child>.dropdown-toggle,
    .input-group-btn:last-child>.btn-group:not(:last-child)>.btn,
    .input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle) {
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

    .input-group-btn>.btn {
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
