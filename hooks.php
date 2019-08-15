<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.08.19 22:50
 *
 */
use WHMCS\Billing\Invoice\Item as InvoiceItem;

add_hook('PreModuleTerminate', 99999999, function ($vars) {
    $InvoiceItem = InvoiceItem::where('relid', '=', $vars['params']['serviceid'])->orderBy('id', 'desc')->first();
    $invoice = $InvoiceItem->invoice()->first();

    if ($invoice->items()->count() == 1) {
        $invoice->status = 'Cancelled';
        $invoice->saveOrFail();
    } else {
        $InvoiceItem->delete();
        updateInvoiceTotal($invoice->id);
    }
});