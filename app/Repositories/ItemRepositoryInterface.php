<?php
namespace App\Repositories;

interface ItemRepositoryInterface
{
	public function getItemsBestSale();
	public function allItemsPaginate($key, $sort, $size);
	public function getItemsFollowCategory($idCategory, $sort, $size);
    public function getItemsById($id);
    public function addQuantityIntoTotalItems($orderItems);
}