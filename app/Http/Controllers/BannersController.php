<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;

class BannersController extends Controller
{
    public function addBanner(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $banner = new Banner;
            $banner->title = $data['inputName'];
            $banner->status = $data['inputStatus'];
            $banner->type = $data['inputType'];
            $banner->link = $data['inputURL'];

            if($request->hasFile('inputImage')){
                $file = $request->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().$file->getClientOriginalExtension();
                $file->move('images/banners/', $img_name);
                $banner->image = $img_name;
            }
            $banner->save();
            return redirect('/admin/add_banner')->with('flash_message_success', 'Banner Created Successfully!');
        }
        return view('admin.add_banner');
    }

    public function viewBanners(){
        $banners = Banner::get();
        return view('admin.view_banners')->with(compact('banners'));
    }

    public function editBanner(Request $req, $id = null){
        $banners = Banner::where(['id'=>$id])->get();
        if($req->isMethod('post')){
            $data = $req->all();
            Banner::where(['id'=>$id])->update(['title'=>$data['inputName'],'link'=>$data['inputURL'],'status'=>$data['inputStatus'],'type'=>$data['inputType'],]);
            $banner = Banner::find($id);
            if($req->hasFile('inputImage')){
                $prev_img = $banner->image;
                $image_path = 'images/banners/'.$prev_img;
                if(file_exists($image_path)) {
                     @unlink($image_path);
                }
                $file = $req->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
                $file->move('images/banners/', $img_name);
                $banner->image = $img_name;
            }
            $banner->save();
            return redirect('/admin/view_banners')->with('flash_message_success', 'Banner Updated Successfully!');
        }
        return view('admin.edit_banner')->with('banners', $banners)->with('id', $id);
    }

    public function deleteBanner($id)
    {
        $delete = Banner::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Banner deleted successfully!";
        } else {
            $success = true;
            $message = "Banner not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
