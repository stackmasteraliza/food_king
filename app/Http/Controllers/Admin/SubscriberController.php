<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Subscriber;
use App\Exports\SubscriberExport;
use App\Services\SubscriberService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\SubscriberResource;
use App\Http\Requests\SubscriberEmailRequest;


class SubscriberController extends AdminController
{
    public SubscriberService $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        parent::__construct();
        $this->subscriberService = $subscriberService;
        $this->middleware(['permission:subscribers'])->only('index', 'destroy', 'export');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return SubscriberResource::collection($this->subscriberService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Subscriber $subscriber): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->subscriberService->destroy($subscriber);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new SubscriberExport($this->subscriberService, $request), 'Subscribers.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function sendEmail(SubscriberEmailRequest $request): \Illuminate\Http\Response | SubscriberResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->subscriberService->sendEmail($request);
            return response(['status' => true, 'message' => trans('all.message.email_send')], 200);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
