<?php

use App\AccHead;
use App\AccTransaction;
use Illuminate\Support\Facades\DB;

if (!function_exists('addOrUpdateOpeningBalance')) {

    function addOrUpdateOpeningBalance($head_id, $head, $amount, $entry_type)
    {
        $debit = 0;
        $credit = 0;
        if ($entry_type == "Cr") $credit = $amount; else $debit = $amount;

        $txn = AccTransaction::firstOrNew(['head' => $head, 'description' => 'OpeningBalance', 'type' => AccHead::class, 'type_id' => $head_id]);
        $txn->credit = $credit;
        $txn->debit = $debit;
        $txn->date = date("Y/m/d");
        $txn->save();
    }
}

if (!function_exists('is_what_perc_of')) {
    function is_what_perc_of($is, $of)
    {
        $result = $is / $of;
        return number_format($result * 100, 2);
    }
}

if (!function_exists('remove_sales_invoice')) {
    function remove_sales_invoice($invoice, $hardDelete = true)
    {

        if ($hardDelete) {
            DB::table('sales_invoice')->where('invoice_no', $invoice)->delete();
        }

        $get_sales_details = DB::table('sales_invoice_details')->where('invoice_no', $invoice)->get();

        foreach ($get_sales_details as $row) {

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock + $qnt);

            DB::table('products')->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('sales_invoice_details')->where('invoice_no', $invoice)->delete();

        DB::table('bank_transactions')->where('invoice_no', $invoice)->delete();


        $get_accounts = DB::table('acc_transactions')->where('description', 'like', '%' . $invoice)->get();

        foreach ($get_accounts as $row) {

            $vno = $row->vno;

            DB::table('acc_transactions')->where('vno', $vno)->delete();
        }
    }
}

?>
