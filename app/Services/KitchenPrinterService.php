<?php

namespace App\Services;


use Exception;
use App\Models\KitchenPrinterSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\KitchenPrinterRequest;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;

class KitchenPrinterService
{
    protected $KitchenPrinterFilter = [
        'name',
        'ip_address',
        'is_default',
        'port',
        'branch_id'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {

            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return KitchenPrinterSetting::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->KitchenPrinterFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(KitchenPrinterRequest $request)
    {
        try {

            return KitchenPrinterSetting::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(KitchenPrinterRequest $request, KitchenPrinterSetting $KitchenPrinter)
    {
        try {

            return tap($KitchenPrinter)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(KitchenPrinterSetting $KitchenPrinter): void
    {
        try {


            $KitchenPrinter->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    
}
