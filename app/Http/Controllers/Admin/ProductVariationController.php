<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductVariationController extends Controller
{
    public function store($variations,$attributeId,$product){
        $counter=count($variations['value']);
        for($i=0;$i<$counter;$i++){
            ProductVariation::create([
                'attribute_id'=>$attributeId,
                'product_id'=>$product->id,
                'value'=>$variations['value'][$i],
                'price'=>$variations['price'][$i],
                'quantity'=>$variations['quantity'][$i],
                'sku'=>$variations['sku'][$i]

            ]);
        }
    }


    public function update($variationId){
        foreach($variationId as $key=>$value){
            $productVariation=ProductVariation::findOrFail($key);
            $productVariation->update([
                'price'=>$value['price'],
                'quantity'=>$value['quantity'],
                'sku'=>$value['sku'],
                'sale_price'=>$value['sale_price'],
                'date_on_sale_from'=>$value['date_on_sale_from'],
                'date_on_sale_to'=>$value['date_on_sale_to'],
            ]);

        }
    }


    public function change($variation,$attributeId,$product){
        ProductVariation::where('product_id',$product->id)->delete();
        $counter=count($product->attributes['value']);
        for($i=0;$i<count;$i++){

            ProductVariation::creat([
                'attribute_id'=>$attributeId,
                'product_id'=>$product->id,
                'value'=>$variation['value'][$i],
                'price'=>$variation['price'][$i],
                'quantity'=>$variation['quantity'][$i],
                'sku'=>$variation['sku'][$i]
            ]);
        }
    }
}
