<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepositoryInterface as CategoryRepository;
use Illuminate\Http\Response;

class CategoryController extends ApiController
{
    protected $categoryRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->categoryRepository->all(), Response::HTTP_OK);   
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
        $categoryRepository = $this->categoryRepository->create($request->all());
        if (!$categoryRepository) {
            return response()->json(['success' => false, 'message' => __('Error during create category')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $categoryRepository, 'success' => true], Response::HTTP_OK);
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
        if ($this->categoryRepository->update($request->all(), $request->id)) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }

        return response()->json(['success' => false, 'message' => __('Error during update current category!')], Response::HTTP_INTERNAL_SERVER_ERROR);
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
        if ($this->categoryRepository->find($id)->delete()) {
            return response()->json(['success' => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => __('Error during delete category')], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
