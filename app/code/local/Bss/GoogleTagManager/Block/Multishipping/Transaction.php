<?php
/**
* BSS Commerce Co.
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://bsscommerce.com/Bss-Commerce-License.txt
*
* =================================================================
*                 MAGENTO EDITION USAGE NOTICE
* =================================================================
* This package designed for Magento COMMUNITY edition
* BSS Commerce does not guarantee correct work of this extension
* on any other Magento edition except Magento COMMUNITY edition.
* BSS Commerce does not provide extension support in case of
* incorrect edition usage.
* =================================================================
*
* @category   BSS
* @package    Bss_GoogleTagManager
* @author     Extension Team
* @copyright  Copyright (c) 2014-2016 BSS Commerce Co. ( http://bsscommerce.com )
* @license    http://bsscommerce.com/Bss-Commerce-License.txt
*/
class Bss_GoogleTagManager_Block_Multishipping_Transaction extends Mage_Core_Block_Template {
	protected $_orders;

	protected function _construct() {
        $this->_prepareOrder();
        parent::_construct();
    }

	public function isEnabled() {
        return (Mage::helper('googletagmanager')->isTransectionEnabled() && Mage::helper('googletagmanager')->isEnabled());
    }

    protected function _prepareOrder() {
    	$ids = Mage::getSingleton('core/session')->getOrderIds(true);

        if ($ids && is_array($ids)) {
        	$orders = array();
        	foreach($ids as $orderId => $value){
			    $orders[] = Mage::getModel('sales/order')->load($orderId);
			}

			$this->_orders = $orders;
        }
    }

    public function getOrders() {
    	return $this->_orders;
    }

    public function getDataLayer() {
    	$orders = $this->getOrders();

    	$dataLayer = '[';
    	$ordersLayer = array();

    	foreach ($orders as $order) {
	    	if($order->getId()) {
	    		$ordersLayer[] = "{
				    'transactionId': '". $order->getIncrementId() ."',
				    'transactionAffiliation': '". Mage::app()->getStore()->getName() ."',
				    'transactionTotal': ". $order->getGrandTotal() .",
				    'transactionTax': ". $order->getTaxAmount() .",
				    'transactionShipping': ". $order->getShippingAmount() .",
				    'transactionProducts': [". $this->getProductsLayer($order) ."]
				}";
	    	}
	    }

	    $dataLayer .= implode(', ', $ordersLayer);
    	$dataLayer .= ']';
    	return $dataLayer;
    }

    protected function getProductsLayer($order) {
    	$items = $order->getAllVisibleItems();

		$productsLayer = '';

		$numberItems = count($items);
		$i = 0;
		foreach ($items as $item) {
			$i++;

			if($i != $numberItems) {
				$productsLayer .= "{
			        'sku': '". $item->getSku() ."',
			        'name': '". $item->getName() ."',
			        'price': ". $item->getRowTotal() .",
			        'category': '',
			        'quantity': ". $item->getQtyOrdered() ." 
			    },";
			} else {
				$productsLayer .= "{
			        'sku': '". $item->getSku() ."',
			        'name': '". $item->getName() ."',
			        'price': ". $item->getRowTotal() .",
			        'category': '',
			        'quantity': ". $item->getQtyOrdered() ." 
			    }";
			}
		}

		return $productsLayer;
    }
}