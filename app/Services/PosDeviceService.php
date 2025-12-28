<?php

namespace App\Services;

use App\Enums\Status;
use Exception;
use App\Models\PosDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PosDeviceRequest;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use Smartisan\Settings\Facades\Settings;

class PosDeviceService
{
    protected array $PosDeviceFilter = [
        'name',
        'identifier'
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

            return PosDevice::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->PosDeviceFilter)) {
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
    public function store(PosDeviceRequest $request)
    {
        try {
            return PosDevice::create($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(PosDeviceRequest $request, PosDevice $PosDevice)
    {
        try {
            return tap($PosDevice)->update($request->validated());
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(PosDevice $PosDevice): void
    {
        try {

            $PosDevice->delete();
        } catch (Exception $exception) {
            Log::info(QueryExceptionLibrary::message($exception));
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(PosDevice $PosDevice): PosDevice
    {
        try {
            return $PosDevice;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
