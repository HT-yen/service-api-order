<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    const ITEMS_PER_PAGE = 10;
    protected $fillable = ['order_id', 'transaction_id', 'payment_gross', 'payment_at', 'payer_email'];

    /**
     * Get the order record associated with the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * This is a recommended way to declare event handlers
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // Update status of order to FINISHED
        static::creating(function ($payment) {
            $order = $payment->order;
            $order->status = Order::STATUS_FINISHED;
            $order->save();
        });
    }
}
