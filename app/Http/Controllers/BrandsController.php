<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Brands;

class BrandsController extends Controller
{
    public function CreateBrand(Request $request){
        if($request->isMethod('post')){
            $slug = Str::slug($request->inputName);
            $slug_count = Brands::where('url', $slug)->count();
            if($slug_count > 0){
                $x = Date('ms')."-".rand(1000,10000);
                $slug = $slug.$x;
            }
            $data = $request->all();
            $brand = new Brands;
            $brand->name = $data['inputName'];
            $brand->description = $data['inputDesc'];
            $brand->url = $slug;
            $brand->status = $data['inputStatus'];

            if($request->hasFile('inputImage')){
                $file = $request->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
                $file->move('images/brands/', $img_name);
                $brand->image = $img_name;
            }
            $brand->save();
            return redirect('/admin/create_brand')->with('flash_message_success', 'Brand Created Successfully!');
        }
        return view('admin.create_brand');
    }

    public function viewBrands(){

        $product = Products::all()->pluck('cat_id')->toArray();


        $brands = Brands::orderBy('name', 'ASC')
            ->where('client_id',auth()->user()->client_id)
            ->get()->map(function ($brand) use ($product){
                $brand['used'] = false;
                if (in_array($brand->id , $product)){
                    $brand['used'] = true;
                }
                return $brand;
            });
        return view('admin.view_brands')->with('brands', $brands);
    }

    public function editBrand(Request $req, $id = null){
        $brands = Brands::where(['id'=>$id])->get();
        if($req->isMethod('post')){
            $data = $req->all();
            Brands::where(['id'=>$id])->update(['name'=>$data['inputName'],'description'=>$data['inputDesc'],'status'=>$data['inputStatus'],'url'=>$data['inputURL'],]);
            $brand = Brands::find($id);
            if($req->hasFile('inputImage')){
                $prev_img = $brand->image;
                $image_path = 'images/brands/'.$prev_img;
                if(file_exists($image_path)) {
                     @unlink($image_path);
                }
                $file = $req->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
                $file->move('images/brands/', $img_name);
                $brand->image = $img_name;
            }
            $brand->save();
            return redirect('/admin/view_brands')->with('flash_message_success', 'Brand Updated Successfully!');
        }
        return view('admin.edit_brand')->with('brands', $brands)->with('id', $id);
    }

    public function deleteBrand($id)
    {
        $delete = Brands::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Brand deleted successfully!";
        } else {
            $success = true;
            $message = "Brand not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }


}
