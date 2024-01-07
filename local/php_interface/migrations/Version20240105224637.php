<?php

namespace Sprint\Migration;

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use Bitrix\Main\Entity;


class Version20240105224637 extends Version
{
    protected $description = "Добавление Highload-блока";

    public function up()
    {
        if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
            die('Модуль "Highload-блоки" не установлен');
        }

        // Добавление Highload-блока
        $result = HLBT::add([
            'NAME' => 'NewHighloadBlock',
            'TABLE_NAME' => 'new_highloadblock',
        ]);

        if (!$result->isSuccess()) {
            die('Ошибка при создании Highload-блока: ' . implode('; ', $result->getErrorMessages()));
        }

        $highloadBlockId = $result->getId();

        // Добавление стандартных полей
        $fields = [
            'UF_NAME' => 'string', // Тип данных - строка
            'UF_DATE' => 'datetime' // Тип данных - дата/время
            // Другие поля можно добавить здесь
        ];

        foreach ($fields as $fieldName => $fieldType) {
            $userTypeEntity = new \CUserTypeEntity();
            $userTypeEntity->Add([
                'ENTITY_ID' => 'HLBLOCK_' . $highloadBlockId,
                'FIELD_NAME' => $fieldName,
                'USER_TYPE_ID' => $fieldType,
                'SORT' => 100,
                'MULTIPLE' => 'N',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => '',
                'EDIT_IN_LIST' => '',
                'IS_SEARCHABLE' => 'N'
            ]);
        }
    }

    public function down()
    {
        if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
            die('Модуль "Highload-блоки" не установлен');
        }

        // Поиск и удаление Highload-блока
        $result = HLBT::getList([
            'filter' => ['=NAME' => 'NewHighloadBlock']
        ]);
        if ($row = $result->fetch()) {
            HLBT::delete($row['ID']);
        }
    }
}
