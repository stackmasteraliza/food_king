<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ItemReportResource;
use Exception;
use App\Services\ItemService;
use App\Exports\ItemsReportExport;
use App\Http\Resources\ItemResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\PaginateRequest;
use App\Models\ThemeSetting;
use App\Services\CompanyService;
use App\Services\ThemeService;
use Smartisan\Settings\Facades\Settings;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemsReportController extends AdminController
{

    private ItemService $itemService;
    private CompanyService $companyService;
    private ThemeService $themeService;

    public function __construct(ItemService $itemService,CompanyService $companyService, ThemeService $themeService)
    {
        parent::__construct();
        $this->itemService = $itemService;
        $this->companyService= $companyService;
        $this->themeService  = $themeService;
        $this->middleware(['permission:items-report'])->only('index', 'export', 'pdf');
    }

    public function index(PaginateRequest $request) : \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return ItemReportResource::collection($this->itemService->itemReport($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(PaginateRequest $request) : \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return Excel::download(new ItemsReportExport($this->itemService, $request), 'Item-Report.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function pdf(PaginateRequest $request):mixed
    {
        try {
           $company = $this->companyService->list();
           $theme_logo   = ThemeSetting::where(['key' => 'theme_logo'])->first()?->logo;
           $copyright   = Settings::group('site')->get('site_copyright');
           $items = $this->itemService->itemReport($request);


           $pdf = Pdf::loadView('pdf.items_report', compact('company', 'theme_logo', 'items', 'copyright') )
           ->setPaper('a4');
        return response()->stream(
            fn() => print($pdf->output()),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="items_report.pdf"',
            ]
        );


        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
