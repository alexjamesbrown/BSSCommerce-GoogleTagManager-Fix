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
class Bss_GoogleTagManager_Block_Transaction extends Mage_Core_Block_Template {
	protected $_order;

	protected function _construct() {
        $this->_prepareOrder();
        parent::_construct();
    }

	public function isEnabled() {
        return (Mage::helper('googletagmanager')->isTransectionEnabled() && Mage::helper('googletagmanager')->isEnabled());
    }

    protected function _prepareOrder() {
    	$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();

    	if($orderId) {
    		$this->_order = Mage::getModel('sales/order')->load($orderId);
    	}
    }

    public function getOrder() {
    	return $this->_order;
    }

    public function getDataLayer() {
    	$order = $this->getOrder();

    	$dataLayer = '';

    	if($order->getId()) {
    		$dataLayer = "[{
			    'transactionId': '". $order->getIncrementId() ."',
			    'transactionAffiliation': '". Mage::app()->getStore()->getName() ."',
			    'transactionTotal': ". $order->getGrandTotal() .",
			    'transactionTax': ". $order->getTaxAmount() .",
			    'transactionShipping': ". $order->getShippingAmount() .",
			    'transactionProducts': [". $this->getProductsLayer() ."]
			}]";
    	}
    	
    	return $dataLayer;
    }

    protected function getProductsLayer() {
    	$order = $this->getOrder();
    	$items = $order->getAllVisibleItems();

		$productsLayer = '';

		$numberItems = count($items);
		$i = 0;
		foreach ($items as $item) {
			$i++;

			if($i != $numberItems) {
				$productsLayer .= "{
			        'sku': '". $item->getSku() ."',
					'name': '" . str_replace("'", '', $item->getName()) . "',
			        'price': ". $item->getRowTotal() .",
			        'category': '',
			        'quantity': ". $item->getQtyOrdered() ." 
			    },";
			} else {
				$productsLayer .= "{
			        'sku': '". $item->getSku() ."',
					'name': '" . str_replace("'", '', $item->getName()) . "',
			        'price': ". $item->getRowTotal() .",
			        'category': '',
			        'quantity': ". $item->getQtyOrdered() ." 
			    }";
			}
		}

		return $productsLayer;
    }
}