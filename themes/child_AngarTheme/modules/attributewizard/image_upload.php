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

include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/attributewizard.php');
$aw = new AttributeWizard();

if ($aw->_aw_random != Tools::getValue('aw_random')) {
    print 'No Permissions';
    exit;
}

$ps_version = (float) (Tools::substr(_PS_VERSION_, 0, 3));

$attributes = Tools::unSerialize(Configuration::get('AW_ATTRIBUTES'));


$action = Tools::getValue('action');
if ($action == 'delete_image') {
    $id_group = Tools::getValue('id_group');
    $filename = $aw->getGroupImage($id_group, true);
    unlink(dirname(__FILE__) . '/views/img/' . $filename);
    echo 'success';
    return true;
}
$order = $aw->isInGroup($_REQUEST['id_group'], $attributes);
if ($attributes[$order]['image_upload']) {
    $attributes[$order]['image_upload'] ++;
} else {
    $attributes[$order]['image_upload'] = 1;
}

Configuration::updateValue('AW_ATTRIBUTES', serialize($attributes));

$uploaddir = dirname(__FILE__) . '/views/img/';
$uploadfile = Tools::strtolower($uploaddir . 'id_group_' . $_REQUEST['id_group'] . Tools::substr(basename($_FILES['userfile']['name']), strrpos(basename($_FILES['userfile']['name']), '.')));

$info = getimagesize($_FILES['userfile']['tmp_name']);
if ($info === false) {
    die("Unable to determine image type of uploaded file");
}
if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
    die("Not a gif/jpeg/png");
}
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    if (Configuration::get('AW_IMAGE_RESIZE') == 1) {
        $newWidth = Configuration::get('AW_IMAGE_RESIZE_WIDTH');
        $path_info = pathinfo($uploadfile);
        $extension = Tools::strtolower($path_info['extension']);
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $src = imagecreatefromjpeg($uploadfile);
        } else if ($extension == 'png') {
            $src = imagecreatefrompng($uploadfile);
        } else {
            $src = imagecreatefromgif($uploadfile);
        }
        list($width, $height) = getimagesize($uploadfile);
        $newHeight = ($height / $width) * $newWidth;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        $no_extention = Tools::substr($uploadfile, 0, Tools::strlen($uploadfile) - Tools::strlen($extension) - 1);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        if (file_exists($no_extention . '.gif')) {
            unlink($no_extention . '.gif');
        }
        if (file_exists($no_extention . '.jpeg')) {
            unlink($no_extention . '.jpeg');
        }
        if (file_exists($no_extention . '.jpg')) {
            unlink($no_extention . '.jpg');
        }
        if (file_exists($no_extention . '.png')) {
            unlink($no_extention . '.png');
        }
        imagejpeg($tmp, $uploadfile, 85);
    }
    echo 'success';
} else {
    echo 'error';
}
