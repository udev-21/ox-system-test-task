<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreate;
use App\Http\Requests\ProductUpdate;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class ProductController extends Controller
{
    public function index(){
        if(Auth::user()->hasPermissionTo('can_read_product')) {
            return response()->json(Product::with('features', 'featureValues')->paginate(25));
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }
    
    public function details(Product $product, Request $request){
        if(Auth::user()->hasPermissionTo('can_read_product')) {
            $product->features;
            $product->featureValues;
            $product->productFeatures();
            return response()->json($product, 200);
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }

    public function update(Product $product, ProductUpdate $request){
        if(Auth::user()->hasPermissionTo('can_update_product')) {
            $data = $request->validated();
            $category = Category::with('features')->findOrFail($data['category_id']);
            $requiredFeatureIDs = $category->features->pluck('id')->toArray();

            DB::beginTransaction();
            $product->category_id = $data['category_id'];
            $product->save();
            
            $product->features()->detach();

            try {
                // make sure all required features exists
                $missing = false;
                $productFeatures = array_unique(
                    array_map(function($val) {
                        return $val['id'] ?? null;   
                    }, $data['features'])
                );

                foreach($requiredFeatureIDs as $featureID) {
                    if(!in_array($featureID, $productFeatures)) {
                        $missing = true;
                        break;
                    }
                }

                if($missing){
                    return response()->json(['error'=>'missing required feature'], 400);
                }

                foreach($data['features'] ?? [] as $feature){
                    if(array_key_exists('id', $feature)){
                        if(in_array($feature['id'], $requiredFeatureIDs)){
                            $productFeature = ProductFeature::create(['product_id'=>$product->id, 'feature_id'=>$feature['id']]);
                            ProductFeatureValue::create(['product_feature_id'=>$productFeature->id, 'value'=>$feature['value']]);
                        }else {
                            DB::rollBack();
                            return response()->json(['error'=>'invalid value for feature_id'], 400);
                        }
                    }else {
                        $newFeature = Feature::create(['name'=>$feature['name']]);
                        $productFeature = ProductFeature::create(['product_id'=>$product->id, 'feature_id'=>$newFeature['id']]);
                        ProductFeatureValue::create(['product_feature_id'=>$productFeature->id, 'value'=>$feature['value']]);    
                    }
                }
                
                DB::commit();
                return response()->json($product);
            }catch (Throwable $e) {
                DB::rollBack();
                dd($e->getMessage());
            }
        }
        return response()->json(['error'=>'internal server error'], 500);
    }
    
    public function delete(Product $product, Request $request){
        if(Auth::user()->hasPermissionTo('can_delete_product')) {
            $product->features()->detach();
            if($product->delete()){
                return response()->json($product, 204);
            }else {
                throw new HttpException(502, 'internal server error'); 
            }
        }
        throw new UnauthorizedHttpException('you have no permission to do this action');
    }

    public function store(ProductCreate $request){
        $data = collect($request->validated());
        $category = Category::with('features')->findOrFail($data['category_id']);
        $requiredFeatureIDs = $category->features->pluck('id')->toArray();
        DB::beginTransaction();
        try {
            // make sure all required features exists
            $missing = false;
            $productFeatures = array_unique(
                array_map(function($val) {
                    return $val['id'] ?? null;   
                }, $data->get('features'))
            );

            foreach($requiredFeatureIDs as $featureID) {
                if(!in_array($featureID, $productFeatures)) {
                    $missing = true;
                    break;
                }
            }

            if($missing){
                return response()->json(['error'=>'missing required feature'], 400);
            }

            $product = Product::create($data->only(['name','category_id'])->toArray());
            foreach($data['features'] ?? [] as $feature){
                if(array_key_exists('id', $feature)){
                    if(in_array($feature['id'], $requiredFeatureIDs)){
                        $productFeature = ProductFeature::create(['product_id'=>$product->id, 'feature_id'=>$feature['id']]);
                        ProductFeatureValue::create(['product_feature_id'=>$productFeature->id, 'value'=>$feature['value']]);
                    }else {
                        DB::rollBack();
                        return response()->json(['error'=>'invalid value for feature_id'], 400);
                    }
                }else {
                    $newFeature = Feature::create(['name'=>$feature['name']]);
                    $productFeature = ProductFeature::create(['product_id'=>$product->id, 'feature_id'=>$newFeature['id']]);
                    ProductFeatureValue::create(['product_feature_id'=>$productFeature->id, 'value'=>$feature['value']]);    
                }
            }
            
            DB::commit();
            return response()->json($product);
        }catch (Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
        return response()->json(['error'=>'internal server error'], 500);
    }
}
