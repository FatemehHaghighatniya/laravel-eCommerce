<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class AdminController extends Controller
{
    public function index()
    {
        $month=12;
        $successTransactions=Transaction::getData($month,1);
        $successTransactionsChart=$this->chart($successTransactions,$month);

        $unsuccessTransactions=Transaction::getData($month,0);
        $unsuccessTransactionsChart=$this->chart($unsuccessTransactions,$month);

        return view('admin.dashboard',[
            'successTransactions'=>array_values($successTransactionsChart),
            'unsuccessTransactions'=>array_values($unsuccessTransactionsChart),
            'labels'=>array_keys($successTransactionsChart),
            'transactionsCount'=>[$successTransactions->count(),$unsuccessTransactions->count()]
        ]);
    }

    public function chart($transactions, $month)
    {
        $monthName=$transactions->map(function($item){
           return verta($item->created_at)->format('%B %y');
        });

        $amount=$transactions->map(function($item){
            return $item->amount;
        });
        foreach($monthName as $key=>$value) {
            if (!isset($result[$value])) {
                $result[$value] = 0;
            }
            $result[$value] += $amount[$key];
        }
            if(count($result) != $month){
                for ($i = 0 ;$i<$month; $i++){
                    $monthName=verta()->subMonth($i)->format('%B %y');
                    $shamsiMonthName[$monthName] = 0;
                }
                return array_reverse(array_merge($shamsiMonthName,$result));
            }
            return $result;
        }


}
