<?php
/**
 * Yireo ProductTemplates
 *
 * @author Yireo
 * @package ProductTemplates
 * @copyright Copyright 2016
 * @license Open Software License
 * @link https://www.yireo.com
 */

/**
 * ProductTemplates observer to various Magento events
 */
class Yireo_ProductTemplates_Model_Observer
{
    /**
     * Method fired on the event <catalog_product_new>
     *
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Model_Observer
     * @deprecated
     */
    public function catalogProductNewAction($observer) 
    {
        return $this;
    }

    /**
     * Method fired on the event <catalog_product_save_before>
     *
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Model_Observer
     * @deprecated
     */
    public function catalogProductSaveBefore($observer) 
    {
        return $this;
    }
}
