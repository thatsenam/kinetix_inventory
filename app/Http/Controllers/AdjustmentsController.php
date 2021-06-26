<?php

namespace App\Http\Controllers;

use App\AdjustmentDetail;
use App\Models\Adjustment;
use App\Products;
use App\Warehouse;
use Illuminate\Http\Request;

class AdjustmentsController extends Controller
{


    public function index(Request $request)
    {

        $from_date = $request->from_date ?? today()->startOfMonth()->toDateString();
        $to_date = $request->to_date ?? today()->endOfMonth()->toDateString();
        $adjustments = Adjustment::with('warehouse')
            ->when($from_date != null, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('date', [$from_date, $to_date]);
            })
            ->latest()->get();

        return view('adjustments.index', compact('adjustments', 'from_date', 'to_date'));
    }

    public function details(Request $request)
    {

        $from_date = $request->from_date ?? today()->startOfMonth()->toDateString();
        $to_date = $request->to_date ?? today()->endOfMonth()->toDateString();
        $adetails = AdjustmentDetail::query()
            ->when($from_date != null, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('date', [$from_date, $to_date]);
            })
            ->latest()->get();

        return view('adjustments.details', compact('adetails', 'from_date', 'to_date'));
    }


    public function create()
    {
        $warehouses = Warehouse::pluck('name', 'id')->all();
        $products = Products::query()->get();

        return view('adjustments.create', compact('warehouses', 'products'));
    }


    public function store(Request $request)
    {

//        dd($request->all());

        $data = $this->getData($request);

        $adjustment = Adjustment::create($data);
        $this->adjustDetails($request, $adjustment);

        return redirect()->route('adjustments.adjustment.index')
            ->with('success_message', 'Adjustment was successfully added.');

    }


    public function show($id)
    {
        $adjustment = Adjustment::with('warehouse')->findOrFail($id);

        return view('adjustments.show', compact('adjustment'));
    }

    public function edit($id)
    {
        $adjustment = Adjustment::findOrFail($id);
        $warehouses = Warehouse::pluck('name', 'id')->all();
        $products = Products::query()->get();
        $ad = AdjustmentDetail::query()->where('adjustment_id', $adjustment->id)->get();

        return view('adjustments.edit', compact('adjustment', 'warehouses', 'products', 'ad'));
    }


    public function adjustDetails(Request $request, $adjustment)
    {

        $product_ids = $request->pid;
        $qnt = $request->qnt;
        $type = $request->type;
        foreach ($product_ids as $index => $product_id) {
            if (!$product_id) break;
            $q = $qnt[$index];
            $t = $type[$index];
            $add = $t == "Add" ? $q : 0;
            $sub = $t == "Subtract" ? $q : 0;
            AdjustmentDetail::create([
                'adjustment_id' => $adjustment->id,
                'pid' => $product_id,
                'add_qnt' => $add,
                'sub_qnt' => $sub,
                'warehouse_id' => $adjustment->warehouse_id,
                'date' => $adjustment->date]);
        }

    }

    public function update($id, Request $request)
    {


        $data = $this->getData($request);
        $adjustment = Adjustment::findOrFail($id);
        $adjustment->update($data);
        AdjustmentDetail::query()->where('adjustment_id', $adjustment->id)->delete();
        $this->adjustDetails($request, $adjustment);


        return redirect()->route('adjustments.adjustment.index')
            ->with('success_message', 'Adjustment was successfully updated.');

    }


    public function destroy($id)
    {

        $adjustment = Adjustment::findOrFail($id);
        AdjustmentDetail::query()->where('adjustment_id', $adjustment->id)->delete();
        $adjustment->delete();

        return redirect()->route('adjustments.adjustment.index')
            ->with('success_message', 'Adjustment was successfully deleted.');

    }


    protected function getData(Request $request)
    {
        $rules = [
            'warehouse_id' => 'required|nullable',
            'date' => 'required|nullable|string|min:1',
            'note' => 'string|min:1|max:1000|nullable',
        ];

        $data = $request->validate($rules);


        return $data;
    }

}
