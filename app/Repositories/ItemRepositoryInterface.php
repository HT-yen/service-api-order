<?php
namespace App\Repositories;

interface ItemRepositoryInterface
{
	public function getItemsBestSale();
	public function allItemsPaginate();
	public function getItemsFollowCategory($idCategory, $sort, $size);
}