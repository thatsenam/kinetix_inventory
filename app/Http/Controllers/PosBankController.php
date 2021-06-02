<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Image;
use App\Filter;
use App\Seller;
use App\AccHead;
use App\BankAcc;
use App\Company;
use App\Product;
use App\BankCard;
use App\BankInfo;
use App\Category;
use App\Manufacturer;
use App\ProductImage;
use App\AccTransaction;
use App\BankInstallment;
use App\BankLoan;
use App\BankTransaction;
use App\BankTransfer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosBankController extends Controller
{

    public function add_bank(Request $request)
    {
        if($request->isMethod('post')){
            //dd($request->all());
            $category = $request->category;
            $name = $request->bank_name;
            $address = $request->bank_address ?? '';
            $acc_name = $request->acc_name ?? '';
            $acc_no = $request->acc_no;
            $cards = $request->card_name ?? '';

            $bank_info=new BankInfo();
            $bank_info->name = $name;
            $bank_info->address = $address;
            $bank_info->type = $category;
            $bank_info->save();

            $bank_acc = new BankAcc();
            $bank_acc->bank_id = $bank_info->id;
            $bank_acc->acc_name = $acc_name;
            $bank_acc->acc_no = $acc_no;
            $bank_acc->save();

            if($cards){
                foreach($cards as $card){
                    if(strlen($card)>2)
                    {
                        $bank_card=new BankCard();
                        $bank_card->bank_id = $bank_info->id;
                        $bank_card->acc_no = $bank_acc->id;
                        $bank_card->card_name = $card;
                        $bank_card->user_id = auth()->user()->id;
                        $bank_card->client_id = auth()->user()->client_id;
                        $bank_card->save();
                    }
                }
            }


            $acc_head = new AccHead();
            $acc_head->parent_head = "Asset";
            if($category=="mobile_bank"){
                $acc_head->sub_head = "Mobile Bank";
            }else{
                $acc_head->sub_head = "General Bank";
            }
            $acc_head->head = $name." A/C: ".$acc_name;
            $acc_head->save();

            return redirect('/dashboard/add_bank')->with('flash_message_success', 'Bank has been created successfully!');
        }

        return view('admin.pos.banking.add_bank');
    }

    public function view_banks()
    {
        $banks = BankInfo::orderBy('name')->where('client_id', auth()->user()->client_id)->get();

        return view('admin.pos.banking.view_bank', compact('banks'));
    }



    public function edit_bank(Request $req, $id = null){
        $bank = BankInfo::where(['id'=>$id])->first();
        $cards=$bank->cards ?? '';
        if($req->isMethod('post')){
            $data = $req->all();
            $prev_cards=$req->card_name_prev ?? '';
            $new_cards=$req->card_name ?? '';
            // $acc_head->head = $name." A/C: ".$acc_name;
            AccHead::where(['head'=>$bank->name . ' A/C: ' . $bank->account->acc_name])
                    ->where('client_id', auth()->user()->client_id)->update(['head'=>$data['bank_name'] . ' A/C: ' . $data['acc_name'],]);
            $acc_transaction_head = AccTransaction::where('head', $bank->name . ' A/C: ' . $bank->account->acc_no)->where('client_id', auth()->user()->client_id)->get();
            foreach($acc_transaction_head as $acc){
                $acc->head = $data['bank_name'] . ' A/C: ' . $data['acc_name'];
                $acc->save();
            }
            BankInfo::where(['id'=>$id])->where('client_id', auth()->user()->client_id)->update(['name'=>$data['bank_name'],'address'=>$data['bank_address'],]);
            BankAcc::where(['bank_id'=>$id])->where('client_id', auth()->user()->client_id)->update(['acc_name'=>$data['acc_name'],'acc_no'=>$data['acc_no'],]);
            if($prev_cards){
                foreach($prev_cards as $key=>$value){
                    BankCard::where(['id'=>$key])->where('client_id', auth()->user()->client_id)->update(['card_name'=>$value,]);
                }
            }
            if($new_cards){
                foreach($new_cards as $card){
                    $bank_card=new BankCard();
                    $bank_card->bank_id = $id;
                    $bank_card->acc_no = $bank->account->id;
                    $bank_card->card_name = $card;
                    $bank_card->save();
                }
            }

           // Cost::where(['id'=>$id])->update(['category_id'=>$data['category'],'sub_category'=>$data['sub_category'],'reason'=>$data['reason'],'amount'=>$data['amount'],'details'=>$data['details'],'date'=>$data['date'],]);

            return redirect('/dashboard/view_banks')->with('flash_message_success', 'Bank has been updated successfully!');
        }
        return view('admin.pos.banking.edit_bank')->with('bank', $bank)->with('cards', $cards)->with('id', $id);

    }

    public function get_mobile_bank()
    {
        $mobile_banks = BankInfo::where('type','mobile_bank')->where('client_id', auth()->user()->client_id);
        $bank_infos=array();
        foreach($mobile_banks as $bank){
            $bank_infos[$bank->name]=$bank->name;
            $bank_infos[$bank->name]=$bank->id;
            $bank_infos[$bank->name]=$bank->account->id;
            $bank_infos[$bank->name]=$bank->account->acc_name;
        }

        ?>

        <ul class='bank-acc-list sugg-list'>

            <?php $i = 1;

            foreach($mobile_banks as $row){

                $id = $row->id;

                $acc_name = $row->name;
                $acc_name = $row->account->acc_no;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectAccount("<?php echo $id; ?>","<?php echo $acc_name; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $acc_name; ?>'><?php echo $acc_name; ?></li>

            <?php } ?>

        </ul>

        <?php
    }

    public function get_bank(Request $req){

        $s_text = $req['s_text'];

        $get_bank = DB::table('bank_info')->where('name', 'like', '%'.$s_text.'%')
        ->where('client_id', auth()->user()->client_id)->limit(9)->get(); ?>

        <ul class='bank-list sugg-list'>

            <?php $i = 1;

            foreach($get_bank as $row){

                $id = $row->id;

                $name = $row->name;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectBank("<?php echo $id; ?>", "<?php echo $name; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>'><?php echo $name; ?></li>

            <?php } ?>

        </ul>

        <?php
    }

    public function get_bank_account(Request $req){

        $s_text = $req['s_text'];
        $bank_id = $req['bank_id'];

        $get_bank_acc = DB::table('bank_acc')->where('acc_name', 'like', '%'.$s_text.'%')
            ->where('client_id', auth()->user()->client_id)
            ->where('bank_id', $bank_id)->limit(9)->get(); ?>

        <ul class='bank-acc-list sugg-list'>

            <?php $i = 1;

            foreach($get_bank_acc as $row){

                $id = $row->id;

                $acc_name = $row->acc_name;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectAccount("<?php echo $id; ?>","<?php echo $acc_name; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $acc_name; ?>'><?php echo $acc_name; ?></li>

            <?php } ?>

        </ul>

        <?php
    }

    public function check_clearance(){

        return view('admin.pos.banking.check_clearance');
    }

    public function get_check_clearance(Request $req)
    {

        $stdate = $req['stdate'];
        $enddate = $req['enddate'];
        $check_type = $req['check_type'];
        $trow = "";

        if ($check_type == "collection") {
            try {
                $bank_transactions = DB::table('bank_transactions')->select("bank_transactions.id as transid", "bank_transactions.date as date", "bank_transactions.invoice_no as invoice", "customers.name as name", "customers.address as add", "customers.phone as phone",
                    "bank_transactions.clients_bank as bank", "bank_transactions.clients_bank_acc", "bank_transactions.check_no", "bank_transactions.withdraw")
                    ->join('customers', 'bank_transactions.cid', 'customers.id')
                    ->where('bank_transactions.client_id', auth()->user()->client_id)
                    ->where('bank_transactions.withdraw', '>', 0)
                    ->where('bank_transactions.type', 'check')
                    ->where('bank_transactions.status', 'pending')
                    ->whereBetween('bank_transactions.date', [$stdate, $enddate])
                    ->get();
            } catch (\Exception $exception) {
                $bank_transactions = [];
            }

            foreach ($bank_transactions as $row) {
                $trow .= "<tr data-transid = '$row->transid'><td>" . $row->date . "</td><td>" . $row->invoice . "</td><td>" . $row->name . "</td><td>" . $row->add . "<br>" . $row->phone . "</td><td>" . $row->bank . "</td><td>" . $row->clients_bank_acc . "</td><td>" . $row->check_no . "</td><td>" . $row->withdraw . "</td><td><a title='Clear' href='#' class='clear'><span class='btn btn-xs btn-primary'><i class='mdi mdi-check'></i></span></a></td></tr>";
            }

            return $trow;

        }
        if ($check_type == "payment") {

            $bank_transactions = DB::table('bank_transactions')->select("bank_transactions.id as transid", "bank_transactions.date", "bank_transactions.invoice_no as invoice", "suppliers.name as name", "suppliers.address as add", "suppliers.phone as phone", "bank_transactions.clients_bank as bank", "bank_transactions.clients_bank_acc", "bank_transactions.check_no", "bank_transactions.deposit")
                ->join('suppliers', 'bank_transactions.sid', 'suppliers.id')
                ->where('bank_transactions.client_id', auth()->user()->client_id)
                ->where('bank_transactions.deposit', '>', 0)
                ->where('bank_transactions.type', 'check')
                ->where('bank_transactions.status', 'pending')
                ->whereBetween('bank_transactions.date', [$stdate, $enddate])
                ->get();

            foreach ($bank_transactions as $row) {
                $trow .= "<tr data-transid = '$row->transid'><td>" . $row->date . "</td><td>" . $row->invoice . "</td><td>" . $row->name . "</td><td>" . $row->add . "<br>" . $row->phone . "</td><td>" . $row->bank . "</td><td>" . $row->clients_bank_acc . "</td>
                <td>" . $row->check_no . "</td><td>" . $row->deposit . "</td><td><a title='Clear' href='#' class='clear'><span class='btn btn-xs btn-primary'><i class='mdi mdi-check'></i></span></a></td></tr>";
            }
            return $trow;
        }
    }


    public function save_check_clearance(Request $req){

        $transid = $req['transid'];
        $check_type = $req['check_type'];

        $transactions = DB::table('bank_transactions')->where('id', $transid)->first();

        $invoice = $transactions->invoice_no;
        $bank_id = $transactions->seller_bank_id;
        $bank_acc_id = $transactions->seller_bank_acc_id;
        $cid = $transactions->cid;
        $amount = $transactions->deposit;

        if($check_type == "collection"){

            if($bank_id > 0 && $bank_acc_id > 0){

                $get_bank = DB::table('bank_info')->where('id', $bank_id)->first();

                $bank_name = $get_bank->name;

                $get_bank_acc = DB::table('bank_acc')->where('id', $bank_acc_id)->first();

                $bank_account = $get_bank_acc->acc_name;

                $debit_head = $bank_name." A/C: ".$bank_account;

            }else{

                $debit_head = "Cash in Hand";
            }

            $get_customer = DB::table('customers')->where('id', $cid)->first();

            $cust_name = $get_customer->name;
            $cust_phone = $get_customer->phone;

            // $vno = (DB::table('acc_transactions')->max('id') + 1);

            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);

            $head = $debit_head;
            $description = "Check for Invoice ".$invoice;
            $debit = $transactions->deposit;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cid,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => date('Y-m-d'),
                // 'user' => Auth::id(),

            ]);

            $head = $cust_name." ".$cust_phone;
            $description = "Check Collection For Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cid,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => date('Y-m-d'),
                // 'user' => Auth::id(),

            ]);


            DB::table('bank_transactions')->where('id', $transid)->update(['status' => 'paid']);

        }
    }

    public function bank_deposit(){

        $bank_info = DB::table('bank_info')->where('client_id', auth()->user()->client_id)->get();
        return view('admin.pos.banking.bank_deposit')->with('bank_info', $bank_info);
    }


    public function get_bank_acc(Request $req){

        $bank_id = $req['bank_id'];

        $options = "<option disabled selected>Select Account</option>";

        $accounts = DB::table('bank_acc')->where('bank_id', $bank_id)->get();

        foreach($accounts as $row){

            if( ! $row->acc_name)
            {
                $options .= "<option value='$row->id'>".$row->acc_no."</option>";
            }
            else
            {
                $options .= "<option value='$row->id'>".$row->acc_name."</option>";
            }
        }

        return $options;
    }

    public function get_bank_balance(Request $req){

        $account_id = $req['account_id'];

        $balance = DB::table('bank_transactions')->select(DB::raw('SUM(deposit) as todep'), DB::raw('SUM(withdraw) as towith'))->where('seller_bank_acc_id', $account_id)->first();

        $todep = $balance->todep;

        $towith = $balance->towith;

        return $balance = ($todep - $towith);
    }


    public function save_bank_deposit(Request $req){

        $bank_id = $req['bank_id'];
        $bank_name = $req['bank_name'];
        $account_id = $req['account_id'];
        $account_name = $req['account_name'];
        $balance = $req['balance'];
        $check_no = $req['check_no'];
        $amount = $req['amount'];
        $date = $req['date'];
        $remarks = $req['remarks'];
        $user = Auth::id();

        $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
        $vno = date('Ymd') . '-' . ($vno_counting + 1);

        BankTransaction::create([
            'vno' => $vno,
            'seller_bank_id' => $bank_id,
            'seller_bank_acc_id' => $account_id,
            'check_no' => $check_no,
            'check_date' => $date,
            'date' => $date,
            'deposit' => $amount,
            'type' => 'cash',
            'remarks' => $remarks,
            // 'user' => $user,
        ]);

        $head = $bank_name." A/C: ".$account_name;
        $description = "Cash Deposit";
        $debit = $amount;
        $credit = 0;

        AccTransaction::create([

            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => $remarks,
            'date' => $date,
            // 'user' => $user,
        ]);

        $head = "Cash in Hand";
        $description = "Deposited to ".$bank_name." A/C: ".$account_name;;
        $debit = 0;
        $credit = $amount;

        AccTransaction::create([

            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => $remarks,
            'date' => $date,
            // 'user' => $user,
        ]);
    }

    public function bank_withdraw(){

        $bank_info = DB::table('bank_info')->where('client_id', auth()->user()->client_id)->get();

        return view('admin.pos.banking.bank_withdraw')->with('bank_info', $bank_info);
    }

    public function save_bank_withdraw(Request $req){

        $bank_id = $req['bank_id'];
        $bank_name = $req['bank_name'];
        $account_id = $req['account_id'];
        $account_name = $req['account_name'];
        $balance = $req['balance'];
        $check_no = $req['check_no'];
        $amount = $req['amount'];
        $date = $req['date'];
        $remarks = $req['remarks'];
        $user = Auth::id();

        $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
        $vno = date('Ymd') . '-' . ($vno_counting + 1);

        BankTransaction::create([
            'vno' => $vno,
            'seller_bank_id' => $bank_id,
            'seller_bank_acc_id' => $account_id,
            'check_no' => $check_no,
            'check_date' => $date,
            'date' => $date,
            'withdraw' => $amount,
            'type' => 'cash',
            'remarks' => $remarks,
            // 'user' => $user,
        ]);

        // $vno = (DB::table('acc_transactions')->max('id') + 1);

        $head = $bank_name." A/C: ".$account_name;
        $description = "Cash Withdrwan";
        $debit = 0;
        $credit = $amount;

        AccTransaction::create([

            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => $remarks,
            'date' => $date,
            // 'user' => $user,
        ]);

        $head = "Cash in Hand";
        $description = "Withdrawn from ".$bank_name." A/C: ".$account_name;;
        $debit = $amount;
        $credit = 0;

        AccTransaction::create([

            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => $remarks,
            'date' => $date,
            // 'user' => $user,
        ]);
    }

    public function bank_ledger(){

        $bank_info = DB::table('bank_info')->where('client_id', auth()->user()->client_id)->get();
        $setting = DB::table('general_settings')->where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.banking.bank_ledger', compact('bank_info', 'setting'));
    }

    public function get_bank_ledger(Request $req){

        $stdate = $req['stdate'];
        $enddate = $req['enddate'];
        $bank_name = $req['bank_name'];
        $account_name = $req['account_name'];

        $head = $bank_name." A/C: ".$account_name;

        $accounts = DB::table('acc_transactions')->where('client_id', auth()->user()->client_id)
            ->where('head', $head)
            ->whereDate('date', '>=', $stdate)
            ->whereDate('date', '<=', $enddate)
            ->get();

        $previousBalance = AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('head', $head)
            ->whereDate('date', '<', $stdate)
            ->sum('debit') -
            AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('head', $head)
            ->whereDate('date', '<', $stdate)
            ->sum('credit');

        $trow = "";
        $debit = 0;
        $credit = 0;

        $Balance = 0;

        $trow = "<tr><th>Previous Balance</th><th colspan='4'></th><th>". $previousBalance ."</th></tr>";

        $trow .= "<tr>
                    <th>Date</th>
                    <th>Voucher No</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>";

        foreach($accounts as $row)
        {
            $Balance = $Balance + $row->debit - $row->credit;

            $date = date('d-M-Y', strtotime($row->date));

            $trow .= "<tr><td>".$date."</td><td>".$row->vno."</td><td>".$row->description."</td><td>".$row->debit."</td><td>".$row->credit."</td><td>". $Balance ."</td></tr>";
            // $debit += $row->debit;
            // $credit += $row->credit;
        }
        // $trow .= "<tr><th colspan='2'>Total</th><th>".$debit."</th><th>".$credit."</th><th></th></tr>";

        return $trow;
    }

    public function bank_transfer(){

        $bank_info = DB::table('bank_info')->where('client_id', auth()->user()->client_id)->get();

        return view('admin.pos.banking.bank_transfer')->with('bank_info', $bank_info);
    }

    public function save_bank_transfer(Request $req)
    {
        $tf_bank = $req['tf_bank'];
        $tf_bank_name = $req['tf_bank_name'];
        $tf_acc = $req['tf_acc'];
        $tf_account_name = $req['tf_account_name'];
        $tt_bank = $req['tt_bank'];
        $tt_bank_name = $req['tt_bank_name'];
        $tt_acc = $req['tt_acc'];
        $tt_account_name = $req['tt_account_name'];
        $balance = $req['balance'];
        $check_no = $req['check_no'];
        $amount = $req['amount'];
        $date = $req['date'];

        $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
        $vno = date('Ymd') . '-' . ($vno_counting + 1);

        BankTransfer::create([
            'vno' => $vno,
            'tf_bank' => $tf_bank,
            'tf_acc' => $tf_acc,
            'tt_bank' => $tt_bank,
            'tt_acc' => $tt_acc,
            'check_no' => $check_no,
            'amount' => $amount,
            'date' => $date,
        ]);

        BankTransaction::create([
            'vno' => $vno,
            'seller_bank_id' => $tf_bank,
            'seller_bank_acc_id' => $tf_acc,
            'check_no' => $check_no,
            'check_date' => $date,
            'date' => $date,
            'withdraw' => $amount,
            'type' => 'cash',
            'remarks' => '',
            // 'user' => $user,
        ]);

        BankTransaction::create([
            'vno' => $vno,
            'seller_bank_id' => $tt_bank,
            'seller_bank_acc_id' => $tt_acc,
            'check_no' => $check_no,
            'check_date' => $date,
            'date' => $date,
            'deposit' => $amount,
            'type' => 'cash',
            'remarks' => '',
            // 'user' => $user,
        ]);

        // // $vno = (DB::table('acc_transactions')->max('id') + 1);

        $head = $tf_bank_name." A/C: ".$tf_account_name;
        $description = "Cash Withdrwan";
        $debit = 0;
        $credit = $amount;

        AccTransaction::create([
            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => '',
            'date' => $date,
            // 'user' => $user,
        ]);

        $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
        $vno = date('Ymd') . '-' . ($vno_counting + 1);

        $head = $tt_bank_name." A/C: ".$tt_account_name;
        $description = "Cash Deposit";
        $debit = $amount;
        $credit = 0;

        AccTransaction::create([
            'vno' => $vno,
            'head' => $head,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'note' => '',
            'date' => $date,
            // 'user' => $user,
        ]);

        // $head = "Cash in Hand";
        // $description = "Deposited to ".$bank_name." A/C: ".$account_name;;
        // $debit = 0;
        // $credit = $amount;

        // AccTransaction::create([

        //     'vno' => $vno,
        //     'head' => $head,
        //     'description' => $description,
        //     'debit' => $debit,
        //     'credit' => $credit,
        //     'note' => $remarks,
        //     'date' => $date,
        //     // 'user' => $user,
        // ]);
    }

    public function bank_transfer_report()
    {
        return view('admin.pos.banking.bank_transfer_report');
    }

    public function get_bank_transfer_report(Request $req)
    {
        $stdate = $req['stdate'];

        $enddate = $req['enddate'];

        if(!$stdate){
            $stdate = date('Y-m-d');
        }
        if(!$enddate){
            $enddate = date('Y-m-d');
        }

        $bank_transfer = DB::table('bank_transfer')->where('bank_transfer.client_id',auth()->user()->client_id)
            ->select('bank_transfer.id as bank_transfer_id', 'bank_transfer.date as date', 'bank_transfer.amount as amount','bank_transfer.vno as vno',
            'A.name as from_bank', 'B.name as to_bank', 'C.acc_name as from_acc', 'D.acc_name as to_acc')
            ->leftJoin('bank_info as A', 'bank_transfer.tf_bank', 'A.id')
            ->leftJoin('bank_info as B', 'bank_transfer.tt_bank', 'B.id')
            ->leftJoin('bank_acc as C', 'bank_transfer.tf_acc', 'C.id')
            ->leftJoin('bank_acc as D', 'bank_transfer.tt_acc', 'D.id')
            ->whereDate('date', '>=', $stdate)
            ->whereDate('date', '<=', $enddate)
            ->get();

        return DataTables()->of($bank_transfer)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $action = '<a data-id='.$row->vno.' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
            return $action;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function bankdepowithdraw_report()
    {
        return view('admin.pos.banking.depositwithdraw_report');
    }

    public function get_bankdepowithdraw_report(Request $req)
    {
        $stdate = $req['stdate'];
        $enddate = $req['enddate'];
        $type = $req['type'];

        if (!$stdate) {
            $stdate = date('Y-m-d');
        }
        if (!$enddate) {
            $enddate = date('Y-m-d');
        }

        $trow = "";

        if ($type == "withdraw") {

            $bank_transactions = DB::table('bank_transactions')->select("bank_transactions.id as transid", "bank_transactions.date as date", "bank_transactions.vno", "bank_transactions.seller_bank_acc_id","bank_transactions.withdraw as amount","bank_info.name","bank_acc.acc_name","bank_acc.acc_no")
            ->join('bank_info', 'bank_transactions.seller_bank_id', 'bank_info.id')
            ->join('bank_acc', 'bank_transactions.seller_bank_acc_id', 'bank_acc.id')
            ->where('bank_transactions.client_id', auth()->user()->client_id)
            ->where('bank_transactions.withdraw', '>', 0)
            ->whereBetween('bank_transactions.date', [$stdate, $enddate])
            ->get();

            foreach ($bank_transactions as $row) {
                $trow .= "<tr data-id ='$row->vno'><td>" . $row->date . "</td><td>" . $row->name . "</td><td>" .$row->acc_name." ".$row->acc_no."</td><td>" . $row->amount ."</td><td><a data-id ='$row->vno' title='Delete' href='#' class='delete'><span class='btn btn-xs btn-primary'><i class='mdi mdi-delete'></i></span></a></td></tr>";
            }

            return $trow;

        }
        if ($type == "deposit") {

            $bank_transactions = DB::table('bank_transactions')->select("bank_transactions.id as transid", "bank_transactions.date as date", "bank_transactions.vno", "bank_transactions.seller_bank_acc_id","bank_transactions.deposit as amount","bank_info.name","bank_acc.acc_name","bank_acc.acc_no")
            ->join('bank_info', 'bank_transactions.seller_bank_id', 'bank_info.id')
            ->join('bank_acc', 'bank_transactions.seller_bank_acc_id', 'bank_acc.id')
            ->where('bank_transactions.client_id', auth()->user()->client_id)
            ->where('bank_transactions.deposit', '>', 0)
            ->whereBetween('bank_transactions.date', [$stdate, $enddate])
            ->get();

            foreach ($bank_transactions as $row) {
                $trow .= "<tr data-id ='$row->vno'><td>" . $row->date . "</td><td>" . $row->name . "</td><td>" .$row->acc_name." ".$row->acc_no."</td><td>" . $row->amount ."</td><td><a data-id ='$row->vno' title='Delete' href='#' class='delete'><span class='btn btn-xs btn-primary'><i class='mdi mdi-delete'></i></span></a></td></tr>";
            }

            return $trow;
        }

    }




    public function delete_bank_transfer(Request $req){

        $id = $req['invoice'];
        // return $id;
        $deletebtransfer = BankTransfer::whereIn('vno', [$id])->delete();
        $deleteAccTrans = AccTransaction::whereIn('vno', [$id])->delete();
        $deletebtransaction = BankTransaction::whereIn('vno', [$id])->delete();

        if ($deletebtransfer == 1 || $deleteAccTrans == 1 || $deletebtransaction == 1) {
            $success = true;
            $message = "Data Deleted Successfully!";
        } else {
            $success = true;
            $message = "";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    //bank loan
    public function create_loan(Request $request)
    {
        $banks = DB::table('bank_info')->where('client_id', auth()->user()->client_id)->get();
        if ($request->isMethod('post')) {
            $data = $request->all();

            $maxid = (DB::table('bank_loans')->max('id') + 1);
            $invoice = "Loan-" . $maxid;

            $loan = new BankLoan;
            $loan->invoice_no = $invoice;
            $loan->bank_id = $data['bank_id'];
            $loan->bank_acc = $data['account_id'];
            $loan->loan_date = $data['inputLoanDate'];
            $loan->loan_expiry_date = $data['inputLoanExpireDate'];
            $loan->loan_amount = $data['inputLoanAmount'];
            $loan->interest_rate = $data['inputInterestRate'];
            $loan->total_amount = $data['inputTotalAmount'];
            $loan->installment_amount = $data['installmentAmount'];
            $loan->client_id = auth()->user()->client_id;
            $loan->status = 1;
            $loan->save();

            $user = Auth::id();
            $getBankName = DB::table('bank_info')->select('name')->where('id', $data['bank_id'])->first();

            $getBankAccNo = DB::table('bank_acc')->select('acc_no')->where('id', $data['account_id'])->first();

            $head = "Bank Loan";
            $description = "Loan " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no . " " . $invoice;
            $debit = 0;
            $credit = $data['inputLoanAmount'];

            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);
            AccTransaction::create([
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
                'vno' => $vno,
                'head' => $head,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $data['inputLoanDate'],
            ]);

            $head = $getBankName->name . " " . $getBankAccNo->acc_no;
            $description = "Loan Deposit " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no;
            $credit = 0;
            $debit = $data['inputLoanAmount'];

            AccTransaction::create([
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
                'vno' => $vno,
                'head' => $head,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $data['inputLoanDate'],
            ]);

            return redirect('/admin/bank_loan_create')->with('flash_message_success', 'Bank Loan Created Successfully!');
        }
        return view('admin.pos.bank-loans.create')->with(compact('banks'));
    }

    public function create_installment(Request $request)
    {

        $banks = DB::table('bank_info')
            ->where('client_id', auth()->user()->client_id)->get();
        $loans = DB::table('bank_loans')
            ->select('bank_loans.id', 'bank_loans.invoice_no', 'bank_loans.bank_id', 'bank_loans.bank_acc', 'bank_info.name', 'bank_acc.acc_no')
            ->join('bank_info', 'bank_loans.bank_id', 'bank_info.id')
            ->join('bank_acc', 'bank_loans.bank_acc', 'bank_acc.id')
            ->where('bank_loans.client_id', auth()->user()->client_id)
            ->get();
        //dd($request->all());

        if ($request->isMethod('post')) {
            $data = $request->all();

            $maxid = (DB::table('bank_installments')->max('id') + 1);
            $invoice = "Installment-" . $maxid;

            $installment = new BankInstallment;

            $installment->invoice_no = $invoice;
            $installment->installment_date = $data['inputDate'];
            $installment->loan_id = $data['inputLoan'];
            $installment->bank_id = $data['bank_id'];
            $installment->bank_acc = $data['account_id'];
            $installment->payment_type = $data['inputType'];
            $installment->amount = $data['inputAmount'];
            $installment->client_id = auth()->user()->client_id;
            $installment->save();

            $user = Auth::id();
            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);

            $getBankName = DB::table('bank_info')->select('name')->where('id', $data['bank_id'])->first();

            $getBankAccNo = DB::table('bank_acc')->select('acc_no')->where('id', $data['account_id'])->first();

            $get_loan = DB::table('bank_loans')->select('installment_amount', 'interest_rate')->where('id', $data['inputLoan'])->first();

            $interestRate = $get_loan->interest_rate;
            $installAmount = $get_loan->installment_amount;
            $realAmount = ($installAmount * 100) / (100 + $interestRate);
            $interest = $installAmount - $realAmount;

            if ($data['inputType'] == "Cash") {

                $head = "Cash In Hand";
                $description = "Loan Installment";
                $credit = $data['inputAmount'];
                $debit = 0;
                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);

                $head = "Bank Loan";
                $description = "Loan Installment in Cash";
                $credit = 0;
                $debit = $realAmount;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);

                $countHead = DB::table('acc_heads')->where('head', "Interest of Loan")->count();

                if ($countHead < 1) {
                    AccHead::create([
                        'user_id' => $user,
                        'client_id' => auth()->user()->client_id,
                        'parent_head' => "Expense",
                        'sub_head' => "Loan Expense",
                        'head' => "Interest of Loan"
                    ]);
                }

                $head = "Interest of Loan";
                $description = "Deposit Installment";
                $credit = 0;
                $debit = $interest;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);
            }

            if ($data['inputType'] == "bank") {

                $head = $getBankName->name . " " . $getBankAccNo->acc_no;
                $description = "Loan Deposit " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no;
                $credit = $interest;
                $debit = 0;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);

                $head = $getBankName->name . " " . $getBankAccNo->acc_no;
                $description = "Installment Deposit " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no;
                $credit = $realAmount;
                $debit = 0;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);

                $head = "Bank Loan";
                $description = "Installment Deposit " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no;
                $credit = 0;
                $debit = $realAmount;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);

                $countHead = DB::table('acc_heads')->where('head', "Interest of Loan")->count();

                if ($countHead < 1) {
                    AccHead::create([
                        'user_id' => $user,
                        'client_id' => auth()->user()->client_id,
                        'parent_head' => "Expense",
                        'sub_head' => "Loan Expense",
                        'head' => "Interest of Loan"
                    ]);
                }

                $head = "Interest of Loan";
                $description = "Installment Deposit " . $getBankName->name . " A/C: " . $getBankAccNo->acc_no;
                $credit = 0;
                $debit = $interest;

                AccTransaction::create([
                    'user_id' => $user,
                    'client_id' => auth()->user()->client_id,
                    'vno' => $vno,
                    'head' => $head,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $data['inputDate'],
                ]);
            }

            return redirect('/admin/create_installment')->with('flash_message_success', 'Installment Deposit Completed!');
        }

        return view('admin.pos.bank-loans.create-installment')->with(compact('banks', 'loans'));
    }

    public function get_installment_amount(Request $request)
    {
        $loan_id = $request['loan_id'];
        $get_loan = DB::table('bank_loans')->select('installment_amount')->where('id', $loan_id)->first();

        $data = $get_loan->installment_amount;
        return $data;
    }

    public function bank_loan_report()
    {
        return view('admin.pos.bank-loans.bank_loan-report');
    }

    public function bank_loan_report_date(Request $req)
    {
        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if (!$stdate) {
            $stdate = date('Y-m-d');
        }
        if (!$enddate) {
            $enddate = date('Y-m-d');
        }

        $loans = DB::table('bank_loans')->select('bank_loans.id', 'bank_loans.bank_id', 'bank_loans.invoice_no', 'bank_loans.loan_date', 'bank_loans.loan_expiry_date', 'bank_loans.loan_amount', 'bank_loans.interest_rate', 'bank_loans.total_amount', 'bank_loans.installment_amount', 'bank_loans.status', 'bank_info.name')
            ->join('bank_info', 'bank_loans.bank_id', 'bank_info.id')
            ->whereBetween('bank_loans.loan_date', [$stdate, $enddate])
            ->where('bank_loans.client_id', auth()->user()->client_id)
            ->get();

        $total_paid = 0;
        $total_unpaid = 0;
        $status = "";
        foreach ($loans as $index => $row) {
            $loan_id = $row->id;
            $row->total_paid = DB::table('bank_installments')->where('client_id', auth()->user()->client_id)->where('loan_id', $loan_id)->sum('amount');

            $row->total_unpaid = $row->total_amount - $row->total_paid;

            if ($row->total_unpaid > 0) {
                $row->status = "Running";
            } else {
                $row->status = "Paid";
            }
        }

        return datatables()->of($loans)->make(true);
    }

    public function installment_report()
    {
        return view('admin.pos.bank-loans.installment_report');
    }

    public function installment_report_date(Request $req)
    {
        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if (!$stdate) {
            $stdate = date('Y-m-d');
        }
        if (!$enddate) {
            $enddate = date('Y-m-d');
        }

        $installments = DB::table('bank_installments')->select('id', 'invoice_no', 'installment_date', 'bank_installments.loan_id', 'bank_id', 'bank_acc', 'payment_type', 'amount')
            ->whereBetween('bank_installments.installment_date', [$stdate, $enddate])
            ->where('bank_installments.client_id', auth()->user()->client_id)
            ->get();

        foreach ($installments as $index => $row) {

            if ($row->payment_type == "Cash") {
                $row->bank_name = "Cash Payment";
                $row->account_no = "Cash Payment";
            }
            if ($row->payment_type == "bank") {
                $getbank_name = DB::table('bank_info')->select('name')->where('id', $row->bank_id)->first();
                $row->bank_name = $getbank_name->name;

                $getaccount_no = DB::table('bank_acc')->select('acc_no')->where('id', $row->bank_acc)->first();
                $row->account_no = $getaccount_no->acc_no;
            }
        }

        return datatables()->of($installments)->make(true);
    }


    public function deleteBank($id)
    {
        $delete = BankInfo::find($id)->delete();

        if ($delete == 1) {
            $success = true;
            $message = " Bank Delete Success";
        } else {
            $success = true;
            $message = " Error";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

}
