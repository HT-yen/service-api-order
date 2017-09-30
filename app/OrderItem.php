<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use softDeletes;

    const ITEMS_PER_PAGE = 10;

    protected $fillable = ['id', 'order_id', 'item_id','quantity', 'price'];

    /**
     * OrderItem has one Order
     *
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

      /**
     * Relationship with item model
     *
     * @return \App\Model
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
