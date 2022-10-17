<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeatureCreate;
use App\Http\Requests\FeatureUpdate;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FeatureController extends Controller
{
    public function index(){
        if(Auth::user()->hasPermissionTo('can_read_feature')) {
            return response()->json(Feature::paginate(25));
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
        
    }
    
    public function details($id, Request $request){
        if(Auth::user()->hasPermissionTo('can_read_feature')) {
            $feature = Feature::findOrFail($id);
            return response()->json($feature, 200);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
    
    public function store(FeatureCreate $request){
        if(Auth::user()->hasPermissionTo('can_create_feature')) {
            $create_data = $request->validated();
            $feature = Feature::create($create_data);
            return response()->json($feature, 201);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');     
    }

    public function update(Feature $feature, FeatureUpdate $request){
        if(Auth::user()->hasPermissionTo('can_update_feature')) {
            $data = $request->validated();
            $feature->update($data);
            return response()->json($feature, 204);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }

    public function delete(Feature $feature, Request $request){
        if(Auth::user()->hasPermissionTo('can_delete_feature')) {
            if($feature->delete()){
                return response()->json($feature, 204);
            }else {
                throw new HttpException(502, 'internal server error'); 
            }
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
}
