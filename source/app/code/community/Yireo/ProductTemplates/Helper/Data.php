<?php
/**
 * Yireo ProductTemplates for Magento 
 *
 * @package     Yireo_ProductTemplates
 * @author      Yireo (http://www.yireo.com/)
 * @copyright   Copyright (c) 2014 Yireo (http://www.yireo.com/)
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
     * @param $productType
     *
     * @return bool|string
     */
    public function getTemplateFile($productType)
    {
        $defaultOption = Mage::getStoreConfig('producttemplates/settings/xmlfile');
        $defaultOption = preg_replace('/\.xml$/', '', $defaultOption);
        if (empty($defaultOption)) {
            $defaultOption = 'default';
        }

        $options = array($productType, $defaultOption);
        foreach($options as $option) {
            $templateFile = Mage::getDesign()->getTemplateFilename('producttemplates/'.$option.'.xml');
            if(is_file($templateFile) && is_readable($templateFile)) {
                break;
            }
        }

        if(!is_file($templateFile) || !is_readable($templateFile)) {
            return false;
        }

        return $templateFile;
    }

    /**
     * Get the product definitions
     *
     * @param null $productType
     *
     * @return array|mixed
     */
    public function getDefaultValues($productType = null)
    {
        $templateFile = $this->getTemplateFile($productType);
        if(empty($templateFile) || !file_exists($templateFile)) {
            return array();
        }

        $xmlString = file_get_contents($templateFile);

        try {
            $xml = new SimpleXMLElement($xmlString);
            $data = array();

            foreach($xml->children() as $name => $attribute)
            {
                if (isset($attribute['type'])) {
                    $type = (string) $attribute['type'];
                } else {
                    $type = null;
                }

                if ($type == 'csv') {
                    $attributeValue = (string) $attribute;
                    $data[$name] = explode(',', $attributeValue);

                } elseif ($type == 'array') {
                    $attributeOptions = array();
                    foreach($attribute->children() as $optionName => $optionValue) {
                        $attributeOptions[$optionName] = (string) $optionValue;
                    }
                    $data[$name] = $attributeOptions;

                } elseif ($type == 'int') {
                    $data[$name] = (string) $attribute;

                } else {
                    $data[$name] = (string) $attribute;
                }
            }

            //print_r($data);exit;

        } catch(Exception $e) {
            return array();
        }

        $data = $this->parseDefaultValues($data);

        return $data;
    }

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
