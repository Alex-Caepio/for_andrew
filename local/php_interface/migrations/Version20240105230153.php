<?php

namespace Sprint\Migration;

use Bitrix\Highloadblock\HighloadBlockTable as HLBT;
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;


class Version20240105230153 extends Version
{
    protected $moduleVersion = "4.6.1";

    protected $description = "Добавление нового поля в Highload-блок NewHighloadBlock";

    protected $highloadBlockName = 'NewHighloadBlock';
    protected $fieldName = 'UF_NEW_FIELD';
    protected $fieldType = 'string';

    public function up()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException('Модуль "Highload-блоки" не установлен');
        }

        $highloadBlockId = $this->getHighloadBlockId($this->highloadBlockName);
        if (!$highloadBlockId) {
            throw new SystemException("Highload-блок {$this->highloadBlockName} не найден");
        }

        $userTypeEntity = new \CUserTypeEntity();
        $userFieldId = $userTypeEntity->Add([
            'ENTITY_ID' => 'HLBLOCK_' . $highloadBlockId,
            'FIELD_NAME' => $this->fieldName,
            'USER_TYPE_ID' => $this->fieldType,
            'SORT' => 100,
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => '',
            'EDIT_IN_LIST' => '',
            'IS_SEARCHABLE' => 'N'
        ]);

        if (!$userFieldId) {
            throw new SystemException('Ошибка при создании нового поля');
        }
    }

    public function down()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new SystemException('Модуль "Highload-блоки" не установлен');
        }
        
        $highloadBlockId = $this->getHighloadBlockId($this->highloadBlockName);
        if (!$highloadBlockId) {
            throw new SystemException("Highload-блок {$this->highloadBlockName} не найден");
        }

        $dbRes = \CUserTypeEntity::GetList([], [
            'ENTITY_ID' => 'HLBLOCK_' . $highloadBlockId,
            'FIELD_NAME' => $this->fieldName
        ]);

        if ($arRes = $dbRes->Fetch()) {
            $userTypeEntity = new \CUserTypeEntity();
            $userTypeEntity->Delete($arRes['ID']);
        }
    }

    private function getHighloadBlockId($name)
    {
        $result = HLBT::getList([
            'filter' => ['=NAME' => $name]
        ])->fetch();

        return $result ? $result['ID'] : false;
    }
}
