<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Order;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OrderItemRepository;
use App\Repositories\ItemRepository;
use DB;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    private $orderItemRepository;
    public function __construct(
        OrderItemRepository $orderItemRepository,
        ItemRepository $itemRepository
    ) {
       parent::__construct();
       $this->orderItemRepository = $orderItemRepository;
       $this->itemRepository = $itemRepository;
    }
    public function model()
    {
       return \App\Order::class;
    }

    public function allOrdersPaginate($sort, $size)
    {
        $this->model = $this->model->with(['items' => function($q) {
            $q->select('items.name')->withPivot('price AS price_real');
        }]);
        if (isset($sort))
        {
            $directionSort = 'ASC';
            if ($sort[0] == '-') {
                $directionSort = 'DESC';
                $sort = substr($sort, 1);
            }
            $this->model = $this->model->orderBy($sort, $directionSort);
        }
        return $this->model->paginate(isset($size) ? $size : Order::ITEMS_PER_PAGE);
    }

    public function ordersPaginateFollowUser($userId,$sort,$size)
    {
       $this->model = $this->model->with(['items' => function($q) {
            $q->select('items.name')->withPivot('price AS price_real');
       }]);
        if (isset($sort))
        {
            $directionSort = 'ASC';
            if ($sort[0] == '-') {
                $directionSort = 'DESC';
                $sort = substr($sort, 1);
            }
            $this->model = $this->model->orderBy($sort, $directionSort);
        }
       return $this->model->where('user_id', $userId)->paginate(isset($size) ? $size : Order::ITEMS_PER_PAGE);

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
                if ($this->itemRepository->find($item['id'])->total >=
                    $item['quantity']) {
                    $this->orderItemRepository->createOrderItem($order->id, $item);
                } else {
                    DB::rollback();
                    return false;
                }
            }

            DB::commit();
            return $order;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    // user update order item of themselves order
    public function updateOrderItemOfOrder($request, $id)
    {
        $order = $this->model->findOrFail($id);
        if ($order->user_id == $request->user()->id) {
            DB::beginTransaction();
            try {
                // order is not pending return false
                if ($order->status != 1) {
                    return false;
                }
                //add quantity orderItem which was devided before
                $this->itemRepository->addQuantityIntoTotalItems($order->orderItems);
                //delete old order's orderItem
                $order->items()->detach();
                // re-create orderItems which user wanted to order
                foreach ($request->items as $item) {
                    if ($this->itemRepository->find($item['id'])->total >=
                        $item['quantity']) {
                        $this->orderItemRepository->createOrderItem($order->id, $item);
                    } else {
                        DB::rollback();
                        return false;
                    }
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

    // admin change status order 
    public function changeStatusOrder($request, $id)
    {
        $order = $this->model->findOrFail($id);
        if ($request->user()->hasRole('admin')) {
            DB::beginTransaction();
            try {
                // order is finish return false
                if ($order->status == 3) {
                    return false;
                }
                // cancel order
                if ($request->status == 0) {
                    //Not delete old order's orderItem but add quantity orderItem which was devided before
                    $this->itemRepository->addQuantityIntoTotalItems($order->orderItems);
                }
                $order->status = $request->status;
                $order->save();
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
        $order = $this->model->with('user')->with('orderItems.item')->findOrFail($id);
        if (
            ($order->user_id == $request->user()->id)
            ||
            ($request->user()->hasRole('admin'))
        ) {
            return $order;
        }
        return false;
    }

    // user delete themselves order by cancel this
    public function deleteOrder($request, $id)
    {
        $order = $this->model->findOrFail($id);
        if ($order->user_id == $request->user()->id) {
            DB::beginTransaction();
            try {
                 // order is not pending return false
                if ($order->status != 1) {
                    return false;
                }
                //add quantity orderItem which was devided before
                $this->itemRepository->addQuantityIntoTotalItems($order->orderItems);
                //delete old order's orderItem
                $order->items()->detach();
                $order->delete();
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollback();
                return false;
            }
        }
        return false;
    }
}