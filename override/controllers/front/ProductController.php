<?php

/*
 * This file is part of the "Prestashop Clean URLs" module.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/prestashop-clean-urls
 */

class ProductController extends ProductControllerCore
{
    public function init()
    {
        $id_product = (int) Tools::getValue('id_product');
        if ($id_product) {
            $this->product = new Product($id_product, true, $this->context->language->id, $this->context->shop->id);

        $productMan =  $this->product->id_manufacturer;

        if ($productMan == 209) {
            $this->setTemplate('catalog/product-rational');
        } else {
            $this->setTemplate('catalog/product');
        }
      }
        parent::init();
    }
}
