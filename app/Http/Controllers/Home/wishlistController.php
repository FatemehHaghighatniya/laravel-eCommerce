<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class wishlistController extends Controller
{
    public function add(Product $product)
    {
        if (auth()->check()) {
            if ($product->checkUserWishlist(auth()->id())) {
                alert()->warning('محصول مورد نظر به لیست علاقه مندی ها اضافه شده است ', 'کاربر گرامی');
                return redirect()->back();
            } else {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ]);
            }
            alert()->success('محصول مورد نظر به لیست علاقه مندی ها اضافه شد', 'موفق');
            return redirect()->back();
        } else {
            alert()->warning('جهت اضافه به لیست علاقه مندی ها ابتدا باید وارد سایت شوید', ' کاربر گرامی');
            return redirect()->back();
        }
    }

    public function remove(Product $product)
    {
        if (auth()->check()){
            $wishlist=Wishlist::where('user_id',auth()->id())->where('product_id',$product->id)->firstOrFail();
            if($wishlist){
                Wishlist::where('user_id',auth()->id())->where('product_id',$product->id)->delete();

                alert()->success('محصول مورد نظر از لیست علاقه مندی ها حذف شد', 'موفق');
                return redirect()->back();
            }
        }else{
            alert()->warning('جهت حذف از لیست علاقه مندی ها ابتدا باید وارد سایت شوید', 'کاربر گرامی ');
            return redirect()->back();
        }
    }

    public function userProfileIndex(){
        $wishlist=Wishlist::where('user_id',auth()->id())->get();
        return view('home.users_profile.wishlist',compact(['wishlist']));
                }
}
