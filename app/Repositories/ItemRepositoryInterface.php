<?php
namespace App\Repositories;

interface ItemRepositoryInterface
{
	public function getItemsBestSale();
	public function allItemsPaginate();
}