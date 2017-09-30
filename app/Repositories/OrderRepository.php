<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Order;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OrderItemRepository;
use DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    private $orderItemRepository;
    public function __construct(OrderItemRepository $orderItemRepository)
    {
       parent::__construct();
       $this->orderItemRepository = $orderItemRepository;
    }
    public function model()
    {
       return \App\Order::class;
    }

    public function allOrdersPaginate()
    {
       return $this->model->with(['items' => function($q) {
            $q->select('items.name')->withPivot('price AS price_real');
       }])->paginate(Order::ITEMS_PER_PAGE);
    }

    public function ordersPaginateFollowUser($userId)
    {
       return $this->model->with(['items' => function($q) {
            $q->select('items.name')->withPivot('price AS price_real');
       }])->where('user_id', $userId)->paginate(Order::ITEMS_PER_PAGE);
    }

    public function createOrder($request)
    {
        DB::beginTransaction();
        try {
            $order = $this->model->create(
                [
                    'user_id' => $request->user()->id,
                    'status' => Order::STATUS_PENDING,
                    'address' => $request->address,
                ]
            );
            foreach ($request->items as $item) {
                $this->orderItemRepository->createOrderItem($order->id, $item);
            }

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function updateOrder($request, $id)
    {
        $order = $this->model->findOrFail($id);
        if (($order->user_id == $request->user()->id) || $request->user()->hasRole('admin')) {
            DB::beginTransaction();
            try {
                $order = $this->order->findOrFail($id);
                // order is not pending return false
                if ($order->status != 1) {
                    return false;
                }
                //delete old order's orderItem
                $hotel->items()->detach();
                foreach ($request->items as $item) {
                    $this->orderItemRepository->createOrderItem($id, $item);
                }
                DB::commit();
                return $order;
            } catch (Exception $e) {
                DB::rollback();
                return false;
            }
        }
        return false;
    }

    public function showOrder($request, $id)
    {
        $order = $this->model->with('items')->with('user')->with('orderItems.item')->findOrFail($id);
        if (
            ($order->user_id == $request->user()->id)
            ||
            ($request->user()->hasRole('admin'))
        ) {
            return $order;
        }
        return false;
    }

    public function deleteOrder($request, $id)
    {
        $order = $this->model->findOrFail($id);
        if (
            ($order->user_id == $request->user()->id)
            ||
            ($request->user()->hasRole('admin'))
        ) {
            return $order->delete();
        }
        return false;
    }
}