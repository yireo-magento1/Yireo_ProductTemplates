<?php
/**
 * Yireo ProductTemplates for Magento
 *
 * @package     Yireo_ProductTemplates
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright (c) 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License
 */

/**
 * ProductTemplates helper
 */
class Yireo_ProductTemplates_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get the template file for product definitions
     *
     * @param Mage_Catalog_Model_Product $product
     *
     * @return array
     */
    public function getTemplateFiles(Mage_Catalog_Model_Product $product)
    {
        $possibleConfigurationFiles = $this->getPossibleConfigurationFiles($product);
        //print_r($possibleConfigurationFiles);exit;

        $templateFiles = [];

        foreach ($possibleConfigurationFiles as $possibleConfigurationFile) {
            foreach (['etc', 'template'] as $folderType) {
                $templateFile = Mage::getDesign()->getFilename('producttemplates/' . $possibleConfigurationFile . '.xml', ['_type' => $folderType]);

                if (!is_file($templateFile)) {
                    continue;
                }

                if (!is_readable($templateFile)) {
                    continue;
                }

                $templateFiles[] = $templateFile;
            }
        }

        return $templateFiles;
    }

    /**
     * @return string
     */
    protected function getConfigurationFilePerSetting()
    {
        return Mage::getStoreConfig('producttemplates/settings/xmlfile');
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return array
     */
    protected function getPossibleConfigurationFiles(Mage_Catalog_Model_Product $product)
    {
        $configurationFiles = [];

        $configurationFiles[] = 'default';

        $option = $this->getConfigurationFilePerSetting();
        if (!empty($option)) {
            $configurationFiles[] = preg_replace('/\.xml$/', '', $option);
        }

        $productType = $product->getTypeId();
        if (!empty($productType)) {
            $configurationFiles[] = $productType;
        }

        $productAttributeSetId = $product->getAttributeSetId();
        if (!empty($productAttributeSetId)) {
            $configurationFiles[] = 'attributeset_' . $productAttributeSetId;
        }

        return $configurationFiles;
    }

    /**
     * @param $file
     *
     * @return string
     */
    protected function getDefaultTemplateLocation($file)
    {
        return Mage::getDesign()->getTemplateFilename('producttemplates/' . $file);
    }

    /**
     * Get the product definitions
     *
     * @param Mage_Catalog_Model_Product $product
     *
     * @return array
     */
    public function getDefaultValues(Mage_Catalog_Model_Product $product)
    {
        $templateFiles = $this->getTemplateFiles($product);

        if (empty($templateFiles)) {
            return array();
        }

        $data = [];

        foreach ($templateFiles as $templateFile) {
            $xmlString = file_get_contents($templateFile);
            $newData = $this->convertXmlToArray($xmlString);
            $data = array_merge($data, $newData);
        }

        $data = $this->parseDefaultValues($data);

        return $data;
    }

    protected function convertXmlToArray($xmlString)
    {
        try {
            $xml = new SimpleXMLElement($xmlString);
            $data = array();

            foreach ($xml->children() as $name => $attribute) {
                if (isset($attribute['type'])) {
                    $type = (string)$attribute['type'];
                } else {
                    $type = null;
                }

                if ($type == 'csv') {
                    $attributeValue = (string)$attribute;
                    $data[$name] = explode(',', $attributeValue);

                } elseif ($type == 'array') {
                    $attributeOptions = array();
                    foreach ($attribute->children() as $optionName => $optionValue) {
                        $attributeOptions[$optionName] = (string)$optionValue;
                    }
                    $data[$name] = $attributeOptions;

                } elseif ($type == 'int') {
                    $data[$name] = (string)$attribute;

                } else {
                    $data[$name] = (string)$attribute;
                }
            }

            return $data;

        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Method to parse default values and unset them if needed
     *
     * @param array $data
     *
     * @return array
     */
    public function parseDefaultValues($data)
    {
        if (isset($data['website_id'])) {
            $data['website_ids'] = array($data['website_id']);
            unset($data['website_id']);
        }

        if (isset($data['category_id'])) {
            $data['category_ids'] = array($data['category_id']);
            unset($data['category_id']);
        }

        return $data;
    }
}
