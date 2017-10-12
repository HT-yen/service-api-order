<?php

namespace App\Repositories;

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
		if ($request->user()->id == $request->order_id) {
			return $this->model->create($request->all());
		}
		return false;
	}
}