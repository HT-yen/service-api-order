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
}