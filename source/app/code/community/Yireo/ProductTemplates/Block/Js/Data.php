<?php
/**
 * Yireo ProductTemplates for Magento
 *
 * @package     Yireo_ProductTemplates
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

/**
 * Class Yireo_ProductTemplates_Block_Js_Data
 */
class Yireo_ProductTemplates_Block_Js_Data extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        $this->setTemplate('producttemplates/js/data.phtml');

        parent::__construct();
    }

    public function getDefaultProductValues()
    {
        $product = Mage::registry('current_product');

        /** @var Yireo_ProductTemplates_Helper_Data $helper */
        $helper = Mage::helper('producttemplates');
        $defaultValues = $helper->getDefaultValues($product);

        return $defaultValues;
    }
}