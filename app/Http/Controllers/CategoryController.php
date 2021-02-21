<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Category;

class CategoryController extends Controller
{
    public function CreateCategory(Request $request){
        $category = Category::orderBy('name', 'ASC')->where('client_id',auth()->user()->client_id)->get();
        if($request->isMethod('post')){
            $slug = Str::slug($request->cat_name);
            $slug_count = Category::where('url', $slug)->count();
            if($slug_count > 0){
                $x = Date('ms')."-".rand(1000,10000);
                $slug = $slug.$x;
            }
            $type = $request->cat_type;
            if($type == ''){
                $type = 0;
            }
            $data = $request->all();
            $category = new Category;
            $category->parent_id = $data['parent_cat'];
            $category->name = $data['cat_name'];
            $category->description = $data['cat_desc'];
            $category->url = $slug;
            $category->featured = $type;
            $category->status = $data['cat_status'];
            $category->vat = $data['inputVat'];

            if($request->hasFile('inputImage')){
                $file = $request->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
                $file->move('images/categories/', $img_name);
                $category->image = $img_name;
            }

            $category->save();
            return redirect('/admin/create_category')->with('flash_message_success', 'Category Created Successfully!');
        }
        return view('admin.create_category')->with('category', $category);
    }

    public function viewCategories(){
        $categories = Category::orderBy('name', 'ASC')->where('client_id',auth()->user()->client_id)->get();
        return view('admin.view_categories')->with('categories', $categories);
    }

    public function editCategory(Request $req, $id = null){
        $category = Category::where(['id'=>$id])->get();
        $categories = Category::orderBy('id','ASC')->get();
        $catArray = [];
        if($categories != null){
            foreach($categories as $cat){
            $catArray[] = $cat->name;
            $catArray[] = $cat->id;
                $subcats = Category::orderBy('id','ASC')->where('parent_id', $cat->id )->get();
                if($subcats != null){
                    foreach($subcats as $subcat){
                        $catArray[] = $cat->name.">".$subcat->name;
                        $catArray[] = $subcat->id;
                    }
                }
            }
        }

        if($req->isMethod('post')){
            $data = $req->all();
            $slug = Str::slug($req->cat_url);
            if($slug == ''){
                $slug = Str::slug($req->cat_name);
            }
            Category::where(['id'=>$id])->update(['name'=>$data['cat_name'],'description'=>$data['cat_desc'],'parent_id'=>$data['parent_cat'],'featured'=>$data['cat_type'],'status'=>$data['cat_status'],'vat'=>$data['vat'],'url'=>$slug,]);
            $Ucat = Category::find($id);
            // dd($Ucat);
            if($req->hasFile('inputImage')){
                $prev_img = $Ucat->image;
                $image_path = 'images/categories/'.$prev_img;
                if(file_exists($image_path)) {
                    @unlink($image_path);
                }
                $file = $req->file('inputImage');
                $basename = basename($file);
                $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
                $file->move('images/categories/', $img_name);
                $Ucat->image = $img_name;
            }
            $Ucat->save();
            return redirect('/admin/view_categories')->with('flash_message_success', 'Category Updated Successfully!');
        }
        return view('admin.edit_category')->with('catArray', $catArray)->with('category', $category)->with('id', $id);
    }

    public function deleteCategory($id)
    {
        $delete = Category::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Category deleted successfully!";
        } else {
            $success = true;
            $message = "Category not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }


}
