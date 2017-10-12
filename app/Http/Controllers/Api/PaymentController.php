<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\PaymentRepositoryInterface as PaymentRepository;
use Illuminate\Http\Response;

class PaymentController extends ApiController
{
    protected $paymentRepository;

    /**
     * UserAPIController constructor.
     *
     * @param User $user Dependence injection
     */
    public function __construct(paymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->paymentRepository->all(), Response::HTTP_OK);   
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
        $paymentRepository = $this->paymentRepository->createPayment($request);
        if (!$paymentRepository) {
            return response()->json(['success' => false, 'message' => __('Error during create payment')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $paymentRepository, 'success' => true], Response::HTTP_OK);
    }
}
