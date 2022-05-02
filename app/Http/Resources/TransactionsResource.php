<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'payer' => $this->user ? $this->user->name : '',
            'amount' => $this->amount,
            'total' => $this->total,
            'category' => $this->category ? $this->category->name : '',
            'sub_category' => $this->subCategory ? $this->subCategory->name : '',
            'due_to' => $this->due_to,
            'vat' => $this->vat_precentage,
            'is_vat' => $this->vat,
            'payed' => $this->paid,
            'payment_records' => TransactionPaymentsResource::collection($this->paymentRecords),
            'status' => $this->getStatus($this)

        ];
    }
    function getStatus($object)
    {
        $currentDay = strtotime(Carbon::now());
        $due_date = strtotime($object->due_to);

        if ($object->paid == $object->total)
            return 'Paid';
        elseif ($currentDay > $due_date) {
            return 'overdue';
        } else {
            return 'Outstandng';
        }
    }
}
