<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreate;
use App\Http\Requests\CategoryUpdate;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CategoryController extends Controller
{
    
    public function index(){
        if(Auth::user()->hasPermissionTo('can_read_category')) {
            return response()->json(Category::paginate(25));
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
    
    public function details($id, Request $request){
        if(Auth::user()->hasPermissionTo('can_read_category')) {
            $category = Category::with('features')->findOrFail($id);
            return response()->json($category, 200);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
    
    public function store(CategoryCreate $request){
        if(Auth::user()->hasPermissionTo('can_create_category')) {
            DB::beginTransaction();
            $data = collect($request->validated());
            $create_data = $data->only(['name'])->toArray();
            $newCategory = Category::create($create_data);
            foreach($data->get('features') as $featureID){
                $feature = Feature::findOrFail($featureID);
                $newCategory->features()->attach($feature);
            }
            $newCategory->save();
            DB::commit();
            return response()->json($newCategory, 201);  
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');     
    }

    public function update(Category $category, CategoryUpdate $request){
        if(Auth::user()->hasPermissionTo('can_update_category')) {
            $data = collect($request->validated());
            $update_data = $data->only(['name'])->toArray();
            DB::beginTransaction();
            $category->update($update_data);
            $category->features()->delete();
            foreach($data->get('features') as $featureID){
                $feature = Feature::findOrFail($featureID);
                $category->features()->attach($feature);
            }
            $category->save();
            return response()->json($category, 204);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }

    public function delete(Category $category, Request $request){
        if(Auth::user()->hasPermissionTo('can_delete_category')) {
            if($category->delete()){
                return response()->json($category->id, 204);
            }else {
                throw new HttpException(502, 'internal server error'); 
            }
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
}
