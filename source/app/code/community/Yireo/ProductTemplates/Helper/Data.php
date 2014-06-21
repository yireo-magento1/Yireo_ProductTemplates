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
    public function getTemplateFile($productType)
    {
        $options = array($productType, 'default', 'example');
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

    public function getDefaultValues($productType = null)
    {
        $templateFile = $this->getTemplateFile($productType);
        if(empty($templateFile) || !file_exists($templateFile)) {
            return array();
        }

        $xmlString = file_get_contents($templateFile);
        try {
            $xml = new SimpleXMLElement($xmlString);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
        } catch(Exception $e) {
            return array();
        }

        return $array;
    }
}
