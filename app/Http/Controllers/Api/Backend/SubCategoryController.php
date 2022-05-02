<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCategoriesResource;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class SubCategoryController extends Controller
{
    function index(Request $request){
        $sub_categories=new SubCategory();
        if($request->category_id!='')
        $sub_categories=$sub_categories::where('category_id',$request->category_id);
        $sub_categories=$sub_categories->latest('id')->get();
        return response()->success(SubCategoriesResource::collection($sub_categories), 'success');

    }
    function Add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:sub_categories,name',
            'category_id' => 'required|string|exists:categories,id',
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $sub_category=new SubCategory();
            $sub_category->name=$request->name;
            $sub_category->category_id=$request->category_id;
            $sub_category->save();
            return response()->success(new SubCategoriesResource($sub_category), 'success');
    }
    function show(Request $request){
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|string|exists:sub_categories,id',
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $sub_category=SubCategory::find($request->sub_category_id);
            
            return response()->success(new SubCategoriesResource($sub_category), 'success');
    }
    function update(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|exists:categories,id',
            'sub_category_id' => 'required|string|exists:sub_categories,id',

            'name' => 'required|string|unique:categories,name,'.$request->sub_category_id,
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $sub_category=SubCategory::find($request->category_id);
            $sub_category->name=$request->name;
            $sub_category->category_id=$request->category_id;
            $sub_category->save();
            return response()->success(new SubCategoriesResource($sub_category), 'success');
    }
    function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|string|exists:sub_categories,id',
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            SubCategory::where('id',$request->sub_category_id)->delete();
        return response()->success([], 'success');

    }
}