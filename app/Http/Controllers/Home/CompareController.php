<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CompareController extends Controller
{
    public function add(Product $product){
        if(session()->has('compareProducts')){
            if(in_array($product->id,session()->get('compareProducts'))){
                alert()->warning('محصول موردنظر به لیست مقایسه اضافه شده است','کاربر گرامی');
                return redirect()->back();
            }
            session()->push('compareProducts',$product->id);
        }else{
            session()->put('compareProducts',[$product->id]);

        }
        alert()->success('محصول مورد نظر به لیست مقایسه شما اضافه شد', 'موفق');
        return redirect()->back();

    }

    public function index(){
        if(session()->has('compareProducts')){
            $products=Product::findOrFail(session()->get('compareProducts'));
            return view('home.compare.index',compact('products'));
        }
        alert()->warning('در ابتدا باید محصولی را جهت مقایسه انتخاب کنید','کاربر گرامی');
        return redirect()->back();

    }

    public function remove($productId){
        if(session()->has('compareProducts')){
           foreach(session()->get('compareProducts') as $key=>$item) {
               session()->pull('compareProducts.'.$key);
           }
        }
        if(session()->get('compareProducts') == []){
            session()->forget('compareProducts');
            return redirect()->route('home.index');
        }
        alert()->warning('در ابتدا باید محصولی را جهت مقایسه انتخاب کنید','کاربر گرامی');
        return redirect()->back();
    }
}
