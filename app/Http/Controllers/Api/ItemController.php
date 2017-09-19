<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\ItemRepositoryInterface as ItemRepository;
use Illuminate\Http\Response;

class ItemController extends ApiController
{
    protected $itemRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->itemRepository->allItemsPaginate(), Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Get best sale items.
     *
     * @return \Illuminate\Http\Response
     */
    public function getItemsBestSale()
    {
        return response()->json($this->itemRepository->getItemsBestSale(), Response::HTTP_OK);
    }

    /**
     * Get items follow category.
     *
     * @return \Illuminate\Http\Response
     */
    public function getItemsFollowCategory(Request $request)
    {
        $itemRepository = $this->itemRepository->getItemsFollowCategory($request->idCategory, $request->sort, $request->size);
        return response()->json($itemRepository, Response::HTTP_OK);
    }
}
