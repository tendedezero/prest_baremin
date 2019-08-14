<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2019 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

require_once(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(dirname(__FILE__) . '/../../init.php');
include_once(dirname(__FILE__) . '/send2friend.php');

$module = new send2friend();

if (Module::isEnabled('send2friend') && Tools::getValue('action') == 'sendToMyFriend' && Tools::getValue('secure_key') == $module->secure_key) {
    // Retrocompatibilty with old theme

    $friendName = Tools::getValue('name');
    $friendMail = Tools::getValue('email');
    $id_product = Tools::getValue('id_product');

    if (!$friendName || !$friendMail || !$id_product) {
        die('0');
    }

    $isValidEmail = Validate::isEmail($friendMail);
    if (false === $isValidEmail) {
        die('0');
    }

    /* Email generation */
    $product     = new Product((int)$id_product, false, Tools::getValue('id_lang'));
    $productLink = Context::getContext()->link->getProductLink($product);
    $customer    = Context::getContext()->cookie->customer_firstname ? Context::getContext()->cookie->customer_firstname . ' ' . Context::getContext()->cookie->customer_lastname : $module->l('A friend', 'send2friend_ajax');

    $templateVars = array(
        '{product}'      => $product->name,
        '{product_link}' => $productLink,
        '{customer}'     => $customer,
        '{name}'         => Tools::safeOutput($friendName)
    );
    /* Email sending */
    if (!Mail::Send((int)Tools::getValue('id_lang'),
        'send_to_a_friend',
        sprintf(Configuration::get('SEND2FRIEND_TITLE', (int)Tools::getValue('id_lang')), $customer, $product->name),
        $templateVars,
        $friendMail,
        null,
        (Context::getContext()->cookie->email ? Context::getContext()->cookie->email : null),
        strval(Configuration::get('PS_SHOP_NAME', null, null, Context::getContext()->shop->id)),
        null,
        null,
        dirname(__FILE__) . '/mails/',
        false,
        Context::getContext()->shop->id)) {
        die('0');
    }
    die('1');
}
die('0');
