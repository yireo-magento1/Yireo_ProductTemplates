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
class Yireo_ProductTemplates_Model_Product_Parser
{
    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $product;

    /**
     * @var Yireo_ProductTemplates_Helper_Data
     */
    protected $helper;

    /**
     * Yireo_ProductTemplates_Model_Product_Parser constructor.
     */
    public function __construct()
    {
        $this->helper = Mage::helper('producttemplates');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Try to complete the product
     */
    public function parse()
    {
        // Load the template values by product type
        $defaultValues = $this->helper->getDefaultValues($this->product);

        // Handle the stock item
        $stockData = null;
        if (isset($defaultValues['stock_data'])) {
            $stockData = $defaultValues['stock_data'];
            $this->product->setData('manage_stock', 1);
            unset($defaultValues['stock_data']);
        }

        if (!empty($defaultValues['sku'])) {
            $defaultValues['sku'] = $this->parseSku($defaultValues['sku']);
        }

        // Insert the default values
        foreach($defaultValues as $name => $value) {
            $this->product->setData($name, $value);
        }

        if (!empty($stockData)) {
            //$this->product->save();

            /** @var Mage_CatalogInventory_Model_Stock_Item $stockItem */
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->assignProduct($this->product);
            $stockItem->setData($stockData);
            $this->product->setStockItem($stockItem);
        }

        return $this;
    }

    protected function parseSku($sku)
    {
        if(preg_match('/([\-\_]?)([\%0-9]+)/', $sku, $match)) {
            $skuBase = preg_replace('/'.$match[0].'$/', '', $sku);
            $newDelimiter = $match[1];
            $newLength = strlen($match[2]);
            $newSkuIncrement = $match[2];

            $collection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToFilter('sku', array('like' => $skuBase.'%' ))
            ;
            foreach($collection as $product) {
                if(preg_match('/([\-\_]?)([0-9]+)/', $product->getSku(), $match)) {
                    if($match[2] > $newSkuIncrement) {
                        $newSkuIncrement = $match[2];
                    }
                }
            }

            $newSkuIncrement = $newSkuIncrement + 1;
            $newSkuIncrement = str_pad($newSkuIncrement, $newLength, 0, 0);
            $sku = $skuBase.$newDelimiter.$newSkuIncrement;
        }

        return $sku;
    }
}
