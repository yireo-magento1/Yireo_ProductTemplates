<?php
/**
 * Yireo ProductTemplates
 *
 * @author Yireo
 * @package ProductTemplates
 * @copyright Copyright 2016
 * @license Open Source License (OSL v3)
 * @link https://www.yireo.com
 */

/**
 * ProductTemplates observer to load Yireo feed
 */
class Yireo_ProductTemplates_Observer_Feed
{
    /**
     * @var Yireo_ProductTemplates_Model_Feed
     */
    protected $feedModel;

    /**
     * Yireo_ProductTemplates_Observer_Feed constructor.
     */
    public function __construct()
    {
        $this->feedModel = Mage::getModel('producttemplates/feed');
    }

    /**
     * Method fired on the event <controller_action_predispatch>
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Yireo_ProductTemplates_Observer_Feed
     * @event controller_action_predispatch
     */
    public function controllerActionPredispatch($observer)
    {
        // Run the feed
        $this->feedModel->updateIfAllowed();

        return $this;
    }
}
