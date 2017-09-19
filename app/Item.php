<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use softDeletes;

    const ITEMS_PER_PAGE = 10;

    protected $table = "items";

    protected $fillable = ['id', 'name','total', 'descript', 'category_id', 'price','image'];

    /**
     * Material has many order item
     *
     * @return mixed
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Material has one Category
     *
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function getImageAttribute($image)
    {
        if ($image) {
            return asset(config('constant.path_upload_items') . $image);
        } else {
            return asset(config('constant.default_image'));
        }
    }
}
