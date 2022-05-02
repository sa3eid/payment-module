<?php
namespace App\Http\Controllers\Api\Backend;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionsResource;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class TransactionController extends Controller
{
    function index(Request $request){
        $transactions=new Transaction();
        if($request->has('search')){
            $transactions=$this->search($transactions,$request);
        }
        $transactions=$transactions->latest('id')->paginate(20);
        return response()->success(['data'=>TransactionsResource::collection($transactions),'current_page'=>$transactions->currentPage(),'lastpage'=>$transactions->lastPage()], 'success'); 
    }
    private function search($object,$request){
        foreach($request->search as $key=>$value){
          if($value!='')
        $object=$object->where($key,$value);
        }
        return $object;

    }
    function add(Request $request){
        $rules=[
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'sometimes|nullable|exists:sub_categories,id',
            'amount'=>['required','regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
            'user_id'=>'required|exists:users,id',
            'due_to'=>'required|date|after:yesterday',
      
            'is_vat'=>'required|boolean'];
            if($request->is_vat==1){
                $rules['vat_precentage']='required|numeric|min:1|max:100';
            }
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails())
            return response()->error($validator->errors()->all());
            $transaction=new Transaction();
            $transaction->category_id=$request->category_id;
            $transaction->sub_category_id=$request->sub_category_id;
            $transaction->amount=$request->amount;
            $transaction->user_id=$request->user_id;
            $transaction->due_to=$request->due_to;
            $transaction->is_vat=$request->is_vat;
            $transaction->vat_precentage=$request->vat_precentage;
            if($request->is_vat==1)
            $transaction->total=$request->amount+($request->vat_precentage*$request->amount/100);
            else
            $transaction->total=$request->amount;
            $transaction->save();
            return response()->success(new TransactionsResource($transaction), 'success'); 
    }
  
    function addPayment(Request $request){

    $rules=['transaction_id'=>'required|exists:transactions,id',
        'amount'=>['required','regex:/^(\d+(\.\d*)?)|(\.\d+)$/'],
        'details'=>'sometimes|nullable',
        'payment_method'=>'required'
    ];
    $validator = Validator::make($request->all(),$rules);
    if ($validator->fails())
        return response()->error($validator->errors()->all());
        $transactioPayment=new TransactionPayment();
        $transactioPayment->transaction_id=$request->transaction_id;
        $transactioPayment->amount=$request->amount;
        $transactioPayment->details=$request->details;
        $transactioPayment->payment_method=$request->payment_method;
        $transactioPayment->paid_on=Carbon::now();
        $transactioPayment->save();
        return response()->success(new TransactionsResource(Transaction::find($transactioPayment->transaction_id)), 'success'); 
    }
}