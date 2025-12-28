<?php

namespace App\Http\Controllers\Admin;


use App\Models\KitchenPrinterSetting;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use App\Http\Requests\KitchenPrinterRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\KitchenPrinterResource;
use App\Services\KitchenPrinterService;
use Exception;
use Illuminate\Support\Facades\Log;

class KitchenPrinterController extends AdminController
{
    private KitchenPrinterService $KitchenPrinterService;
    public function __construct(KitchenPrinterService $KitchenPrinterService)
    {

        parent::__construct();
        $this->KitchenPrinterService = $KitchenPrinterService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show', 'testprint');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {

            return KitchenPrinterResource::collection($this->KitchenPrinterService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(KitchenPrinterRequest $request): KitchenPrinterResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {

            return new KitchenPrinterResource($this->KitchenPrinterService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(KitchenPrinterRequest $request, KitchenPrinterSetting $KitchenPrinter): KitchenPrinterResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {

            return new KitchenPrinterResource($this->KitchenPrinterService->update($request, $KitchenPrinter));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(KitchenPrinterSetting $KitchenPrinter): \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->KitchenPrinterService->destroy($KitchenPrinter);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(KitchenPrinterSetting $KitchenPrinter): KitchenPrinterResource | \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {

            return new KitchenPrinterResource($KitchenPrinter);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function testprint(KitchenPrinterRequest $request)
    {

        log::info($request->ip_address);

        try {
            $connector = new NetworkPrintConnector($request->ip_address, $request->port);
            $printer = new Printer($connector);
            log::info($request->port);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("TEST PRINT\n");
            $printer->text("Kitchen Printer Connection Successful\n");
            $printer->text(date('Y-m-d H:i:s') . "\n");
            $printer->text("----------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Printer Name: " . ($request->name ?: 'Test Printer') . "\n");
            $printer->text("IP Address: {$request->ip_address}\n");
            $printer->text("Port: {$request->port}\n");

            $printer->cut();
            $printer->close();
            log::info('ok');
            return response()->json(['message' => 'Test print sent successfully']);
        } catch (Exception $e) {
            log::info($e->getMessage());
            return response()->json([
                'message' => 'Print failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
