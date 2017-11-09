<?php
namespace App\Repositories;

interface OrderRepositoryInterface
{
	public function allOrdersPaginate($sort, $size);
    public function ordersPaginateFollowUser($userId);
	public function createOrder($request);
	public function updateOrderItemOfOrder($request, $id);
	public function changeStatusOrder($request, $id);
	public function showOrder($request, $id);
	public function deleteOrder($request, $id);
    public function getStatisticOrder($startDate, $endDate, $sort, $size, $status);
}