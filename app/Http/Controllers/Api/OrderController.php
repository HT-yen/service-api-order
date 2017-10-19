<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\OrderRepositoryInterface as OrderRepository;
use Illuminate\Http\Response;

class OrderController extends ApiController
{
    protected $orderRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the resource for statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json($this->orderRepository->allOrdersPaginate($request->sort, $request->size), Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource by user_id of admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderFollowUser($userId,Request $request)
    {
        return response()->json($this->orderRepository->ordersPaginateFollowUser($userId, $request->sort, $request->size), Response::HTTP_OK);
    }

    /**
     * Display a listing of the themselves resource of user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getThemselvesOrder(Request $request)
    {
        return response()->json($this->orderRepository->ordersPaginateFollowUser($request->user()->id), Response::HTTP_OK);
    }


     /**
     * Create order themselves of user.
     *
     * @param Request $request request store data by user
     *
     * @return \Illuminate\Http\Response
     */
    public function storeOrder(Request $request)
    {
        $orderRepository = $this->orderRepository->createOrder($request);
        if (!$orderRepository) {
            return response()->json(['success' => false, 'message' => __('Error during create order')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['data' => $orderRepository, 'success' => true], Response::HTTP_OK);
    }

     /**
     * Update order items of themselves by user.
     *
     * @param Request $request request update
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request, $id)
    {
        $orderRepository = $this->orderRepository->updateOrderItemOfOrder($request, $id);
        if (!$orderRepository) {
            return response()->json(['success' => false, 'message' => __('Error during update order')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['success' => true], Response::HTTP_OK);
    }

    /**
     * Change status of order by admin.
     *
     * @param Request $request request update
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatusOrder(Request $request, $id)
    {
        $orderRepository = $this->orderRepository->changeStatusOrder($request, $id);
        if (!$orderRepository) {
            return response()->json(['success' => false, 'message' => __('Error during change status order')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['success' => true], Response::HTTP_OK);
    }
    
    
    /**
     * Delete themselves order by user.
     *
     * @param Request $request request destroy data of user or admin
     * @param int $id id delete
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyOrder(Request $request, $id)
    {
        if ($this->orderRepository->deleteOrder($request, $id)) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => __('Error during delete order')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource by admin.
     *
     * @param Request $request request show data of user or admin
     * @param int $id of order item
     *
     * @return \Illuminate\Http\Response
     */
    public function showOrder(Request $request, $id)
    {
        $orderRepository = $this->orderRepository->showOrder($request, $id);
        if ($orderRepository) {
        return response()->json(['data' => $orderRepository, 'success' => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => __('Error during get order')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
