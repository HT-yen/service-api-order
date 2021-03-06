<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\ItemRepositoryInterface as ItemRepository;
use Illuminate\Http\Response;
use App\Http\Requests\Api\UpdateImageRequest;
use Image;

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
    public function index(Request $request)
    {
        return response()->json($this->itemRepository->allItemsPaginate($request->key, $request->sort, $request->size), Response::HTTP_OK);
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
        $itemRepository = $this->itemRepository->getItemsFollowCategory($request->idCategory, $request->size);
        return response()->json($itemRepository, Response::HTTP_OK);
    }

    /**
     * Get best sale items Related To Item.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBestSaleItemsRelatedToItem(Request $request)
    {
        return response()->json($this->itemRepository->getBestSaleItemsRelatedToItem($request->id, $request->size), Response::HTTP_OK);
    }

    /**
     * Get items by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $itemRepository = $this->itemRepository->getItemsById($id);
        return response()->json($itemRepository, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request request store data
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $itemRepository = $this->itemRepository->create($request->all());
        if (!$itemRepository) {
            return response()->json(['success' => false, 'message' => __('Error during create item')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $itemRepository, 'success' => true], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request request update
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($this->itemRepository->update($request->all(), $request->id)) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }

        return response()->json(['success' => false, 'message' => __('Error during update current item!')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id id delete
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->itemRepository->find($id)->delete()) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => __('Error during delete item')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Save image and response image name
     *
     * @param UpdateImageRequest $request request has image file
     *
     * @return string
     */
    public function postUploadImage(UpdateImageRequest $request)
    {
        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $imageName = time() . $image->getClientOriginalName();
            Image::make($image)->save(public_path('images/items/' . $imageName));
            return $imageName;
        }
        return response()->json(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Remove image with file name
     *
     * @param Request $request to remove image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request)
    {
        $fileName = $request->get('file_name');
        if (unlink(public_path('images/items/' . $fileName))) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => __('Error during remove image')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
