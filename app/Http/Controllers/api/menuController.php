<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

use App\Category;

class menuController extends Controller
{
    public function index(){

        //return Category::all();

        return response()->json([
            'success' => true,
            'message' => 'Message',
            'data' => []
        ]);
    }

    public function category($id){

        $category = Category::find($id);

        if(is_null($category)){

            return response()->json(['Record Not Found', 404]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message',
            'data' => $category
        ], 200);
    }

    public function categorySave(Request $req){

        $rules = [
            'name' => 'required',
            'pid' => 'required'
        ];

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $pid = $req['pid'];
        $name = $req['name'];
        $description = $req['description'];
        $url = $req['url'];
        $status = $req['status'];
        $image = $req['image'];

//        DB::table('categories')->insert([
            Category::create([
            'parent_id' =>   $pid,
            'name' => $name,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'image' => $image

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Inserted',
            'data' => []
        ], 201);

    }

}
