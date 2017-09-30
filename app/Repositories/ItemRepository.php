<?php

namespace App\Repositories;

use App\Item;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ItemRepositoryInterface;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    private $categoryRepository;

    public function __construct(
       CategoryRepository $categoryRepository
   ) {
       parent::__construct();
       $this->categoryRepository = $categoryRepository;
   }

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

    public function getItemsFollowCategory($idCategory, $sort, $size)
    {
        // Check if has category
        $this->categoryRepository->find($idCategory);

        // Take items of category
        $this->model = $this->model->where('category_id', $idCategory);
        if (isset($sort))
        {
            $directionSort = 'ASC';
            if ($sort[0] == '-') {
                $directionSort = 'DESC';
                $sort = substr($sort, 1);
            }
            $this->model = $this->model->orderBy($sort, $directionSort);
        }
        return $this->model->paginate(isset($size) ? $size : Item::ITEMS_PER_PAGE);
    }
    public function getItemsById($id)
    {
        return $this->model->with('category')->find($id);
    }
}