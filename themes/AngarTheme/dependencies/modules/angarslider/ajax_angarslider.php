<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('angarslider.php');

$angar_slider = new AngarSlider();
$slides = array();

if (!Tools::isSubmit('secure_key') || Tools::getValue('secure_key') != $angar_slider->secure_key || !Tools::getValue('action')) {
    die(1);
}

if (Tools::getValue('action') == 'updateSlidesPosition' && Tools::getValue('slides')) {
    $slides = Tools::getValue('slides');

    foreach ($slides as $position => $id_slide) {
        $res = Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'angarslider_slides` SET `position` = '.(int)$position.'
			WHERE `id_angarslider_slides` = '.(int)$id_slide);
    }

    $angar_slider->clearCache();
}
