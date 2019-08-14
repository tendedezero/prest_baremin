<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2020 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class pixelgoogle extends ObjectModel
{
    public $id_pixelgoogle;
    public $id_google;
    public $id_category;
    public $id_lang;
    public $value;
    public static $definition = array(
        'table' => 'pixelgoogle',
        'primary' => 'id_pixelgoogle',
        'multilang' => false,
        'fields' => array(
            'id_pixelgoogle' => array('type' => ObjectModel :: TYPE_INT),
            'id_google' => array('type' => ObjectModel :: TYPE_INT),
            'id_category' => array('type' => ObjectModel :: TYPE_INT),
            'id_lang' => array('type' => ObjectModel :: TYPE_INT),
            'value' => array('type' => ObjectModel :: TYPE_STRING),
        ),
    );

    public function __construct($id_association = null)
    {
        parent::__construct($id_association);
    }

    public static function getAllByLanguage($id_lang)
    {
        $array = array();
        foreach (Db::getInstance(_PS_USE_SQL_SLAVE_)->Executes('SELECT * FROM `' . _DB_PREFIX_ . 'pixelgoogle` WHERE id_lang=' . $id_lang . '') AS $pixelgoogle)
        {
            $array[$pixelgoogle['id_category']] = $pixelgoogle;
        }
        return $array;
    }

    public static function getByDetails($id_category, $id_google = null, $id_lang)
    {
        $association = Db::getInstance(_PS_USE_SQL_SLAVE_)->Executes('SELECT * FROM `' . _DB_PREFIX_ . 'pixelgoogle` WHERE id_category=' . $id_category . ' AND id_lang=' . $id_lang . '');
        if (isset($association[0]['id_pixelgoogle']))
        {
            return $association[0]['id_pixelgoogle'];
        }
        else
        {
            return 0;
        }
    }
}