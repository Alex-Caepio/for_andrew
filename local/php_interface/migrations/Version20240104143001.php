<?php

namespace Sprint\Migration;


use CIBlock;
use CIBlockProperty;
use CIBlockType;

class Version20240104143001 extends Version
{
    protected $description = "Создание инфоблока и свойства";

    protected $moduleVersion = "4.6.1";

    public function up()
    {
        if (!\CModule::IncludeModule("iblock")) {
            die('Модуль "Инфоблоки" не установлен');
        }

        $type = 'catalog';

        $iblock = new CIBlock;
        $iblockID = $iblock->Add(array(
            'ACTIVE' => 'Y',
            'NAME' => 'Новый каталог',
            'CODE' => 'new_catalog',
            'IBLOCK_TYPE_ID' => $type,
            'SITE_ID' => array('s1'), // ID сайта
            'SORT' => 100,
            'GROUP_ID' => array('10' => 'R')
        ));

        if ($iblockID > 0) {
            echo "Инфоблок успешно создан. ID: " . $iblockID;
        } else {
            echo 'Ошибка создания инфоблока';
        }

        $ibp = new CIBlockProperty;
        $PropID = $ibp->Add(array(
            'NAME' => 'Новое свойство',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'CODE' => 'NEW_PROP',
            'PROPERTY_TYPE' => 'S',
            'IBLOCK_ID' => $iblockID
        ));

        if ($PropID > 0) {
            echo "Свойство успешно создано. ID: " . $PropID;
        } else {
            echo 'Ошибка создания свойства';
        }
    }

    public function down()
    {
        if (!\CModule::IncludeModule("iblock")) {
            die('Модуль "Инфоблоки" не установлен');
        }

        $iblockCode = 'new_catalog';

        $iblockID = CIBlock::GetList([], ['CODE' => $iblockCode])->Fetch()['ID'];

        if ($iblockID) {
            $properties = CIBlockProperty::GetList([], ["IBLOCK_ID" => $iblockID]);
            while ($prop_fields = $properties->GetNext()) {
                CIBlockProperty::Delete($prop_fields["ID"]);
            }
            
            if (!CIBlock::Delete($iblockID)) {
                echo "Ошибка удаления инфоблока\n";
            } else {
                echo "Инфоблок удален\n";
            }
        } else {
            echo "Инфоблок не найден\n";
        }
    }
}
