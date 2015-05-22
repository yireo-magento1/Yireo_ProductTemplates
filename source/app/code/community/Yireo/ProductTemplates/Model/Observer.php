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
    /*
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
        $productType = $product->getTypeId();
        $defaultValues = Mage::helper('producttemplates')->getDefaultValues($productType);

        // Insert the default values
        foreach($defaultValues as $name => $value) {
            $product->setData($name, $value);
        }

        // @todo: Increment SKU
 
        /*
        $stockItem = Mage::getModel('cataloginventory/stock_item');
        $stockItem->assignProduct($product);
        $stockItem->setData('is_in_stock', 1);
        $stockItem->setData('qty', 1);
        $product->setStockItem($stockItem);
        */
    }

    /*
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
}
