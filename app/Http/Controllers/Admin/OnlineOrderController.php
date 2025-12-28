<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\SimpleOrderResource;
use App\Models\ThemeSetting;
use App\Services\CompanyService;
use App\Services\ThemeService;
use Exception;
use App\Models\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PaymentStatusRequest;
use App\Http\Resources\OrderDetailsResource;
use Smartisan\Settings\Facades\Settings;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlineOrderController extends AdminController
{
    private OrderService $orderService;
    private CompanyService $companyService;
    private ThemeService $themeService;

    public function __construct(OrderService $order, CompanyService $companyService, ThemeService $themeService)
    {
        parent::__construct();
        $this->orderService = $order;
        $this->companyService = $companyService;
        $this->themeService  = $themeService;
        $this->middleware(['permission:online-orders'])->only(
            'index',
            'show',
            'export',
            'pdf',
            'changeStatus',
            'changePaymentStatus',
            'selectDeliveryBoy'
        );
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return SimpleOrderResource::collection($this->orderService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Order $order): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->show($order, false));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new OrderExport($this->orderService, $request), 'Online-Order.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
    public function pdf(PaginateRequest $request): mixed
    {
        try {
            $company = $this->companyService->list();
            $theme_logo   = ThemeSetting::where(['key' => 'theme_logo'])->first()?->logo;
            $copyright   = Settings::group('site')->get('site_copyright');
            $orders = $this->orderService->list($request);

            $pdf = Pdf::loadView('pdf.online_orders', compact('company', 'theme_logo', 'orders', 'copyright'))
                ->setPaper('a4');
            return response()->stream(
                fn() => print($pdf->output()),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="online_order_report.pdf"',
                ]
            );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeStatus(Order $order, OrderStatusRequest $request): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->changeStatus($order, false, $request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changePaymentStatus(Order $order, PaymentStatusRequest $request): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->changePaymentStatus($order, false, $request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function selectDeliveryBoy(Order $order, Request $request): \Illuminate\Http\Response | OrderDetailsResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new OrderDetailsResource($this->orderService->selectDeliveryBoy($order, false, $request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
