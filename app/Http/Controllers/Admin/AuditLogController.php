<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Services\auditLogService;
use App\Http\Resources\auditLogResource;
use App\Models\OperationLog;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\PaginateRequest;
use App\Exports\AuditLogExport;
use Illuminate\Support\Facades\Log;

class AuditLogController extends AdminController
{

    public auditLogService $auditLogService;

    public function __construct(auditLogService $auditLogService)
    {
        parent::__construct();
        $this->auditLogService = $auditLogService;
        $this->middleware(['permission:auditLog'])->only('index', 'export', 'pdf');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            // Log::info("1");
            // $logs = $this->auditLogService->list($request);
            // Log::info("2");
            // return response([
            //     'data'           => $logs->items(),
            //     'current_page'   => $logs->currentPage(),
            //     'last_page'      => $logs->lastPage(),
            //     'prev_page_url'  => $logs->previousPageUrl(),
            //     'next_page_url'  => $logs->nextPageUrl(),
            // ]);
            return auditLogResource::collection($this->auditLogService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new AuditLogExport($this->auditLogService, $request), 'auditLog.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
