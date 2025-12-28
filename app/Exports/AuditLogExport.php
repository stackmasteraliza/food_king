<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Requests\PaginateRequest;
use App\Services\auditlogService;

class AuditLogExport implements FromCollection, WithHeadings
{
    public auditlogService $auditlogService;
    public PaginateRequest $request;

    public function __construct($auditlogService, $request)
    {

        $this->auditlogService = $auditlogService;
        $this->request              = $request;
    }

    public function collection()
    {

        $logs     = $this->auditlogService->list($this->request);

        return collect($logs)->map(function ($log) {
            return [
                'الموديل' => class_basename($log->model_type),
                'العملية' => $log->action,
                'اسم المستخدم' => optional($log->user)->name ?? 'نظام',
                'البيانات السابقة' => json_encode($log->before, JSON_UNESCAPED_UNICODE),
                'البيانات الجديدة' => json_encode($log->after, JSON_UNESCAPED_UNICODE),
                'التاريخ' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'الموديل',
            'العملية',
            'اسم المستخدم',
            'البيانات السابقة',
            'البيانات الجديدة',
            'التاريخ',
        ];
    }
}
