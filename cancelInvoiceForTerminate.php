<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 15.08.19 22:51
 *
 */


function cancelInvoiceForTerminate_config()
{
    return [
        "name" => "Отмена счета при удалении услуги",
        "description" => "",
        "version" => "1",
        "author" => "service-voice",
        "fields" => [
            'note1' => [
                "Description" => "Если в счете более 2х услуг, то удаляется просто item счета",
            ],
            'note2' => [
                "Description" => "Модуль не имеет административного вывода",
            ]
        ]
    ];
}