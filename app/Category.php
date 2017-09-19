<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use softDeletes;

    const ITEMS_PER_PAGE = 10;

    protected $fillable = [
        'name',
    ];
    
    /**
     * Category has many items
     *
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany('App\Item', 'category_id', 'id');
    }

    /**
     * This is a recommended way to declare event handlers
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Register a deleting model event with the dispatcher.
         *
         * @param \Closure|string  $callback
         *
         * @return void
         */
        static::deleting(function ($category) {
            $category->items()->delete();
        });
    }
}
