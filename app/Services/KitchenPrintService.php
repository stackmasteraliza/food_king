<?php

namespace App\Services;

use App\Models\Order;
use App\Models\KitchenPrinterSetting;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\BluetoothPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class KitchenPrintService
{
    public function printOrder(Order $order)
    {
        $printer = KitchenPrinterSetting::where('is_default', true)
            ->first();

        if (!$printer) {
            throw new \Exception('No default kitchen printer configured');
        }

        try {
            Log::error("Kitchen print 0: " . "0");
            // إنشاء اتصال بلوتوث
            $connector = new WindowsPrintConnector($printer->ip_address); // اسم الطابعة الحرارية
            //$connector = new BluetoothPrintConnector($printer->ip_address);
            $printer = new Printer(connector: $connector);
            Log::error("Kitchen print 1: " . "1");
            // إعداد الطابعة
            $printer->initialize();


            // $connector = new NetworkPrintConnector($printer->ip_address, $printer->port);
            // $printer = new Printer($connector);

            // Set alignment and styles
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("فاتورة ضريبية\n");
            $printer->selectPrintMode();
            $printer->feed();

            // Company logo
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("----------------------------\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text($order->company->company_name . "\n");
            $printer->selectPrintMode();
            $printer->text($order->branch->address . "\n");
            $printer->text("Tel: " . $order->branch->phone . "\n");
            $printer->text("----------------------------\n");
            $printer->feed();

            // Order type and details
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("نوع الطلب: " . $this->getOrderTypeText($order->order_type) . "\n");

            if ($order->order_type === 'dining_table') {
                $printer->text("رقم الطاولة: " . $order->table_name . "\n");
            } elseif ($order->order_type === 'delivery') {
                $printer->text("عنوان التوصيل: " . $order->order_address . "\n");
            } elseif ($order->order_type === 'reservations') {
                $printer->text("تاريخ الحجز: " . $order->order_Reservations->ReservationsDate . "\n");
            }

            $printer->selectPrintMode();
            $printer->text("----------------------------\n");
            $printer->feed();

            // Order info
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("رقم الطلب: #" . $order->order_serial_no . "\n");
            $printer->text("التاريخ والوقت: " . $order->order_date . " - " . $order->order_time . "\n");
            $printer->text("----------------------------\n");
            $printer->feed();

            // Items header
            $printer->text(str_pad("الكمية", 10, ' ', STR_PAD_RIGHT) .
                str_pad("الصنف", 20, ' ', STR_PAD_RIGHT) .
                str_pad("السعر", 10, ' ', STR_PAD_LEFT) . "\n");
            $printer->text("----------------------------\n");

            // Order items
            foreach ($order->order_items as $item) {
                $printer->text(str_pad($item->quantity, 10, ' ', STR_PAD_RIGHT));

                // Item name
                $printer->text(str_pad($item->item_name, 20, ' ', STR_PAD_RIGHT));

                // Price
                $printer->text(str_pad($item->total_without_tax_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");

                // Variations
                if (!empty($item->item_variations)) {
                    foreach ($item->item_variations as $variation) {
                        $printer->text("  " . $variation->variation_name . ": " . $variation->name . "\n");
                    }
                }

                // Extras
                if (!empty($item->item_extras)) {
                    $extras = array_map(fn($e) => $e->name, $item->item_extras);
                    $printer->text("  إضافات: " . implode(', ', $extras) . "\n");
                }

                // Instructions
                if ($item->instruction) {
                    $printer->text("  ملاحظات: " . $item->instruction . "\n");
                }

                // Tax
                if ($item->tax_rate > 0) {
                    $taxInfo = $item->tax_name . " (" . $item->tax_currency_rate . " " . $item->tax_type . ")";
                    $printer->text("  " . str_pad($taxInfo, 20, ' ', STR_PAD_RIGHT) .
                        str_pad($item->tax_currency_amount . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");
                }

                $printer->text("----------------------------\n");
            }

            // Order summary
            $printer->text(str_pad("المجموع الفرعي:", 30, ' ', STR_PAD_LEFT) .
                str_pad($order->subtotal_without_tax_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");

            $printer->text(str_pad("إجمالي الضريبة:", 30, ' ', STR_PAD_LEFT) .
                str_pad($order->total_tax_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");

            $printer->text(str_pad("الخصم:", 30, ' ', STR_PAD_LEFT) .
                str_pad($order->discount_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");

            if ($order->order_type === 'delivery') {
                $printer->text(str_pad("رسوم التوصيل:", 30, ' ', STR_PAD_LEFT) .
                    str_pad($order->delivery_charge_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");
            }

            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text(str_pad("الإجمالي النهائي:", 30, ' ', STR_PAD_LEFT) .
                str_pad($order->total_currency_price . " ﷼", 10, ' ', STR_PAD_LEFT) . "\n");
            $printer->selectPrintMode();
            $printer->text("----------------------------\n");
            $printer->feed();

            // Payment info
            $printer->text("طريقة الدفع: " . $this->getPaymentMethodText($order->pos_payment_method) . "\n");

            if ($order->cash_back_amount > 0) {
                $printer->text("المبلغ المدفوع: " . $order->pos_received_currency_amount . " ﷼\n");
                $printer->text("المبلغ المسترد: " . $order->cash_back_currency_amount . " ﷼\n");
            }

            $printer->text("----------------------------\n");
            $printer->feed();

            // Token number
            if ($order->token) {
                $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
                $printer->text("رقم التذكرة: #" . $order->token . "\n");
                $printer->selectPrintMode();
                $printer->text("----------------------------\n");
            }

            // QR Code placeholder
            // $printer->text("[رمز الاستجابة السريعة هنا]\n");
            $printer->text("----------------------------\n");
            $printer->feed();

            // Footer messages
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("شكراً لزيارتكم\n");
            $printer->text("نرجو زيارتنا مرة أخرى\n");
            $printer->feed(2);

            // Powered by
            $printer->text("مقدم من\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text($order->company->company_name . "\n");
            $printer->selectPrintMode();

            // Final cut and close
            $printer->cut();
            $printer->close();

            return true;
        } catch (\Exception $e) {
            Log::error("Kitchen print failed: " . $e->getMessage());
            return false;
        }
    }
    // public function printInvoice(Invoice $invoice)
    // {
    //     try {
    //         // الحصول على الطابعة الافتراضية
    //         $printerDevice = BluetoothPrinter::where('is_default', true)->first();

    //         if (!$printerDevice) {
    //             throw new \Exception('No default printer configured');
    //         }

    //         // إنشاء اتصال بلوتوث
    //         $connector = new BluetoothPrintConnector($printerDevice->mac_address);
    //         $printer = new Printer($connector);

    //         // إعداد الطابعة
    //         $printer->initialize();
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);

    //         // طباعة العنوان
    //         $printer->setTextSize(2, 2);
    //         $printer->text("فاتورة #{$invoice->number}\n");
    //         $printer->setTextSize(1, 1);
    //         $printer->text("تاريخ: {$invoice->created_at->format('Y-m-d H:i')}\n\n");

    //         // طباعة العناصر
    //         $printer->setJustification(Printer::JUSTIFY_LEFT);
    //         $printer->text("--------------------------------\n");
    //         $printer->text(str_pad('الصنف', 15) . str_pad('الكمية', 8) . str_pad('السعر', 10) . "\n");
    //         $printer->text("--------------------------------\n");

    //         foreach ($invoice->items as $item) {
    //             $printer->text(str_pad($item['name'], 15));
    //             $printer->text(str_pad($item['quantity'], 8));
    //             $printer->text(str_pad(number_format($item['price'], 2), 10) . "\n");
    //         }

    //         // طباعة المجموع
    //         $printer->text("--------------------------------\n");
    //         $printer->setJustification(Printer::JUSTIFY_RIGHT);
    //         $printer->text("المجموع: " . number_format($invoice->total, 2) . " ر.س\n\n");

    //         // رسالة ختامية
    //         $printer->setJustification(Printer::JUSTIFY_CENTER);
    //         $printer->text("شكراً لتعاملكم معنا\n");
    //         $printer->text("للاستفسار: 0555555555\n\n");

    //         // قطع الورق
    //         $printer->cut();

    //         // إغلاق الاتصال
    //         $printer->close();

    //         Log::info("تم طباعة الفاتورة {$invoice->number} بنجاح");
    //     } catch (\Exception $e) {
    //         Log::error("فشل الطباعة: " . $e->getMessage());
    //     }
    // }
    private function getOrderTypeText($type)
    {
        $types = [
            'DINING_TABLE' => 'طاولة',
            'DELIVERY' => 'توصيل',
            'Reservations' => 'حجوزات',
            'TAKEAWAY' => 'سفري'
        ];
        return $types[$type] ?? $type;
    }
    public function testConnection($ipAddress, $port)
    {
        try {
            $connector = new NetworkPrintConnector($ipAddress, $port);
            $printer = new Printer($connector);

            // Print test receipt
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
            $printer->text("اختبار الاتصال\n");
            $printer->selectPrintMode();

            $printer->text("----------------------------\n");
            $printer->text("تاريخ الاختبار: " . date('Y-m-d H:i:s') . "\n");
            $printer->text("IP: $ipAddress\n");
            $printer->text("Port: $port\n");
            $printer->text("----------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("الحالة: اتصال ناجح!\n");
            $printer->text("الطابعة جاهزة للاستخدام\n");

            $printer->feed(3);
            $printer->cut();
            $printer->close();

            return [
                'status' => 'success',
                'message' => 'تم إرسال الاختبار بنجاح إلى الطابعة'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'فشل الاتصال: ' . $e->getMessage()
            ];
        }
    }
    private function getPaymentMethodText($method)
    {
        $methods = [
            'cash' => 'نقدي',
            'card' => 'بطاقة',
            'mada' => 'مدى',
            'fiza' => 'فيزا'
        ];

        return $methods[$method] ?? $method;
    }
}
