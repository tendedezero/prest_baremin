<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class AngarSlide extends ObjectModel
{
    public $title;
    public $description;
    public $url;
    public $legend;
    public $image;
    public $active;
    public $position;
    public $id_shop;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'angarslider_slides',
        'primary' => 'id_angarslider_slides',
        'multilang' => true,
        'fields' => array(
            'active' =>            array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'position' =>        array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),

            // Lang fields
            'description' =>    array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 4000),
            'title' =>            array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
            'legend' =>            array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
            'url' =>            array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
            'image' =>            array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255),
        )
    );

    public function __construct($id_slide = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id_slide, $id_lang, $id_shop);
    }

    public function add($autodate = true, $null_values = false)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $res = parent::add($autodate, $null_values);
        $res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'angarslider` (`id_shop`, `id_angarslider_slides`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')');
        return $res;
    }

    public function delete()
    {
        $res = true;

        $images = $this->image;
        foreach ($images as $image) {
            if (preg_match('/sample/', $image) === 0) {
                if ($image && file_exists(dirname(__FILE__).'/images/'.$image)) {
                    $res &= @unlink(dirname(__FILE__).'/images/'.$image);
                }
            }
        }

        $res &= $this->reOrderPositions();

        $res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'angarslider`
			WHERE `id_angarslider_slides` = '.(int)$this->id);

        $res &= parent::delete();
        return $res;
    }

    public function reOrderPositions()
    {
        $id_slide = $this->id;
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(hss.`position`) as position
			FROM `'._DB_PREFIX_.'angarslider_slides` hss, `'._DB_PREFIX_.'angarslider` hs
			WHERE hss.`id_angarslider_slides` = hs.`id_angarslider_slides` AND hs.`id_shop` = '.(int)$id_shop);

        if ((int)$max == (int)$id_slide) {
            return true;
        }

        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hss.`position` as position, hss.`id_angarslider_slides` as id_slide
			FROM `'._DB_PREFIX_.'angarslider_slides` hss
			LEFT JOIN `'._DB_PREFIX_.'angarslider` hs ON (hss.`id_angarslider_slides` = hs.`id_angarslider_slides`)
			WHERE hs.`id_shop` = '.(int)$id_shop.' AND hss.`position` > '.(int)$this->position);

        foreach ($rows as $row) {
            $current_slide = new AngarSlide($row['id_slide']);
            --$current_slide->position;
            $current_slide->update();
            unset($current_slide);
        }

        return true;
    }

    public static function getAssociatedIdsShop($id_slide)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_shop`
			FROM `'._DB_PREFIX_.'angarslider` hs
			WHERE hs.`id_angarslider_slides` = '.(int)$id_slide);

        if (!is_array($result)) {
            return false;
        }

        $return = array();

        foreach ($result as $id_shop) {
            $return[] = (int)$id_shop['id_shop'];
        }

        return $return;
    }
}
