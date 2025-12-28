<?php

namespace App\Http\Controllers\Admin;

use App\Models\Integration;
use App\Models\UserIntegration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\IntegrationRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\IntegrationResource;
use App\Services\IntegrationService;
use Exception;
use Illuminate\Support\Facades\Log;

class IntegrationController extends AdminController
{


    private IntegrationService $IntegrationService;

    public function __construct(IntegrationService $IntegrationService)
    {
        parent::__construct();
        $this->IntegrationService = $IntegrationService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show', 'listsWithAPISetting', 'SaveIntegrationAPI');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return IntegrationResource::collection($this->IntegrationService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function listsWithAPISetting(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return IntegrationResource::collection($this->IntegrationService->listsWithAPISetting($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function store(IntegrationRequest $request): IntegrationResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new IntegrationResource($this->IntegrationService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(IntegrationRequest $request, Integration $Integration): IntegrationResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {

            return new IntegrationResource($this->IntegrationService->update($request, $Integration));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Integration $Integration): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->IntegrationService->destroy($Integration);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Integration $Integration): IntegrationResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new IntegrationResource($Integration);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function SaveIntegrationAPI(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'api_url' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'api_key' => 'nullable|string',
            'client_id' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'auth_method' => 'required|in:basic,token,oauth2',
        ]);

        $integration = UserIntegration::updateOrCreate(
            ['user_id' => auth()->id(), 'integration_id' => $request->integration_id],
            [...$request->all(), 'user_id' => auth()->id()]
        );

        return response()->json(['message' => 'Saved successfully', 'integration' => $integration]);
    }
}
