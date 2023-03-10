<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\orderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
            'address_id' => 'required'
        ]);
        if ($validator->fails()) {
            alert()->error('انتخاب آدرس تحویل سفارش الزامی می باشد', 'کاربر گرامی');
            return redirect()->back();
        }

        $checkCart = $this->checkCart();
        if (array_key_exists('error', $checkCart)) {
            alert()->error($checkCart['error'], 'کاربر گرامی');
            return redirect()->route('home.index');
        }
        $amounts = $this->getAmounts();
        if (array_key_exists('error', $amounts)) {
            alert()->error($amounts['error'], 'کاربر گرامی');
            return redirect()->route('home.index');
        }


        $api = 'test';
        $amount = $amounts['paying_amount'].'0';
        $redirect = route('home.payment-verify');
        $result = $this->send($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null);
        $result = json_decode($result);
        if ($result->status) {

            $createOrder = $this->createOrder($request->address_id,$amounts,$result->token,'pay');
            if(array_key_exists('error',$createOrder)){
                alert()->error($createOrder['error'],'کاربر گرامی');
                return redirect()->back();
            }
            $go = "https://pay.ir/pg/$result->token";
           return redirect()->to($go);
        } else {
            alert()->error($result->errorMessage,'کاربر گرامی')->persistent('حله');
            return redirect()->back();
        }
    }

    public function paymentVerify(Request $request)
    {
        $api = 'test';
        $token = $request->token;
        $result = json_decode($this->verify($api, $token));
        if (isset($result->status)) {
            if ($result->status == 1) {
                $updateOrder=$this->updateOrder($token,$result->transId);
                if(array_key_exists('error',$updateOrder)){
                    alert()->error($updateOrder['error'],'کاربر گرامی')->persistent('حله');
                    return redirect()->back();
                     }
                \Cart::clear();
                alert()->success('پرداخت با موفقیت انجام شد;شماره تراکنش : '.$result->transId , 'کاربر گرامی');
                return redirect()->route('home.index');
            } else {
                alert()->error('پرداخت با خطا مواجه شد;شماره وضعیت : '.$result->status , 'کاربر گرامی');
                return redirect()->route('home.index');
            }
        } else {
            if ($result->status == 0) {
                alert()->error('پرداخت با خطا مواجه شد;شماره وضعیت : '.$result->status , 'کاربر گرامی');
                return redirect()->route('home.index');
            }
        }
    }

    public function send($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api' => $api,
            'amount' => $amount,
            'redirect' => $redirect,
            'mobile' => $mobile,
            'factorNumber' => $factorNumber,
            'description' => $description,
        ]);
    }

    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }


    public function verify($api, $token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' => $api,
            'token' => $token,
        ]);
    }




    public function checkCart()
    {
        if (\Cart::isEmpty()) {
            return ['error' => 'سبد خرید شما خالی است'];
        }
        foreach (\Cart::getContent() as $item) {
            $variation = ProductVariation::find($item->attributes->id);
            $price = $variation->is_sale ? $variation->sale_price : $variation->price;

            if ($item->price != $price) {
                \Cart::clear();
                return ['error' => 'قیمت محصول تغییر پیدا کرده است'];
            }
            if ($item->quantity > $variation->quantity) {
                \Cart::clear();
                return ['error' => 'تعداد محصول تغییر پیدا کرده است'];
            }
            return ['success' => 'success!'];
        }
    }

    public function getAmounts()
    {
        if (session()->has('coupon')) {
            $checkCoupon = checkCoupon(session()->get('coupon.code'));
            if (array_key_exists('error', $checkCoupon)) {
                return $checkCoupon;
            }
        }
        return [
            'total_amount' => \Cart::getTotal() + cartTotalSaleAmount(),
            'delivery_amount' => cartTotalDeliveryAmount(),
            'coupon_amount' => session()->has('coupon') ? session()->get('coupon.amount') : 0,
            'paying_amount' => cartTotalAmount()

        ];
    }

    public function createOrder($addressId,$amounts,$token,$getwayName)
    {
        try {
            DB::beginTransaction();

            $order=Order::create([
                'user_id'=>auth()->id(),
                'address_id'=>$addressId,
                'coupon_id'=>session()->has('coupon') ? session()->get('coupon.id') : null,
                'total_amount'=>$amounts['total_amount'],
                'delivery_amount'=>$amounts['delivery_amount'],
                'coupon_amount'=>$amounts['coupon_amount'],
                'paying_amount'=>$amounts['paying_amount'],
                'payment_type'=>'online',
                 ]);

            foreach(\Cart::getContent() as $item){
                orderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$item->associatedModel->id,
                    'product_variation_id'=>$item->attributes->id,
                    'price'=>$item->price,
                    'quantity'=>$item->quantity,
                    'subtotal'=>($item->price*$item->quantity),
                     ]);

                Transaction::create([
                    'user_id'=> auth()->id(),
                    'order_id'=>$order->id,
                    'amount'=>$amounts['paying_amount'],
                    'token'=>$token,
                    'gateway_name'=>$getwayName,
                ]);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['error'=> $ex->getMessage()];
        }
        return ['success'=>'success!'];
    }

    public function updateOrder($token,$refId){
        try {
            DB::beginTransaction();
            $transaction=Transaction::where('token',$token)->firstOrFail();
            $transaction->update([
                'ref_id'=>$refId,
                'status'=>1
            ]);
            $order=Order::findOrFail($transaction->order_id);
            $order->update([
                'status'=>1,
                'payment_status'=>1
            ]);
            foreach (\Cart::getContent() as $item) {
                $variation=ProductVariation::findOrFail($item->attributes->id);
                $variation->update([
                    'quantity'=>$variation->quantity - $item->quantity
                ]);

            }


            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['error'=> $ex->getMessage()];
        }
        return ['success'=>'success!'];
    }

}
