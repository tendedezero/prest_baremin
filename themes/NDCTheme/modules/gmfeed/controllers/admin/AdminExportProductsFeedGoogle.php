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
include_once('../modules/gmfeed/model/google.php');
include_once('../modules/gmfeed/gmfeed.php');

class AdminExportProductsFeedGoogleController extends ModuleAdminController
{
    public $available_fields;
    public $id_country_default;
    public $default_carrier;
    public $all_carriers;

    public function __construct()
    {
        $this->all_carriers = Carrier::getCarriers(Tools::getValue('export_language', Configuration::get('PS_LANG_DEFAULT')), true, false, false, null, Carrier::ALL_CARRIERS);
        $this->default_carrier = new Carrier((int)Configuration::get('PS_CARRIER_DEFAULT'));
        $this->id_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
        $this->taxonomyFiles = array(
            'Argentina' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt',
            'Australia' => 'http://www.google.com/basepages/producttype/taxonomy-with-ids.en-AU.txt',
            'Austria' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.de-DE.txt',
            'Belgium French' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.fr-FR.txt',
            'Belgium Dutch' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.nl-NL.txt',
            'Brazil' => 'http://www.google.com/basepages/producttype/taxonomy-with-ids.pt-BR.txt',
            'Canada English' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt',
            'Canada French' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.fr-FR.txt',
            'Chile' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt',
            'Colombia' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt',
            'Czechia' => 'http://www.google.com/basepages/producttype/taxonomy-with-ids.cs-CZ.txt',
            'Denmark' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.da-DK.txt',
            'France' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.fr-FR.txt',
            'Germany' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.de-DE.txt',
            'Ireland' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-GB.txt',
            'Italy' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.it-IT.txt',
            'Japan' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.ja-JP.txt',
            'Malaysia' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt',
            'Mexico' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt',
            'Netherlands' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.nl-NL.txt',
            'New Zealand' => 'http://www.google.com/basepages/producttype/taxonomy-with-ids.en-AU.txt',
            'Philippines' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt',
            'Poland' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.pl-PL.txt',
            'Portugal' => 'http://www.google.com/basepages/producttype/taxonomy-with-ids.pt-BR.txt',
            'Russia' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.ru-RU.txt',
            'Singapore' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt',
            'South Africa' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt',
            'Spain' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.es-ES.txt',
            'Sweden' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.sv-SE.txt',
            'Switzerland French' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.fr-CH.txt',
            'Switzerland German' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.de-CH.txt',
            'Switzerland Italian' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.it-CH.txt',
            'Turkey' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.tr-TR.txt',
            'United Kingdom' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-GB.txt',
            'United States' => 'https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt'
        );

        $this->bootstrap = true;
        parent::__construct();
        $this->meta_title = $this->l('Export Products');

        if (!$this->module->active)
        {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
        }
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

    public static function getAllCategoriesName($root_category = null, $id_lang = false, $active = true, $groups = null,
        $use_shop_restriction = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
    {
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }

        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }

        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }

        $cache_id = 'Category::getAllCategoriesName_'.md5((int)$root_category.(int)$id_lang.(int)$active.(int)$use_shop_restriction
                                                          .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));

        if (!Cache::isStored($cache_id)) {
            $result = Db::getInstance()->executeS('
				SELECT c.id_category, cl.name
				FROM `'._DB_PREFIX_.'category` c
				'.($use_shop_restriction ? Shop::addSqlAssociation('category', 'c') : '').'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
				'.(isset($groups) && Group::isFeatureActive() ? 'LEFT JOIN `'._DB_PREFIX_.'category_group` cg ON c.`id_category` = cg.`id_category`' : '').'
				'.(isset($root_category) ? 'RIGHT JOIN `'._DB_PREFIX_.'category` c2 ON c2.`id_category` = '.(int)$root_category.' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`' : '').'
				WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
				'.($active ? ' AND c.`active` = 1' : '').'
				'.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.implode(',', $groups).')' : '').'
				'.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
				'.($sql_sort != '' ? $sql_sort : ' ORDER BY c.`level_depth` ASC').'
				'.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '').'
				'.($sql_limit != '' ? $sql_limit : '')
            );

            Cache::store($cache_id, $result);
        } else {
            $result = Cache::retrieve($cache_id);
        }

        return $result;
    }

    public function ajaxProcess()
    {
        if (Tools::getValue('action') == 'downloadCategories' && Tools::getValue('language_code', 'false') != 'false')
        {
            $language = explode("-", Tools::getValue('language_code'));
            if (count($language) > 1)
            {
                $language_code_first = $language[0];
                $language_code_second = $language[1];
            }
            else
            {
                $language_code_first = $language[0];
                $language_code_second = $language[0];
            }

            $download = Tools::file_get_contents('https://www.google.com/basepages/producttype/taxonomy-with-ids.' . $language_code_first . '-' . strtoupper($language_code_second) . '.txt');
            if ((bool)$download == 0)
            {
                $download = Tools::file_get_contents('https://www.google.com/basepages/producttype/taxonomy-with-ids.' . $language_code_second . '-' . strtoupper($language_code_second) . '.txt');
                if ((bool)$download == 0)
                {
                    $download = Tools::file_get_contents('https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt');
                }
            }

            if (file_exists('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt') && (bool)$download != 0)
            {
                @unlink('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt');
            }

            if ((bool)$download != 0 && @file_put_contents('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt', $download))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }

        if (Tools::getValue('action') == 'downloadCategoriesCustom' && Tools::getValue('language_code', 'false') != 'false')
        {

            $download = Tools::file_get_contents($this->taxonomyFiles[Tools::getValue('selected_option')]);

            if (file_exists('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt') && (bool)$download != 0)
            {
                @unlink('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt');
            }

            if ((bool)$download != 0 && @file_put_contents('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt', $download))
            {
                echo 1;
            }
            else
            {
                echo 0;
            }
        }
        elseif (Tools::getValue('action') == 'googleCategories')
        {
            $categories = array();
            $id_language = $this->context->language->id;
            foreach (Language::getLanguages(true) AS $language)
            {
                if (strtolower($language['language_code']) == strtolower(Tools::getValue('language_code')))
                {
                    $id_language = $language['id_lang'];
                }
            }

            $google_categories = pixelgoogle::getAllByLanguage($id_language);

            foreach (Self::getAllCategoriesName(null, $id_language, false) AS $category)
            {
                $categories[$category['id_category']]['name'] = $category['name'];
                $categories[$category['id_category']]['id'] = $category['id_category'];
                if (Configuration::get('pf_tree'))
                {
                    $cat = new Category($category['id_category']);
                    $categories[$category['id_category']]['parents'] = array_reverse($cat->getParentsCategories());
                }
            }

            $this->context->smarty->assign('gmfeed_categories', $categories);
            $this->context->smarty->assign('gmfeed_google_categories', $google_categories);

            echo $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'gmfeed/views/script-fancybox.tpl') . $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'gmfeed/views/categories-fancybox.tpl');
        }
        elseif (Tools::getValue('action') == 'searchCategory')
        {
            $contents = Tools::file_get_contents('../modules/gmfeed/google/' . Tools::getValue('language_code') . '.txt');
            $pattern = preg_quote(Tools::getValue('q'), '/');
            $pattern = "/^.*$pattern.*\$/mi";
            if (preg_match_all($pattern, $contents, $matches))
            {
                echo implode("\n", $matches[0]);
            }
            else
            {
                echo $this->l('No matches found');
            }

        }
        elseif (Tools::getValue('action') == 'saveCategories')
        {
            foreach (Language::getLanguages(true) AS $language)
            {
                if (strtolower($language['language_code']) == strtolower(Tools::getValue('language_code')))
                {
                    $id_language = $language['id_lang'];
                }
            }
            $id_category = Tools::getValue('id_category');
            $id_google = preg_replace("/[^0-9]/", '', Tools::getValue('value'));

            $pg = new pixelgoogle(((int)Tools::getValue('id_association') > 0 ? Tools::getValue('id_association') : pixelgoogle::getByDetails(Tools::getValue('id_category'), $id_google, $id_language)));

            $pg->id_category = $id_category;
            $pg->id_google = $id_google;
            $pg->id_lang = $id_language;
            $pg->value = Tools::getValue('value');
            $pg->save();
        }
        elseif (Tools::getValue('action') == 'deleteCategories')
        {
            foreach (Language::getLanguages(true) AS $language)
            {
                if (strtolower($language['language_code']) == strtolower(Tools::getValue('language_code')))
                {
                    $id_language = $language['id_lang'];
                }
            }

            $pg = new pixelgoogle(((int)Tools::getValue('id_association') > 0 ? Tools::getValue('id_association') : pixelgoogle::getByDetails(Tools::getValue('id_category'), null, $id_language)));
            $pg->delete();
        }
    }

    public function renderView()
    {
        return $this->renderConfigurationForm();
    }

    public function renderConfigurationForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $langs = Language::getLanguages();
        $id_shop = (int)$this->context->shop->id;
        $options_images = ImageType::getImagesTypes('products');

        foreach ($langs as $key => $language)
        {
            $options[] = array(
                'id_option' => $language['id_lang'],
                'name' => $language['name']
            );
        }

        $cats = $this->getCategories($lang->id, true, $id_shop);

        $pricetax = array(
            array(
                'id_option' => 'price_tin',
                'name' => 'Price Tax Included'
            ),
            array(
                'id_option' => 'price_tex',
                'name' => 'Price Tax Excluded'
            )
        );

        $yesno = array(
            array(
                'id_option' => '0',
                'name' => $this->l('No'),
            ),
            array(
                'id_option' => '1',
                'name' => $this->l('Yes'),
            )
        );

        $categories[] = array(
            'id_option' => 99999,
            'name' => 'All'
        );

        foreach ($cats as $key => $cat)
        {
            $categories[] = array(
                'id_option' => $cat['id_category'],
                'name' => $cat['name']
            );
        }

        $manufacturers[] = array(
            'id_option' => 99999,
            'name' => 'All'
        );
        foreach (Manufacturer::getManufacturers(false, $this->context->language->id, false) as $key => $man)
        {
            $manufacturers[] = array(
                'id_option' => $man['id_manufacturer'],
                'name' => $man['name']
            );
        }

        $suppliers[] = array(
            'id_option' => 99999,
            'name' => 'All'
        );
        foreach (Supplier::getSuppliers(false, $this->context->language->id, false) as $key => $man)
        {
            $suppliers[] = array(
                'id_option' => $man['id_supplier'],
                'name' => $man['name']
            );
        }

        $export_shipping_info = array(
            array(
                'id_option' => '0',
                'name' => $this->l('Do not include')
            ),
            array(
                'id_option' => '1',
                'name' => $this->l('Include (shipping price calculated for each item separately)')
            ),
            array(
                'id_option' => '2',
                'name' => $this->l('Set shipping price manually')
            ),
        );

        $export_id_product = array(
            array(
                'id_option' => 'id_product',
                'name' => $this->l('id_product')
            ),
            array(
                'id_option' => 'id_combination',
                'name' => $this->l('id_combination')
            ),
            array(
                'id_option' => 'id_product_id_combination',
                'name' => $this->l('id_product-id_attribute')
            ),
        );

        $what_to_export = array(
            array(
                'id_option' => 'products',
                'name' => $this->l('Products')
            ),
            array(
                'id_option' => 'combinations',
                'name' => $this->l('All products variants')
            ),
        );
        $export_file_format = array(
            array(
                'id_option' => 'csv',
                'name' => $this->l('CSV')
            ),
            array(
                'id_option' => 'xml',
                'name' => $this->l('XML')
            ),
        );

        $inputs = array(
            array(
                'type' => 'select',
                'label' => $this->l('Type of file module will generate'),
                'desc' => $this->l('Module allows to generate CSV or XML file. Here you can decide what format of file module will create. Suggested: lightweight CSV'),
                'name' => 'export_file_format',
                'class' => 't export_format',
                'value' => 'csv',
                'options' => array(
                    'query' => $export_file_format,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('What you want to export?'),
                'name' => 'export_type',
                'class' => 't export_type',
                'value' => 'products',
                'options' => array(
                    'query' => $what_to_export,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('How to identify product?'),
                'name' => 'export_identification',
                'options' => array(
                    'query' => $export_id_product,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
                'desc' => $this->l('It is an unique identification number of product in shop\'s catalog. It will be introduced to feed as an unique ID of product')
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Unique product identifier'),
                'desc' => $this->l('Selected order of fields: ').'<span id="gtin_details" style="font-weight:bold;"></span>. '.$this->l('If first field will not exist, module will try to use second one. If second will not exist too - module will include information that product does not have unique product identifier').'<br/><br/> '.$this->l('Unique product identifiers (UPI) define the product you\'re selling in the global marketplace. They uniquely distinguish products you are selling and help match search queries with your offers. Unique product identifiers are assigned to each product by the manufacturer, so if you sell the same product as another retailer, the UPIs will be identical').' '
                . $this->l('Common unique product identifiers include Global Trade Item Numbers (GTINs), Manufacturer Part Numbers (MPNs), and brand names. Not all products have unique product identifiers. However, if your product does have one, especially a GTIN, providing it can help make your ads richer and easier for users to find. If your product doesn’t have a UPI, module will inform about it in feed'),
                'name' => 'export_gtin',
                'values' => array(
                    array(
                        'id' => 'upc',
                        'value' => 'upc',
                        'label' => $this->l('Product UPC barcode')
                    ),
                    array(
                        'id' => 'ean13',
                        'value' => 'ean13',
                        'label' => $this->l('Product ean13 / JAN barcode')
                    ),
                    array(
                        'id' => 'reference',
                        'value' => 'reference',
                        'label' => $this->l('Product reference number')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Include shipping price'),
                'desc' => $this->l('Select if you want to include shipping price to feed and how you want to define the price of shipping'),
                'name' => 'export_shipping_info',
                'class' => 't',
                'options' => array(
                    'query' => $export_shipping_info,
                    'id' => 'id_option',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Include product\'s additional shipping cost'),
                'desc' => $this->l('Each product can have own unique value of additional shipping fee. If you will activate this option module will include it to delivery price'),
                'name' => 'export_additional_sc',
                'class' => 't',
                'options' => array(
                    'query' => $yesno,
                    'id' => 'id_option',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Manual shipping value'),
                'desc' => $this->l('Set the value of shipping cost that will be included to each product'),
                'name' => 'export_shipping_info_price',
                'class' => 't',
                'prefix' => '<div id="shipping_currency"></div>'
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Automatic shipping price zone select'),
                'desc' => $this->l('If you selected option to include the shipping price automatically, select the zone. Module will calculate shipping price for selected zone. Please note that automatic price calculation requires more hosting resources than flat value of delivery for each product.'),
                'name' => 'export_shipping_id_zone',
                'class' => 't',
                'options' => array(
                    'query' => Zone::getZones(false, false),
                    'id' => 'id_zone',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Include weight'),
                'desc' => $this->l('Include weight of product to feed'),
                'name' => 'export_product_weight',
                'class' => 't',
                'options' => array(
                    'query' => $yesno,
                    'id' => 'id_option',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product type'),
                'desc' => $this->l('Use the product_type attribute to include your own product categorization system in your product data'),
                'name' => 'export_product_type',
                'class' => 't',
                'options' => array(
                    'query' => $yesno,
                    'id' => 'id_option',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Short description'),
                'desc' => $this->l('Select what description will included to "short description" field in generated catalog file / feed'),
                'name' => 'export_short_description_what',
                'values' => array(
                    array(
                        'id' => 'short_short',
                        'value' => 'short',
                        'label' => $this->l('Short description')
                    ),
                    array(
                        'id' => 'short_desc',
                        'value' => 'desc',
                        'label' => $this->l('Long description')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Description'),
                'desc' => $this->l('Select what description will included to "description" field in generated catalog file / feed'),
                'name' => 'export_description_what',
                'values' => array(
                    array(
                        'id' => 'desc_desc',
                        'value' => 'desc',
                        'label' => $this->l('Long description')
                    ),
                    array(
                        'id' => 'desc_short',
                        'value' => 'short',
                        'label' => $this->l('Short description')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Remove html from descriptions'),
                'desc' => $this->l('Module can export formatted descriptions and also pure description text (without html tags)'),
                'name' => 'export_removehtml',
                'values' => array(
                    array(
                        'id' => 'removehtml_off',
                        'value' => 0,
                        'label' => $this->l('No')
                    ),
                    array(
                        'id' => 'removehtml_on',
                        'value' => 1,
                        'label' => $this->l('Yes')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Language'),
                'desc' => $this->l('Choose a language you wish to export'),
                'name' => 'export_language',
                'class' => 't',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Delimiter'),
                'name' => 'export_delimiter',
                'value' => ';',
                'desc' => $this->l('The character to separate the fields in CSV file') . '. ' . $this->l('Usually pipe "|" - Google merchant center allows to use it as a column delimiter')
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Products\'s availability'),
                'name' => 'export_active',
                'values' => array(
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Export all products.')
                    ),
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Export only active products')
                    ),
 array(
                        'id' => 'active_on',
                        'value' => 2,
                        'label' => $this->l('Export select products')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Products\'s stock'),
                'name' => 'export_instock',
                'values' => array(
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Export all products.')
                    ),
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Export only in-stock products')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('Information about stock'),
                'name' => 'export_instock_info',
                'values' => array(
                    array(
                        'id' => 'active0',
                        'value' => 0,
                        'label' => $this->l('include real stock information')
                    ),
                    array(
                        'id' => 'active1',
                        'value' => 1,
                        'label' => $this->l('insert "in-stock" info for all products even if some of them are out of stock')
                    ),
                    array(
                        'id' => 'active2',
                        'value' => 2,
                        'label' => $this->l('insert "out-of-stock" info for all products even if some of them are in stock')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product Manufacturer'),
                'desc' => $this->l('Choose a manufacturer you wish to export'),
                'name' => 'export_manufacturers',
                'class' => 't',
                'options' => array(
                    'query' => $manufacturers,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Default manufacturer value'),
                'desc' => $this->l('If product will not be associated with any manufacturer - this will be the value of manufacturer field for this product in exported catalog file / feed'),
                'name' => 'export_manufacturers_default',
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product Supplier'),
                'desc' => $this->l('Choose a supplier you wish to export'),
                'name' => 'export_suppliers',
                'class' => 't',
                'options' => array(
                    'query' => $suppliers,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Product Category'),
                'desc' => $this->l('Choose a product category you wish to export'),
                'name' => 'export_category',
                'class' => 't',
                'options' => array(
                    'query' => $categories,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Image size for product pictures'),
                'name' => 'export_img',
                'options' => array(
                    'query' => $options_images,
                    'id' => 'name',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'radio',
                'label' => $this->l('How many pictures you want to export?'),
                'name' => 'export_what_pictures',
                'values' => array(
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Export cover only')
                    ),
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Export all pictures')
                    ),
                ),
                'is_bool' => true,
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Currency'),
                'desc' => $this->l('Choose a currency you wish to export'),
                'name' => 'export_currency',
                'class' => 't',
                'options' => array(
                    'query' => Currency::getCurrencies(false, false),
                    'id' => 'id_currency',
                    'name' => 'name'
                ),
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Price tax included or excluded'),
                'desc' => $this->l('Choose if you want to export the price with or without tax.'),
                'name' => 'export_tax',
                'class' => 't export_tax',
                'options' => array(
                    'query' => $pricetax,
                    'id' => 'id_option',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'html',
                'label' => $this->l('Exclude products'),
                'name' => 'export_exclude',
                'html_content' => $this->renderExcludeForm(),
            ),
            array(
                'type' => 'html',
                'label' => $this->l('Google Categories'),
                'name' => 'export_google_categories',
                'html_content' => $this->renderGoogleCategories(),
            ),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Export Options'),
                    'icon' => 'icon-cogs'
                ),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Export'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;

        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitExport';
        $helper->currentIndex = self::$currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminExportProductsFeedGoogle');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $this->heading() . $helper->generateForm(array($fields_form));
    }

    public function loadScript()
    {
        $this->context->smarty->assign('link', $this->context->link);
        $this->context->smarty->assign('feed_updated', $this->l('URL of product\'s feed updated'));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'gmfeed/views/script.tpl');
    }

    public function heading()
    {
        $shop = new ShopUrl($this->context->shop->id);
        return "
        <div class='alert alert-info'>
        " . $this->l('This form generates a .csv or .xml file that is ready to import for Google Merchant Center "products" purposes.') . "<br/>
        " . $this->l('Define what kind of products\' feed you want to create and press Export button') . "<br/>
        " . $this->l('Module will generate a .csv / .xml file and your browser will download it immediately') . "
        </div>
        
        <div class='alert alert-info'>
            " . $this->l('Optionally you can use an URL - feed of products (feed uses filters defined below in export options form)') . "<br/><br/>
            <button class=\"btn btn-default show-links clearfix\" style=\"margin-bottom:20px;\"><i class=\"process-icon-ok\"></i>" . $this->l('Show links') . "</button>        
            <div class='hide panel clearfix'>
                <h3>" . $this->l('Secured URL') . "</h3>
                <div><span> " . $shop->getUrl(true) . "modules/gmfeed/feed.php?</span><span class='feedurl' style='word-wrap: break-word;'></span></div><br/><hr/><br/>
                <h3>" . $this->l('Non-secured URL') . "</h3>
                <div><span> " . $shop->getUrl(false) . "modules/gmfeed/feed.php?</span><span class='feedurl' style='word-wrap: break-word;'></span></div>    
            </div>
        </div>
        <div class='panel'>
            <h3>" . $this->l('Configure form from URL') . "</h3>
            <div class='alert alert-info'>
            " . $this->l('This form allows to automatically configure previously selected options based on url of feed. Just paste the feed here and module will automatically select all fields in configuration form') . "
            </div>
            <input type='text' class='deserializeUrl'/>
        </div>
        " . $this->loadScript();
    }

    public function renderExcludeForm()
    {
        $this->context->smarty->assign(array(
            'msgTitle' => $this->l('Product added to the list'),
            'msgContents' => $this->l('Product added to the list of products'),
            'version' => _PS_VERSION_,
        ));

        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'gmfeed/views/exclude-products.tpl');
    }

    public function renderGoogleCategories()
    {
        $taxonomy_files = array();
        foreach (Language::getLanguages(true) AS $language)
        {
            if (file_exists('../modules/gmfeed/google/' . $language['language_code'] . '.txt'))
            {
                $taxonomy_files[$language['language_code']] = 1;
            }
            else
            {
                $taxonomy_files[$language['language_code']] = 0;
            }
        }

        $this->context->smarty->assign('taxonomy_files', $taxonomy_files);
        $this->context->smarty->assign('taxonomy_files_custom', $this->taxonomyFiles);
        $this->context->smarty->assign('link', $this->context->link);
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'gmfeed/views/google_lang_links.tpl');
    }

    public function getConfigFieldsValues()
    {
        return array(
            'export_additional_sc' => false,
            'export_active' => false,
            'export_instock' => false,
            'export_format' => 'csv',
            'export_gtin' => 'upc',
            'export_identification' => 'id_product',
            'export_category' => 'all',
            'export_manufacturers_default' => 'Default',
            'export_type' => 'product',
            'export_img' => 1,
            'export_file_format' => 'csv',
            'export_manufacturers' => 'all',
            'export_description_what' => 'desc',
            'export_short_description_what' => 'short',
            'export_suppliers' => 'all',
            'export_delimiter' => '|',
            'delete_images' => '0',
            'export_currency' => (int)Configuration::get('PS_CURRENCY_DEFAULT'),
            'export_instock_info' => 0,
            'export_what_pictures' => 0,
            'export_removehtml' => '0',
            'export_language' => (int)Configuration::get('PS_LANG_DEFAULT'),
            'export_tax' => 'price_tin',
            'export_product_type' => 0,
            'export_exclude' => '',
            'export_product_weight' => 0
        );
    }

    public function setMedia($isNewTheme = false)
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addJqueryPlugin('autocomplete');
        parent::setMedia($isNewTheme = false);
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitExport'))
        {

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



            if (Tools::getValue('export_what_pictures') == 0)
            {
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
			if ($p->custom_field != 'googlefeed') {break;}
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
                                    $line[$field] = (Tools::getValue('export_instock_info') == 0 ? ($p->quantity > 0 ? 'in stock' : 'out of stock'):(Tools::getValue('export_instock_info') == 1 ? 'in stock':(Tools::getValue('export_instock_info') == 2 ? 'out of stock':'in stock')));
                                    break;
                                case 'condition':
                                    $line[$field] = $p->condition;
                                    break;
                                case 'include_url':
                                    $line['include_url'] = Context::getContext()->link->getProductLink($p->id, null, null, null, $id_lang, $this->context->shop->id);
                                    break;
                                case 'image_url':
                                    $line['image_url'] = '';
                                    $imagelinks = array();
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
                                        $line['image_url'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
                                    }
                                    if ($line['image_url'] == ''){
                                        $line['image_url'] = $this->context->shop->getBaseUrl(true, true) . 'img/p/' . $this->context->language->iso_code . '-default-' . Tools::getValue('export_img') . '.jpg';
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
                                foreach ($line as $lkey => $litem) {
                                    $lkey = $this->changeKeyToGoogleFeed($lkey);
                                    $new_line[$lkey] = $litem;
                                }
                                fputcsv($f, $line, $delimiter, '"');
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
                                            $line[$field] = (Tools::getValue('export_instock_info') == 0 ? ($value['quantity'] > 0 ? 'in stock' : 'out of stock'):(Tools::getValue('export_instock_info') == 1 ? 'in stock':(Tools::getValue('export_instock_info') == 2 ? 'out of stock':'in stock')));
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
                                    fputcsv($f, $line, $delimiter, '"');
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
    }

    public function initContent()
    {
        if (Tools::getValue('ajax', 'false') == 'false')
        {
            $this->content = $this->renderView() . $this->renderScript();
            parent::initContent();
        }
        else
        {
            $this->ajaxProcess();
        }
    }

    public function getWarehouses($id_warehouses)
    {
        return $id_warehouses['id_warehouse'];
    }

    public function renderScript()
    {

    }

    public function getCategories($id_lang, $active, $id_shop)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `' . _DB_PREFIX_ . 'category` c
			LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON c.`id_category` = cl.`id_category`
			WHERE ' . ($id_shop ? 'cl.`id_shop` = ' . (int)$id_shop : '') . ' ' . ($id_lang ? 'AND `id_lang` = ' . (int)$id_lang : '') . '
			' . ($active ? 'AND `active` = 1' : '') . '
			' . (!$id_lang ? 'GROUP BY c.id_category' : '') . '
			ORDER BY c.`level_depth` ASC, c.`position` ASC');

        return $result;
    }

    /**
     * @param $array
     * @param $xml
     */
    public function array_to_xml($array, &$xml)
    {
        $export_type = Tools::getValue('export_type', 'object');
        foreach($array as $key => $value)
        {
            $key = str_replace('sale_price_tin', 'sale_price', $key);
            $key = str_replace('sale_price_tex', 'sale_price', $key);
            $key = str_replace('price_tex', 'price', $key);
            $key = str_replace('price_tin', 'price', $key);
            $key = str_replace('name', 'title', $key);
            $key = str_replace('quantity', 'availability', $key);
            $key = str_replace('include_url', 'link', $key);
            $key = str_replace('description_short', 'description', $key);
            $key = str_replace('image_url', 'image_link', $key);

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
