<?php
namespace App\Repositories;

interface PaymentRepositoryInterface
{
	public function createPayment($request);
	public function allPaymentsPaginate($sort, $size);
}