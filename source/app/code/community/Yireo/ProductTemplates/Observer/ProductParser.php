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
class Yireo_ProductTemplates_Observer_ProductParser
{
    /**
     * @var Yireo_ProductTemplates_Model_Product_Parser
     */
    protected $productParser;

    /**
     * Yireo_ProductTemplates_Observer_ProductParser constructor.
     */
    public function __construct()
    {
        $this->productParser = Mage::getModel('producttemplates/product_parser');
    }

    /**
     * Method fired on the event <catalog_product_new>
     *
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Observer_ProductParser
     */
    public function catalogProductNewAction($observer)
    {
        // Get the empty product from the event
        $product = $observer->getEvent()->getProduct();

        // Make sure there is no ID
        /** @var Mage_Catalog_Model_Product $product */
        if ($product->getId() > 0) {
            return $this;
        }

        $this->productParser->setProduct($product)->parse();
        $product = $this->productParser->getProduct();
        $observer->getEvent()->setProduct($product);

        return $this;
    }

    /**
     * Method fired on the event <catalog_product_save_before>
     *
     * @param Varien_Event_Observer $observer
     * @return Yireo_ProductTemplates_Observer_ProductParser
     */
    public function catalogProductSaveBefore($observer)
    {
        // Get the empty product from the event
        $product = $observer->getEvent()->getProduct();

        // Make sure there is no ID
        /** @var Mage_Catalog_Model_Product $product */
        if ($product->getId() > 0) {
            return $this;
        }

        $this->productParser->setProduct($product)->parse();
        $product = $this->productParser->getProduct();
        $observer->getEvent()->setProduct($product);

        return $this;
    }
}
