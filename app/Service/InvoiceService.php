<?php

namespace App\Service;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoiceService{
    public function getInvoice($payment){
        $customer = new Buyer([
            'name'          => $payment->user->name,
            'custom_fields' => [
                'email' => $payment->user->email,
            ],
        ]);

        $item = (new InvoiceItem())->title('Subscrption Fee')->pricePerUnit(number_format($payment->total / 100,2));

        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItem($item);

        return $invoice->stream();
    }
}