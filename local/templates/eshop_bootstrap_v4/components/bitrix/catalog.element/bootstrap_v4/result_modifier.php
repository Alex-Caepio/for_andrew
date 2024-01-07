<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

if (!CModule::IncludeModule("iblock")) {
    die('Модуль "Инфоблоки" не установлен');
}

// Получаем информацию об инфоблоке по его символьному коду
$res = CIBlock::GetList(
    Array(),
    Array(
        'CODE'=>'clothes', // символьный код инфоблока
        'SITE_ID'=>SITE_ID
    ), true
);

while($ar_res = $res->Fetch())
{
    echo 'Название инфоблока: '.$ar_res['NAME'].'<br>';
    echo 'ID инфоблока: '.$ar_res['ID'].'<br>';
    echo 'Тип инфоблока: '.$ar_res['IBLOCK_TYPE_ID'].'<br>';
}