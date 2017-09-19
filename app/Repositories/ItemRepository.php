<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
	public function model()
    {
       return \App\Item::class;
    }
    public function getItemsBestSale()
    {
    	return $this->model->select([
    							'items.*',
    							\DB::raw('SUM(order_items.quantity) AS totalQuantity')
    						])
	    	                ->leftJoin('order_items', 'item_id', '=', 'items.id')
	    					->where('order_items.created_at', '<=', \DB::raw('DATE_SUB(NOW(),INTERVAL -30 DAY)'))
	    					->groupBy('items.id')
	    					->orderby('totalQuantity', 'DESC')
	    					->limit(10)
	    					->get();
    }

    public function allItemsPaginate()
    {
    	return $this->model->with('category')
    						->orderBy('created_at', 'DESC')
    						->paginate(\App\Item::ITEMS_PER_PAGE);
    }

}