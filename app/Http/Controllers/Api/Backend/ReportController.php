<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    function index(Request $request){
      $rules=[
          'from_date'=>'required|date',
          'to_date'=>'required|date',
    ];
    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails())
        return response()->error($validator->errors()->all());
        $reports=Transaction::whereDate('created_at','>=',$request->from_date)
        ->whereDate('created_at','<=',$request->to_date)
        ->select(DB::raw('YEAR(transactions.created_at) year, MONTH(transactions.created_at) month'))
        ->groupBy(['year','month'])
        ->get();
        $reportData=[];
        foreach($reports as $key=>$report){
            $reportData[$key]['month']=$report->month;
            $reportData[$key]['year']=$report->year;
        $transactions=Transaction::whereMonth('created_at',$report->month)
        ->whereYear('created_at',$report->year)->get();
        $paid=0;
        $due=0;
        $upstandang=0;
        foreach($transactions as $one){
           $date=$report->year.'-';
            $date.=$report->month<10?'0':'';
            $date.=$report->month;
                                    $transactionPay=$one->paymentRecords()->whereMonth('paid_on',$report->month)
            ->whereYear('paid_on',$report->year)->sum('amount');
            
            $paid+=$transactionPay;
        
            if($one->total>$transactionPay&&(strtotime($one->due_to)>strtotime(date('Y-m-t',strtotime($date)))))
            $upstandang+=$one->total-$transactionPay;
            elseif($one->total>$transactionPay&&(strtotime($one->due_to)<strtotime(date('Y-m-t',strtotime($date)))))
            $due+=$one->total-$transactionPay;
        }
        $reportData[$key]['due']=$due;
        $reportData[$key]['paid']=$paid;
        $reportData[$key]['upstandang']=$upstandang;
        }
        return response()->success($reportData, 'success'); 

    }
   
}