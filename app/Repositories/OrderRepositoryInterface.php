<?php
namespace App\Repositories;

interface OrderRepositoryInterface
{
	public function allOrdersPaginate();
    public function ordersPaginateFollowUser($userId);
	public function createOrder($request);
	public function updateOrder($request, $id);
	public function showOrder($request, $id);
	public function deleteOrder($request, $id);
}