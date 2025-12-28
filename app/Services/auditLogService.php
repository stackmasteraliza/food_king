<?php

namespace App\Services;


use App\Http\Requests\PaginateRequest;
use Exception;
use App\Models\Address;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Models\OperationLog;

class auditLogService
{

    public $auditLogFilter = ['model_type', 'user', 'action_type', 'from_date', 'to_date'];

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


            $query = OperationLog::with('user')->latest();

            if ($request->action_type) {
                $query->where('action', $request->action_type);
            }

            if ($request->model_type) {
                $query->where('model_type', 'like', "%{$request->model_type}%");
            }
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }
            if ($request->user) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->user}%");
                });
            }
            return $query->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
    // public function list(PaginateRequest $request)
    // {
    //      Log::info("1.1");
    //     $query = OperationLog::with('user')
    //         ->orderBy('created_at', 'desc');

    //     // فلتر كلمة مفتاحية على event, auditable_type, auditable_id
    //     if ($request->filled('keyword')) {
    //         $kw = $request->keyword;
    //         $query->where(function ($q) use ($kw) {
    //             $q->where('action', 'like', "%{$kw}%")
    //                 ->orWhere('model_type', 'like', "%{$kw}%");
    //         });
    //     }

    //     // فلتر حسب مستخدم محدد
    //     if ($request->filled('user')) {
    //         $query->where('user_id', $request->user);
    //     }

    //     // فلتر من تاريخ
    //     if ($request->filled('from_date')) {
    //         $query->whereDate('created_at', '>=', $request->from_date);
    //     }

    //     // فلتر إلى تاريخ
    //     if ($request->filled('to_date')) {
    //         $query->whereDate('created_at', '<=', $request->to_date);
    //     }

    //     // جلب النتائج مع ترحيل الفلاتر في روابط الصفحة
    //     $logs = $query->paginate(10)
    //         ->appends($request->only(['keyword', 'user', 'from_date', 'to_date']));
    //     return $logs;
    //     // إعادة JSON مباشرة للـ Vue component

    // }
}
