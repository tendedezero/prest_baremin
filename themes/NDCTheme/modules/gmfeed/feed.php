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
include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('gmfeed.php');
$thismodule = new gmfeed();
include_once('model/google.php');

class Feed
{
    public $available_fields;
    public $id_country_default;
    public $default_carrier;
    public $all_carriers;

    function __construct()
    {
        $this->all_carriers = Carrier::getCarriers(Tools::getValue('export_language', Configuration::get('PS_LANG_DEFAULT')), true, false, false, null, Carrier::ALL_CARRIERS);
        $this->default_carrier = new Carrier((int)Configuration::get('PS_CARRIER_DEFAULT'));
        $this->id_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
        $this->context = Context::getContext();
        $this->available_fields['combinations'] = array(
            'id' => array('label' => 'ID'),
            'gtin' => array('label' => 'GTIN'),
            'identifier_exists' => array('label' => 'Identifier_exists'),
            'name' => array('label' => 'Title'),
            'quantity' => array('label' => 'Availability'),
            'brand' => array('label' => 'Brand'),
            'include_url' => array('label' => 'Link'),
            'image_url' => array('label' => 'Image link'),
            'additional_image_link' => array('label' => 'Additional_image_link'),
            'item_subtitle' => array('label' => 'Item subtitle'),
            'description_short' => array('label' => 'Description'),
            'item_category' => array('label' => 'Item category'),
            'condition' => array('label' => 'Condition'),
            'price_tex' => array('label' => 'Price'),
            'price_tin' => array('label' => 'Price'),
            'sale_price_tin' => array('label' => 'Sale Price'),
            'sale_price_tex' => array('label' => 'Sale Price'),
            'contextual_keywords' => array('label' => 'Contextual keywords'),
            'google_product_category' => array('label' => 'Google product category'),
        );
        $this->available_fields['products'] = array(
            'id' => array('label' => 'ID'),
            'gtin' => array('label' => 'GTIN'),
            'identifier_exists' => array('label' => 'Identifier_exists'),
            'name' => array('label' => 'Title'),
            'quantity' => array('label' => 'Availability'),
            'brand' => array('label' => 'Brand name'),
            'include_url' => array('label' => 'Link'),
            'image_url' => array('label' => 'Image URL'),
            'additional_image_link' => array('label' => 'Additional_image_link'),
            'item_subtitle' => array('label' => 'Item subtitle'),
            'description_short' => array('label' => 'Description'),
            'item_category' => array('label' => 'Item category'),
            'condition' => array('label' => 'Condition'),
            'price_tin' => array('label' => 'Price'),
            'price_tex' => array('label' => 'Price'),
            'sale_price_tin' => array('label' => 'Sale Price'),
            'sale_price_tex' => array('label' => 'Sale Price'),
            'contextual_keywords' => array('label' => 'Contextual keywords'),
            'google_product_category' => array('label' => 'Google_product_category')
        );
    }

    public function generateFeed()
    {
        $thismodule = new gmfeed();
        $export_type = Tools::getValue('export_type');
        $delimiter = Tools::getValue('export_delimiter');
        $id_lang = Tools::getValue('export_language');
        $id_shop = (int)$this->context->shop->id;
        $weight_unit = Configuration::get('PS_WEIGHT_UNIT');


        if (Tools::getValue('export_product_type') == 1)
        {
            $this->available_fields[$export_type]['product_type'] = array('label' => 'product_type');
        }
        if (Tools::getValue('export_product_weight') == 1)
        {
            $this->available_fields[$export_type]['weight'] = array('label' => 'shipping_weight');
        }
        if (Tools::getValue('export_shipping_info', 'false') != 'false')
        {
            if (Tools::getValue('export_shipping_info', 'false') != 0)
            {
                $this->available_fields[$export_type]['shipping'] = array('label' => 'shipping');
            }
        }

        set_time_limit(0);
        echo "\xEF\xBB\xBF";
        if (Tools::getValue('export_file_format', 'csv') == 'csv') {
            $fileName = $export_type . '_' . date("Y_m_d_H_i_s") . '.csv';
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Pragma: public");
        }
        elseif (Tools::getValue('export_file_format', 'csv') == 'xml')
        {
            $fileName = $export_type . '_' . date("Y_m_d_H_i_s") . '.xml';
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            header('Content-Type: application/xml; charset=utf-8');
            header("Content-Disposition: attachment; filename={$fileName}");
            header("Expires: 0");
            header("Pragma: public");
        }

        $f = fopen('php://output', 'w');
        if (Tools::getValue('export_what_pictures') != 1) {
            unset($this->available_fields[$export_type]['additional_image_link']);
        }

        $export_tax = Tools::getValue('export_tax');
        if ($export_tax == 'price_tin')
        {
            unset($this->available_fields[$export_type]['price_tex']);
            unset($this->available_fields[$export_type]['sale_price_tex']);
        }
        elseif ($export_tax == 'price_tex')
        {
            unset($this->available_fields[$export_type]['price_tin']);
            unset($this->available_fields[$export_type]['sale_price_tin']);
        }

        foreach ($this->available_fields[$export_type] as $field => $array)
        {

            $titles[] = $array['label'];
        }

        if (Tools::getValue('export_file_format', 'csv') == 'csv')
        {
            fputcsv($f, $titles, $delimiter, '"');
        }
        elseif (Tools::getValue('export_file_format', 'csv') == 'xml')
        {
            $xml_array = array();
        }

        $export_active = (Tools::getValue('export_active') == 0 ? false : true);
        $export_instock = (Tools::getValue('export_instock') == 0 ? false : true);
        $export_category = (Tools::getValue('export_category') == 99999 ? false : Tools::getValue('export_category'));
        $pixel_google_categories = pixelgoogle::getAllByLanguage($id_lang);
        $category_names = array();


        switch ($export_type)
        {
            case 'products':
                $currency = new Currency(Tools::getValue('export_currency'));
                $products = Product::getProducts($id_lang, 0, 0, 'id_product', 'ASC', $export_category, $export_active);
                foreach ($products as $product)
                {
                    if (Tools::getValue('gmfeed_products','false') != 'false'){
                        if (in_array($product['id_product'], Tools::getValue('gmfeed_products'))){
                            continue;
                        }
                    }

                    $line = array();
                    $p = new Product($product['id_product'], true, $id_lang, $id_shop);
                    $p->loadStockData();
                    $category_default = new Category($p->id_category_default, $id_lang);
                    foreach ($this->available_fields['products'] as $field => $array)
                    {
                        if ($export_instock == true && $p->quantity <= 0)
                        {
                            continue;
                        }

                        switch ($field)
                        {
                            case 'shipping':
                                if (Tools::getValue('export_shipping_info') == 1) {
                                    $line[$field] = ":::".Tools::ps_round((Tools::getValue('export_additional_sc') == 1 ? Tools::convertPrice($p->additional_shipping_cost, $currency, true):0)+Tools::convertPrice($this->getShippingCost($p->price, $p->weight), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                } elseif (Tools::getValue('export_shipping_info') == 2) {
                                    $line[$field] = ":::".Tools::ps_round((Tools::getValue('export_additional_sc') == 1 ? Tools::convertPrice($p->additional_shipping_cost, $currency, true):0)+Tools::convertPrice(Tools::getValue('export_shipping_info_price'), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                }
                                break;
                            case 'id':
                                $line[$field] = $p->id;
                                break;
                            case 'gtin':
                                if (Tools::getValue('export_gtin') == 'upc'){
                                    if (Validate::isUpc($p->upc)) {
                                        $line[$field] = $p->upc;
                                    } elseif(Validate::isEan13($p->ean13)) {
                                        $line[$field] = $p->ean13;
                                    } else {
                                        $line[$field] = '';
                                    }
                                } elseif (Tools::getValue('export_gtin') == 'ean13'){
                                    if (Validate::isEan13($p->ean13)) {
                                        $line[$field] = $p->ean13;
                                    } elseif(Validate::isUpc($p->upc)) {
                                        $line[$field] = $p->upc;
                                    } else {
                                        $line[$field] = '';
                                    }
                                } elseif (Tools::getValue('export_gtin') == 'reference'){
                                    if (isset($p->reference)) {
                                        $line[$field] = $p->reference;
                                    } elseif(Validate::isUpc($p->upc)){
                                        $line[$field] = $p->upc;
                                    } elseif (Validate::isEan13($p->ean13)){
                                        $line[$field] = $p->ean13;
                                    } else {
                                        $line[$field] = '';
                                    }
                                } else {
                                    $line[$field] = '';
                                }

                                if ($line[$field] == 0){
                                    $line[$field] = '';
                                }
                                break;
                            case 'identifier_exists':
                                if ($line['gtin'] == '' || $line['gtin'] == 0) {
                                    $line[$field] = 'false';
                                } else {
                                    $line[$field] = 'true';
                                }
                                break;
                            case 'name':
                                $line[$field] = ucfirst($p->name);
                                break;
                            case 'quantity':
                                $line[$field] = (Tools::getValue('export_instock_info') == 0 ? ($p->quantity > 0 ? 'In stock' : 'Out of stock'):(Tools::getValue('export_instock_info') == 1 ? 'In stock':(Tools::getValue('export_instock_info') == 2 ? 'Out of stock':'In stock')));
                                break;
                            case 'condition':
                                $line[$field] = $p->condition;
                                break;
                            case 'include_url':
                                $line['include_url'] = Context::getContext()->link->getProductLink($p->id, null, null, null, $id_lang, $this->context->shop->id);
                                break;
                            case 'image_url':
                                $imagelinks = array();
                                $line['image_url'] = '';
                                $images = $p->getImages($id_lang);
                                foreach ($images as $image)
                                {
                                    $imagelinks[] = $this->context->link->getImageLink($p->link_rewrite, $p->id . '-' . $image['id_image'], Tools::getValue('export_img'));
                                }
                                if (isset($imagelinks[0]))
                                {
                                    $line['image_url'] = $imagelinks[0];
                                }
                                else
                                {
                                    $line['image_url'] = Context::getContext()->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                }
                                if ($line['image_url'] == ''){
                                    $line['image_url'] = Context::getContext()->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                }
                                break;
                            case 'additional_image_link':
                                $line['additional_image_link']='';
                                $imagelinks = array();
                                $images = $p->getImages($id_lang);
                                foreach ($images as $image)
                                {
                                    $imagelinks[] = $this->context->link->getImageLink($p->link_rewrite, $p->id . '-' . $image['id_image'], Tools::getValue('export_img'));
                                }
                                if (isset($imagelinks[0]) && Tools::getValue('export_what_pictures') == 1)
                                {
                                    array_shift($imagelinks);
                                    $line['additional_image_link'] = $imagelinks[0];
                                }
                                else
                                {
                                    $line['additional_image_link'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                }

                                if ($line['additional_image_link'] == ''){
                                    $line['additional_image_link'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                }
                                break;
                            case 'item_subtitle':
                                $line[$field] = ucfirst($p->name);
                                $meta = Meta::getProductMetas($p->id, $id_lang, '');
                                $line['item_subtitle'] = $meta['meta_title'];
                                break;
                            case 'description_short':
                                $description_short = '-';
                                if (Tools::getValue('export_short_description_what', 'short') == 'short')
                                {
                                    if (Tools::getValue('export_removehtml', 0) !=0)
                                    {
                                        $description_short = strip_tags($p->description_short);
                                    }
                                    else
                                    {
                                        $description_short = $p->description_short;
                                    }
                                }
                                elseif (Tools::getValue('export_short_description_what', 'short') == 'desc')
                                {
                                    if (Tools::getValue('export_removehtml', 0) !=0)
                                    {
                                        $description_short = strip_tags($p->description);
                                    }
                                    else
                                    {
                                        $description_short = $p->description;
                                    }
                                }
                                $line[$field] = (strlen(trim($description_short)) > 0 ? trim($description_short) : '-');
                                break;
                            case 'item_category':
                                $line[$field] = $category_default->name;
                                break;
                            case 'price_tin':
                                $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(true, null, 2, null, false, false), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                break;
                            case 'price_tex':
                                $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(false, null, 2, null, false, false), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                break;
                            case 'sale_price_tin':
                                $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(true, null, 2), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                break;
                            case 'sale_price_tex':
                                $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(false, null, 2), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                break;
                            case 'brand':
                                $line[$field] = ($p->manufacturer_name != "" ? $p->manufacturer_name:Tools::getValue('export_manufacturers_default', 'Default'));
                                break;
                            case 'contextual_keywords':
                                $name = explode(" ", $p->name);
                                $line[$field] = implode(';', $name);
                                break;
                            case 'google_product_category':
                                if (isset($pixel_google_categories[$p->id_category_default]['id_google']))
                                {
                                    $line['google_product_category'] = $pixel_google_categories[$p->id_category_default]['id_google'];
                                }
                                else
                                {
                                    $line['google_product_category'] = 0;
                                }
                                break;
                            case 'product_type':
                                $category_names_array = array();
                                foreach (Product::getProductCategories($p->id) AS $pcatid) {
                                    if (!isset($category_names[$pcatid]))
                                    {
                                        $category_names[$pcatid] = new Category($pcatid, Tools::getValue('export_language'));
                                        $category_names_array[] = $category_names[$pcatid]->name;
                                    } else {
                                        $category_names_array[] = $category_names[$pcatid]->name;
                                    }
                                }
                                $line['product_type'] = implode(" > ", $category_names_array);
                                break;
                            case 'weight':
                                $line['weight'] = number_format($p->weight, 2,'.','').' '.$weight_unit;
                                break;
                        }
                    }

                    $include = 1;

                    if (Tools::getValue('export_manufacturers') != 99999)
                    {
                        if ($p->id_manufacturer != Tools::getValue('export_manufacturers'))
                        {
                            $include = 0;
                        }
                    }

                    if (Tools::getValue('export_suppliers') != 99999)
                    {
                        if (Supplier::getProductInformationsBySupplier(Tools::getValue('export_suppliers'), $p->id) == null)
                        {
                            $include = 0;
                        }

                    }

                    if ($include == 1)
                    {
                        if (Tools::getValue('export_file_format', 'csv') == 'csv')
                        {
                            $new_line = array();
                            foreach ($line as $lkey => $litem) {
                                $lkey = $this->changeKeyToGoogleFeed($lkey);
                                $new_line[$lkey] = $litem;
                            }
                            fputcsv($f, $new_line, $delimiter, '"');
                        }
                        elseif (Tools::getValue('export_file_format', 'csv') == 'xml')
                        {
                            $new_line = array();
                            foreach ($line as $lkey => $litem)
                            {
                                $lkey = $this->changeKeyToGoogleFeed($lkey);
                                $new_line[$lkey] = $litem;
                            }
                            $xml_array[]=$new_line;
                        }
                    }
                }
                break;
            case 'combinations':
                $currency = new Currency(Tools::getValue('export_currency', (int)Configuration::get('PS_CURRENCY_DEFAULT')));
                if (!Combination::isFeatureActive())
                {
                    return false;
                }
                $products = Product::getProducts($id_lang, 0, 0, 'id_product', 'ASC', $export_category, $export_active);
                foreach ($products as $product)
                {
                    if (Tools::getValue('gmfeed_products','false') != 'false'){
                        if (in_array($product['id_product'], Tools::getValue('gmfeed_products'))){
                            continue;
                        }
                    }

                    $line = array();
                    $p = new Product($product['id_product'], true, $id_lang, 1);
                    $p->loadStockData();
                    $category_default = new Category($p->id_category_default, $id_lang);
                    $sql = 'SELECT
                            pa.`supplier_reference` AS supplier_reference,
                            ag.`id_attribute_group`,
                            ag.`is_color_group`,
                            agl.`name` AS group_name,
                            agl.`public_name` AS public_group_name,
                            a.`id_attribute`,
                            a.`position` AS attribute_position,
                            ag.`position` AS group_position,
                            al.`name` AS attribute_name,
                            a.`color` AS attribute_color,
                            product_attribute_shop.`id_product_attribute` AS id_product_attribute,
                            IFNULL(stock.quantity, 0) as quantity,
                            pa.`price`,
                            product_attribute_shop.`ecotax`,
                            product_attribute_shop.`weight`,
                            pa.`ean13`,
                            product_attribute_shop.`wholesale_price`,
                            pa.`upc`,
                            pa.`default_on`,
                            pa.`reference` AS reference,
                            product_attribute_shop.`unit_price_impact`,
                            product_attribute_shop.`ecotax`,
                            product_attribute_shop.`minimal_quantity`,
                            product_attribute_shop.`available_date`,
                            product_attribute_shop.`id_shop` AS id_shop,
                            ag.`group_type`
                            FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                            ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                            ' . Product::sqlStock('pa', 'pa') . '
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute`)
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group`)
                            ' . Shop::addSqlAssociation('attribute', 'a') . '

                            WHERE pa.`id_product` = ' . (int)$p->id . '
                            GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                            ORDER BY pa.`id_product_attribute`';

                    $attributes = Db::getInstance()->executeS($sql);
                    if (count($attributes) <= 0)
                    {
                        continue;
                    }

                    if ($attributes)
                    {
                        $attributes_ready = array();
                        $attributes_details = array();

                        foreach ($attributes as $vvalue)
                        {
                            $attributes_details[$vvalue['id_product_attribute']]['new_group'][] = $vvalue['public_group_name'] . ':' . $vvalue['group_type'] . ':' . $vvalue['group_position'];
                            $attributes_details[$vvalue['id_product_attribute']]['new_attribute'][] = $vvalue['attribute_name'] . ':' . $vvalue['attribute_position'];
                            $attributes_ready[$vvalue['id_product_attribute']] = $vvalue;
                        }
                    }

                    if ($attributes_ready)
                    {
                        foreach ($attributes_ready as $value)
                        {
                            if ($export_instock == true && $value['quantity'] <= 0)
                            {
                                continue;
                            }
                            foreach ($this->available_fields['combinations'] as $field => $array)
                            {
                                switch ($field)
                                {
                                    case 'shipping':
                                        if (Tools::getValue('export_shipping_info') == 1) {
                                            $line[$field] = ":::".Tools::ps_round((Tools::getValue('export_additional_sc') == 1 ? Tools::convertPrice($p->additional_shipping_cost, $currency, true):0)+Tools::convertPrice($this->getShippingCost($p->price, $p->weight), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        } elseif (Tools::getValue('export_shipping_info') == 2) {
                                            $line[$field] = ":::".Tools::ps_round((Tools::getValue('export_additional_sc') == 1 ? Tools::convertPrice($p->additional_shipping_cost, $currency, true):0)+Tools::convertPrice(Tools::getValue('export_shipping_info_price'), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        }
                                        break;
                                    case 'id':
                                        if (Tools::getValue('export_identification') == 'id_product')
                                        {
                                            $id = $p->id;
                                        }
                                        elseif (Tools::getValue('export_identification') == 'id_combination')
                                        {
                                            $id = $value['id_product_attribute'];
                                        }
                                        elseif (Tools::getValue('export_identification') == 'id_product_id_combination')
                                        {
                                            $id = $p->id.'-'.$value['id_product_attribute'];
                                        }
                                        else
                                        {
                                            $id = $p->id;
                                        }
                                        $line[$field] = $id;
                                        break;
                                    case 'gtin':
                                        if (Tools::getValue('export_gtin') == 'upc'){
                                            if (Validate::isUpc($value['upc'])) {
                                                $line[$field] = $value['upc'];
                                            } elseif(Validate::isEan13($value['ean13'])) {
                                                $line[$field] = $value['ean13'];
                                            } else {
                                                $line[$field] = '';
                                            }
                                        } elseif (Tools::getValue('export_gtin') == 'ean13'){
                                            if (Validate::isEan13($value['ean13'])) {
                                                $line[$field] = $value['ean13'];
                                            } elseif(Validate::isUpc($value['upc'])) {
                                                $line[$field] = $value['upc'];
                                            } else {
                                                $line[$field] = '';
                                            }
                                        } elseif (Tools::getValue('export_gtin') == 'reference'){
                                            if (isset($p->reference)) {
                                                $line[$field] = $p->reference;
                                            } elseif(Validate::isUpc($value['upc'])){
                                                $line[$field] = $value['upc'];
                                            } elseif (Validate::isEan13($value['ean13'])){
                                                $line[$field] = $value['ean13'];
                                            } else {
                                                $line[$field] = '';
                                            }
                                        } else {
                                            $line[$field] = '';
                                        }

                                        if ($line[$field] == 0){
                                            $line[$field] = '';
                                        }
                                        break;
                                    case 'identifier_exists':
                                        if ($line['gtin'] == '' || $line['gtin'] == 0) {
                                            $line[$field] = 'false';
                                        } else {
                                            $line[$field] = 'true';
                                        }
                                        break;
                                    case 'name':
                                        $line[$field] = ucfirst($p->name);
                                        break;
                                    case 'quantity':
                                        $line[$field] = (Tools::getValue('export_instock_info') == 0 ? ($value['quantity'] > 0 ? 'In stock' : 'Out of stock'):(Tools::getValue('export_instock_info') == 1 ? 'In stock':(Tools::getValue('export_instock_info') == 2 ? 'Out of stock':'In stock')));
                                        break;
                                    case 'brand':
                                        $line[$field] = ($p->manufacturer_name != "" ? $p->manufacturer_name:Tools::getValue('export_manufacturers_default', 'Default'));
                                        break;
                                    case 'include_url':
                                        $line['include_url'] = Context::getContext()->link->getProductLink($p->id, null, null, null, $id_lang, $this->context->shop->id, $value['id_product_attribute']);
                                        break;
                                    case 'additional_image_link':
                                        $line['additional_image_link'] = '';
                                        if (Tools::getValue('export_what_pictures') == 1)
                                        {
                                            $images = $p->getImages($id_lang);
                                            foreach ($images as $image)
                                            {
                                                $imagelinks[] = $this->context->link->getImageLink($p->link_rewrite, $p->id . '-' . $image['id_image'], Tools::getValue('export_img'));
                                            }
                                            if (isset($imagelinks[0]))
                                            {
                                                array_shift($imagelinks);
                                                $line['additional_image_link'] = $imagelinks[0];
                                            } else {
                                                $line['additional_image_link'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                            }
                                            unset($images);
                                        }
                                        if ($line['additional_image_link'] == '') {
                                            $line['image_url'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                        }
                                        break;
                                    case 'image_url':
                                        $imagelinks = array();
                                        $line['image_url'] = '';
                                        $sql = 'SELECT id_image FROM `' . _DB_PREFIX_ . 'product_attribute_image` WHERE `id_product_attribute`=' . $value['id_product_attribute'];
                                        $images = Db::getInstance()->executeS($sql);

                                        $image_urls = array();
                                        foreach ($images as $image)
                                        {
                                            $line['image_url'] = '';
                                            if (isset($image['id_image']))
                                            {
                                                if ((int)$image['id_image'] > 0)
                                                {
                                                    $image_urls[] = $this->context->link->getImageLink($p->link_rewrite, $p->id . '-' . $image['id_image'], Tools::getValue('export_img'));
                                                }
                                            }
                                            if (isset($image_urls))
                                            {
                                                $line['image_url'] = (isset($image_urls[0]) ? $image_urls[0] : $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg');
                                            }
                                        }

                                        if ($line['image_url'] == '') {
                                            $line['image_url'] = Context::getContext()->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                        }
                                        unset($imagelinks);
                                        break;
                                    case 'item_subtitle':
                                        $line[$field] = ucfirst($p->name);
                                        $meta = Meta::getProductMetas($p->id, $id_lang, '');
                                        $line['item_subtitle'] = $meta['meta_title'];
                                        break;
                                    case 'description_short':
                                        $description_short = '-';
                                        if (Tools::getValue('export_short_description_what', 'short') == 'short')
                                        {
                                            if (Tools::getValue('export_removehtml', 0) !=0)
                                            {
                                                $description_short = strip_tags($p->description_short);
                                            }
                                            else
                                            {
                                                $description_short = $p->description_short;
                                            }
                                        }
                                        elseif (Tools::getValue('export_short_description_what', 'short') == 'desc')
                                        {
                                            if (Tools::getValue('export_removehtml', 0) !=0)
                                            {
                                                $description_short = strip_tags($p->description);
                                            }
                                            else
                                            {
                                                $description_short = $p->description;
                                            }
                                        }
                                        $line[$field] = (strlen(trim($description_short)) > 0 ? trim($description_short) : '-');
                                        break;
                                    case 'item_category':
                                        $line[$field] = $category_default->name;
                                        break;
                                    case 'condition':
                                        $line[$field] = $p->condition;
                                        break;
                                    case 'price_tin':
                                        $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(true, $value['id_product_attribute'], 2, null, false, false), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        break;
                                    case 'price_tex':
                                        $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(false, $value['id_product_attribute'], 2, null, false, false), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        break;
                                    case 'sale_price_tin':
                                        $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(true, $value['id_product_attribute'], 2, null, false, true), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        break;
                                    case 'sale_price_tex':
                                        $line[$field] = Tools::ps_round(Tools::convertPrice($p->getPrice(false, $value['id_product_attribute'], 2, null, false, true), $currency, true), _PS_PRICE_COMPUTE_PRECISION_) . ' ' . $currency->iso_code;
                                        break;
                                    case 'google_product_category':
                                        if (isset($pixel_google_categories[$p->id_category_default]['id_google']))
                                        {
                                            $line['google_product_category'] = $pixel_google_categories[$p->id_category_default]['id_google'];
                                        }
                                        else
                                        {
                                            $line['google_product_category'] = 0;
                                        }
                                        break;
                                    case 'contextual_keywords':
                                        $name = explode(" ", $p->name);
                                        $line[$field] = implode(';', $name);
                                        break;
                                        break;
                                    case 'product_type':
                                        $category_names_array = array();
                                        foreach (Product::getProductCategories($p->id) AS $pcatid) {
                                            if (!isset($category_names[$pcatid]))
                                            {
                                                $category_names[$pcatid] = new Category($pcatid, Tools::getValue('export_language'));
                                                $category_names_array[] = $category_names[$pcatid]->name;
                                            } else {
                                                $category_names_array[] = $category_names[$pcatid]->name;
                                            }
                                        }
                                        $line['product_type'] = implode(" > ", $category_names_array);
                                        break;
                                    case 'weight':
                                        $line['weight'] = number_format($value['weight'], 2,'.','').' '.$weight_unit;
                                        break;
                                }

                                if (!array_key_exists($field, $line))
                                {
                                    $line[$field] = '';
                                }
                            }

                            if (Tools::getValue('export_file_format', 'csv') == 'csv')
                            {
                                $new_line = array();
                                foreach ($line as $lkey => $litem) {
                                    $lkey = $this->changeKeyToGoogleFeed($lkey);
                                    $new_line[$lkey] = $litem;
                                }
                                fputcsv($f, $new_line, $delimiter, '"');
                            }
                            elseif (Tools::getValue('export_file_format', 'csv') == 'xml')
                            {
                                $new_line = array();
                                foreach ($line as $lkey => $litem) {
                                    $lkey = $this->changeKeyToGoogleFeed($lkey);
                                    $new_line[$lkey] = $litem;
                                }
                                $xml_array[]=$new_line;
                            }
                        }
                    }
                }
                break;
        }

        if (Tools::getValue('export_file_format', 'csv') == 'csv')
        {

        }
        elseif (Tools::getValue('export_file_format', 'csv') == 'xml')
        {
            $export_type = Tools::getValue('export_type', 'object');
            $xml = new SimpleXMLElement('<?xml version="1.0"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0"><title>'.$export_type.' - '.Context::getContext()->shop->name.'</title><link rel="self" href="'.Context::getContext()->shop->getBaseUrl(true, true).'"/><updated>'.date('c').'</updated></feed>');
            $this->array_to_xml($xml_array, $xml);
            echo $xml->asXML();
        }

        fclose($f);
        die();
    }
    public function array_to_xml($array, &$xml)
    {
        $export_type = Tools::getValue('export_type', 'object');
        foreach($array as $key => $value)
        {
            if(is_array($value))
            {
                $subnode = $xml->addChild("entry");
                $this->array_to_xml($value, $subnode);
            }
            else
            {
                $xml->addChild($key, htmlspecialchars("$value"), 'http://base.google.com/ns/1.0');
            }
        }
    }
    public function changeKeyToGoogleFeed($key) {
        $key = str_replace('sale_price_tin', 'sale_price', $key);
        $key = str_replace('sale_price_tex', 'sale_price', $key);
        $key = str_replace('price_tex', 'price', $key);
        $key = str_replace('price_tin', 'price', $key);
        $key = str_replace('name', 'title', $key);
        $key = str_replace('quantity', 'availability', $key);
        $key = str_replace('include_url', 'link', $key);
        $key = str_replace('description_short', 'description', $key);
        $key = str_replace('image_url', 'image_link', $key);
        return $key;
    }

    function getShippingCost($price, $weight, $id_zone = 1) {
        $carrier_by_weight = $this->default_carrier->getDeliveryPriceByWeight($weight, $id_zone);
        if ($carrier_by_weight != false) {
            return $carrier_by_weight;
        }

        $carrier_by_price = $this->default_carrier->getDeliveryPriceByPrice($price, $id_zone, Tools::getValue('export_currency'));
        if ($carrier_by_price != false) {
            return $carrier_by_price;
        }

        foreach ($this->all_carriers AS $carrier) {
            $carrier = new Carrier($carrier['id_carrier']);
            if ($carrier->shipping_method == 2) {
                $carrier_by_price = $carrier->getDeliveryPriceByPrice($price, $id_zone, Tools::getValue('export_currency'));
                if ($carrier_by_price != false) {
                    return $carrier_by_price;
                }
            } elseif ($carrier->shipping_method == 1) {
                $carrier_by_weight = $carrier->getDeliveryPriceByWeight($weight, $id_zone);
                if ($carrier_by_weight != false) {
                    return $carrier_by_weight;
                }
            }
        }

    }
}

$feed = new Feed();
$feed->generateFeed();