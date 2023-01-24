<?php
namespace App\Channels;
use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;
class SmsChannel{

    public function Send($notifiable,Notification $notification){
        return 'Done!';

        $receptor=$notifiable->cellphone;
        $type=1;
        $template="test1";
        $param1=$notification->code;

        $api=new GhasedakApi(env('GHASEDAK_API_KEY'));
        $api->verify($receptor,$type,$template,$param1);

    }
}
