<?php

namespace App\Repositories;

use App\Order;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
	public function model()
    {
       return \App\Payment::class;
    }

    /**
     * override
     */
	public function createPayment($request)
	{
		if ($request->user()->id == \App\Order::find($request->order_id)->user_id) {
			return $this->model->create($request->all());
		}
		return false;
	}

	public function allPaymentsPaginate($sort, $size)
    {
        $this->model = $this->model->with('order');
        if (isset($sort))
        {
            $directionSort = 'ASC';
            if ($sort[0] == '-') {
                $directionSort = 'DESC';
                $sort = substr($sort, 1);
            }
            $this->model = $this->model->orderBy($sort, $directionSort);
        }
    	return $this->model->paginate(isset($size) ? $size : \App\Payment::ITEMS_PER_PAGE);
    }
}