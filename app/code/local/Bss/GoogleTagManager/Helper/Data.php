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
class Bss_GoogleTagManager_Helper_Data extends Mage_Core_Helper_Abstract {
	const MODULE_CONFIG_ENABLE_PATH = 'bss_googletagmanager/general/enabled';
	const ACCOUNT_NUMBER_PATH = 'bss_googletagmanager/general/account_number';
	const TRANSACTION_CONFIG_ENABLE_PATH = 'bss_googletagmanager/general/enabled_transaction';
	const TRANSACTION_ID_PATH = 'bss_googletagmanager/general/transaction_id';

	public function isEnabled() {
		return Mage::getStoreConfigFlag(self::MODULE_CONFIG_ENABLE_PATH);
	}

	public function getAccountNumber() {
		return Mage::getStoreConfig(self::ACCOUNT_NUMBER_PATH);
	}

	public function isTransectionEnabled() {
		return Mage::getStoreConfigFlag(self::TRANSACTION_CONFIG_ENABLE_PATH);
	}

	public function getTransactionId() {
		return Mage::getStoreConfig(self::TRANSACTION_ID_PATH);
	}
}