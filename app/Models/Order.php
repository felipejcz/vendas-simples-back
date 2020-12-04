<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['customer_id', 'status', 'description'];

    protected $appends = ['amount'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function getAmountAttribute()
    {
        $list = OrderItems::where('order_id', $this->id)->get();
        $list->load('product');
        $amount = 0;

        foreach ($list as $item) {
            $amount += $item->product->price * $item->quantity;
        }
        return $amount;
    }
}
