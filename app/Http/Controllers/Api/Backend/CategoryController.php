<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class CategoryController extends Controller
{
    function index(Request $request){
        $categories=Category::latest('id')->get();
        return response()->success(CategoriesResource::collection($categories), 'success');

    }
    function Add(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $category=new Category();
            $category->name=$request->name;
            $category->save();
            return response()->success(new CategoriesResource($category), 'success');
    }
    function show(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|exists:categories,id',
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $category=Category::find($request->category_id);
            
            return response()->success(new CategoriesResource($category), 'success');
    }
    function update(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|exists:categories,id',
            'name' => 'required|string|unique:categories,name,'.$request->category_id,
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $category=Category::find($request->category_id);
            $category->name=$request->name;
            $category->save();
            return response()->success(new CategoriesResource($category), 'success');
    }
    function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|string|exists:categories,id',
           
        ]);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
        Category::where('id',$request->category_id)->delete();
        return response()->success([], 'success');

    }
}