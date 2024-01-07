<?php

namespace Sprint\Migration;


class Version20240105222834 extends Version
{
    protected $description = "Добавление нового свойства в инфоблок new_catalog";

    public function up()
    {
        if (!\CModule::IncludeModule("iblock")) {
            die('Модуль "Инфоблоки" не установлен');
        }

        // Получаем ID инфоблока по его символьному коду
        $iblockCode = 'new_catalog';
        $iblockID = \CIBlock::GetList([], ['CODE' => $iblockCode])->Fetch()['ID'];

        if (!$iblockID) {
            die("Инфоблок с кодом $iblockCode не найден");
        }

        // Добавляем новое свойство
        $ibp = new \CIBlockProperty;
        $PropID = $ibp->Add([
            'NAME' => 'Новое свойство 2',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'NEW_PROP2',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => $iblockID
        ]);

        if (!$PropID) {
            die('Ошибка при создании свойства');
        }
    }

    public function down()
    {
        if (!\CModule::IncludeModule("iblock")) {
            die('Модуль "Инфоблоки" не установлен');
        }

        // Получаем ID инфоблока по его символьному коду
        $iblockCode = 'new_catalog';
        $iblockID = \CIBlock::GetList([], ['CODE' => $iblockCode])->Fetch()['ID'];

        if (!$iblockID) {
            die("Инфоблок с кодом $iblockCode не найден");
        }

        // Находим и удаляем свойство
        $dbProperty = \CIBlockProperty::GetList([], [
            "IBLOCK_ID" => $iblockID,
            "CODE" => "NEW_PROP2"
        ]);

        if ($arProperty = $dbProperty->Fetch()) {
            \CIBlockProperty::Delete($arProperty["ID"]);
        }
    }
}
