<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class InfoBlock3 extends ObjectModel
{
    public $id;
    
    public $id_shop;
    
    public $text;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'info3',
        'primary' => 'id_info3',
        'multilang' => true,
        'fields' => array(
            'id_shop' =>            array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
            // Lang fields
            'text' =>                array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
        )
    );
}
