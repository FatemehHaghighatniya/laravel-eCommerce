<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Product;
use App\Models\Province;
use App\Models\UserAddress;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
class CartController extends Controller
{
    public function add(Request $request)
    {
//            dd($request->all());
        $request->validate([
            'product_id' => 'required',
            'qtybutton' => 'required'
        ]);
        $product = Product::findOrfail($request->product_id);
        $productVariation = ProductVariation::findOrFail(json_decode($request->variation)->id);
        if ($request->qtybutton > $productVariation->quantity) {
            alert()->error('تعداد وارد شده از محصول صحیح نمی باشد', 'کاربر گرامی');
            return redirect()->back();
        }
        $rowId = $product->id . '-' . $productVariation->id;
        if (\Cart::get($rowId) == null) {
            \Cart::add(array(
                'id' => $rowId,
                'name' => $product->name,
                'price' => $productVariation->is_sale ? $productVariation->sale_price : $productVariation->price,
                'quantity' => $request->qtybutton,
                'attributes' => $productVariation->toArray(),
                'associatedModel' => $product


            ));
        } else {
            alert()->warning('این محصول قبلا به سبد خرید شما اضافه شده است ', 'کاربر گرامی');
            return redirect()->back();
        }
        alert()->success('محصول با موفقیت به سبد خرید شما اضافه شد', 'موفق');
        return redirect()->back();
    }

    public function remove($rowId)
    {
        \Cart::remove($rowId);
        alert()->warning('محصول مورد نطر با موفقیت از سبد خرید حذف شد', 'موفق');
        return redirect()->back();
    }

    public function index()
    {
        return view('home.cart.index');
    }

    public function clear()
    {
        \Cart::clear();
        alert()->warning('سبد خرید شما حذف شد', 'موفق');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'qtybutton' => 'required'
        ]);
        foreach ($request->qtybutton as $rowId => $quantity) {
            $item = \Cart::get($rowId);
            if ($quantity > $item->attribute->quantity) {
                alert()->error('تعداد وارد شده از محصول صحیح نمی باشد', 'کاربر گرامی');
                return redirect()->back();
            }
            \Cart::update($rowId, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $quantity
                ),
            ));

        }
        alert()->success('محصول با موفقیت ویرایش شد ', 'موفق');
        return redirect()->back();
    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        if (!auth()->check()) {
            alert()->error('برای استفاده از کد تخفیف نیاز هست ابتدا وارد وب سایت شوید', 'دقت کنید');
            return redirect()->back();
        }

        $result = checkCoupon($request->code);

        if (array_key_exists('error', $result)) {
            alert()->error($result['error'], 'دقت کنید');
        } else {
            alert()->success($result['success'], 'باتشکر');
        }
        return redirect()->back();
    }

    public function checkout(){
        $addresses=UserAddress::where('user_id',auth()->id())->get();
        $provinces=Province::all();
        return view('home.cart.checkout',compact('addresses','provinces'));
    }

    public function usersProfileIndex()
    {
        $orders=Order::where('user_id',auth()->id())->get();
        return view('home.users_profile.orders',compact('orders'));
    }
}
