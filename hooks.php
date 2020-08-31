<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 04.12.2019, 23:40
 *
 */

use WHMCS\Billing\Invoice\Item as InvoiceItem;
use WHMCS\Billing\Invoice;
use WHMCS\Module\Addon\Setting;
use Illuminate\Database\Capsule\Manager as Capsule;

add_hook('PreModuleTerminate', 99999999, function ($vars) {
    $result = Setting::Module('InvoiceAutomation')->get()->toArray();
    $config = [];

    array_walk($result, function ($val, $key) use (&$config) {
        $config[$val['setting']] = $val['value'];
    });

    if (array_key_exists('cancelInvoicePreModuleTerminate', $config) && $config['cancelInvoicePreModuleTerminate'] != 'on')
        return;

    $InvoiceItem = InvoiceItem::where('relid', '=', $vars['params']['serviceid'])->orderBy('id', 'desc')->first();

    if (empty($InvoiceItem)) {
        return;
    }

    $invoice = $InvoiceItem->invoice()->first();

    if (empty($invoice)) {
        return;
    }

    if ($invoice->items()->count() == 1) {
        $invoice->status = 'Cancelled';
        $invoice->saveOrFail();
    } else {
        $InvoiceItem->delete();
        updateInvoiceTotal($invoice->id);
    }
});

add_hook('DailyCronJob', 1, function ($vars) {
    $result = Setting::Module('InvoiceAutomation')->get()->toArray();
    $config = [];

    array_walk($result, function ($val, $key) use (&$config) {
        $config[$val['setting']] = $val['value'];
    });

    if (array_key_exists('cancelInvoiceAddFunds', $config) && (int)$config['cancelInvoiceAddFunds'] <= -1)
        return;

    $invoices = Invoice::
    join(
        'tblinvoiceitems',
        'tblinvoices.id',
        '=', 'tblinvoiceitems.invoiceid'
    )
        ->select(
            'tblinvoices.id',
            'tblinvoices.status',
        )
        ->where('status', '=', 'unpaid')
        ->where('type', '=', 'addFunds')
        ->whereDate('date', '<=', date('Y-m-d', time() - (60 * 60 * 24 * (int)$config['cancelInvoiceAddFunds'])))
        ->get();

    foreach ($invoices as $invoice) {
        $invoice->status = 'Cancelled';
        $invoice->save();

    }
});
