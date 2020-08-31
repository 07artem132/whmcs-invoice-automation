<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.08.19 22:51
 *
 */


function InvoiceAutomation_config()
{
    return [
        "name" => "Автоматизация работы со счетами",
        "description" => "",
        "version" => "1",
        "author" => "service-voice",
        "fields" => [
            'cancelInvoicePreModuleTerminate' => [
                'FriendlyName' => 'Отменять счет при удалении услуги',
                'Type' => 'yesno',
                "Description" => "Если в счете более 2х услуг, то удаляется просто item счета",
            ],
            'cancelInvoiceAddFunds' => [
                'FriendlyName' => 'Отменять счета на пополнение баланса',
                'Type' => 'text',
                'Default' => '-1',
                "Description" => "дней (-1 отключено)",
            ],
            'note2' => [
                "Description" => "Модуль не имеет административного вывода",
            ]
        ]
    ];
}