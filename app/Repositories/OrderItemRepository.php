<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\OrderItem;
use App\Item;
use App\Repositories\OrderItemRepositoryInterface;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
	public function model()
    {
       return \App\OrderItem::class;
    }

    public function orderItemsPaginate($orderId)
    {
    	return $this->model->where('order_id', $orderId)
    				->paginate(OrderItem::ITEMS_PER_PAGE);
    }

    public function updateOrderItem($request, $id)
    {
        $orderItem = $this->model->findOrFail($id);
        $orderItem->quantity = $request->quantity;
        return $orderItem->save();
    }

    public function createOrderItem($orderId, $item)
    {
        $price = Item::findOrFail($item['id'])->price * $item['quantity'];
        $this->model->create(
            [
                'item_id' => $item['id'],
                'quantity'=> $item['quantity'],
                'order_id' => $orderId,
                'price' => $price
            ]
        );
    }
}