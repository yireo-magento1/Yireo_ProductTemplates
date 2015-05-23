<?php
/**
 * Yireo ProductTemplates
 *
 * @author Yireo
 * @package ProductTemplates
 * @copyright Copyright 2014
 * @license Open Software License
 * @link http://www.yireo.com
 */

/*
 * ProductTemplates observer to various Magento events
 */
class Yireo_ProductTemplates_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Method fired on the event <catalog_product_new>
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Model_Observer
     */
    public function catalogProductNewAction($observer) 
    {
        // Get the empty product from the event
        $product = $observer->getEvent()->getProduct();

        // Load the template values by
        $product->setTypeId('virtual');
        $productType = $product->getTypeId();
        $defaultValues = $this->getHelper()->getDefaultValues($productType);

        // Handle the stock item
        $stockData = null;
        if (isset($defaultValues['stock_item'])) {
            $stockData = $defaultValues['stock_item'];
            $product->setData('manage_stock', 1);
            unset($defaultValues['stock_item']);
        }

        // Insert the default values
        foreach($defaultValues as $name => $value) {
            $product->setData($name, $value);
        }

        if (!empty($stockData)) {
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->assignProduct($product);
            $stockItem->setData($stockData);
            $product->setStockItem($stockItem);
        }
    }

    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Model_Observer
     */
    public function controllerActionPredispatch($observer)
    {
        // Run the feed
        Mage::getModel('producttemplates/feed')->updateIfAllowed();
    }

    /**
     * Return the module helper
     *
     * @return Yireo_ProductTemplates_Helper_Data
     */
    public function getHelper()
    {
        /** @var Yireo_ProductTemplates_Helper_Data */
        return Mage::helper('producttemplates');
    }
}
