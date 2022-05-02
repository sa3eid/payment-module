<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model
{   protected $with=['category','paymentRecords','user','subcategory'];
    protected $casts=['amount'=>'integer'];
    function getPaidAttribute(){
        return $this->paymentRecords()->sum('amount');
    }
    function paymentRecords(){
        return $this->hasMany(TransactionPayment::class,'transaction_id')->latest('id');
    }
    function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    function subCategory(){
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }
}