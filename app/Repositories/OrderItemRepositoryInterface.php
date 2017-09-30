<?php
namespace App\Repositories;

interface OrderItemRepositoryInterface
{
	public function orderItemsPaginate($orderId);
	public function updateOrderItem($request, $id);
	public function createOrderItem($orderId, $items);
}