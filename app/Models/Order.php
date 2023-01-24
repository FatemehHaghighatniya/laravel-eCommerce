<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];


    public function getStatusAttribute($status)
    {
        switch ($status) {
            case '0':
                $status = 'در انتظار پرداخت';
                break;
            case '1':
                $status = 'پرداخت شده';
                break;
        }
        return $status;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaymentTypeAttribute($PaymentType)
    {
        switch ($PaymentType) {
            case 'pos':
                $PaymentType = 'دستگاه کارتخوان';
                break;
            case 'cash':
                $PaymentType = 'نقدی';
                break;

            case 'shabaNumber':
                $PaymentType = 'شماره شبا';
                break;
            case 'cardToCard':
                $PaymentType = 'نقدی';
                break;
            case 'online':
                $PaymentType = 'اینترنتی';
                break;
        }
        return $PaymentType;
    }


    public function getPaymentStatusAttribute($paymentStatus){
        return $paymentStatus == 1 ? 'موفق' : 'ناموفق';
    }

    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }

    public function address(){
        return $this->belongsTo(UserAddress::class);
    }
}
