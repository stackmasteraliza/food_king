<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\BranchService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\BranchResource;

class BranchController extends Controller
{
    public BranchService $branchService;

    public function __construct(BranchService $branch)
    {
        $this->branchService = $branch;
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return BranchResource::collection($this->branchService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Branch $branch): BranchResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new BranchResource($this->branchService->show($branch));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function showByLatLong(Request $request)
    {
        try {
            return new BranchResource($this->branchService->showByLatLong($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}