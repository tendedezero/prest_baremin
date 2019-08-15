<?php
/**
 * 2008 - 2017 Presto-Changeo
 *
 * MODULE Attribute Wizard
 *
 * @version   2.0.0
 * @author    Presto-Changeo <info@presto-changeo.com>
 * @link      http://www.presto-changeo.com
 * @copyright Copyright (c) permanent, Presto-Changeo
 * @license   Addons PrestaShop license limitation
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Addons
 * for all its modules is valid only once for a single shop.
 */

//call module
include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/attributewizard.php');
$aw = new AttributeWizard();

$aw_random = Tools::getValue('aw_random');
if ($aw_random != Configuration::get('AW_RANDOM')) {
    die('No Access');
}

$result = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'aw_attribute_wizard`');
$result = $result[0]['aw_attributes'];

$attributes = unserialize($result);
$nattributes = array();

$id_group = Tools::getValue('id_group');
if ($id_group == 'row') {
    $attribute_group_order = 1;
} else {
    $attribute_value_order = Tools::getValue('attribute_value_order');
}
if ($attribute_group_order == 1) {
    $idsInOrder = Tools::getValue('idsInOrder');
    $idsInOrder = explode(",", $idsInOrder);
    $order = 0;
    foreach ($idsInOrder as $ids) {
        if ($ids != "") {
            $id_group = $ids;
            $group = $aw->isInGroup($id_group, $attributes);
            $nattributes[$order] = $attributes[$group];
            $order++;
        }
    }
    if (count($nattributes) == count($attributes)) {
        Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'aw_attribute_wizard` SET aw_attributes = "' . pSQL((serialize($nattributes))) . '"');
    }
} else if ($attribute_value_order == 1) {
    $order = 0;
    $group = Tools::getValue('id_group');
    $idsInOrder = Tools::getValue('idsInOrder');
    $idsInOrder = explode(",", $idsInOrder);
    foreach ($idsInOrder as $ids) {
        if ($ids != "") {
            $id_value = $ids;
            $group = $aw->isInGroup($id_group, $attributes);
            $attr = $aw->isInAttribute($id_value, $attributes[$group]["attributes"]);
            $nattributes[$order] = $attributes[$group]["attributes"][$attr];
            $order++;
        }
    }
    $attributes[$group]['attributes'] = $nattributes;
    Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'aw_attribute_wizard` SET aw_attributes = "' . pSQL((serialize($attributes))) . '"');
}
