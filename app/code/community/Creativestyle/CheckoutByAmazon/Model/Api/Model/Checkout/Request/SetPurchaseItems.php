<?php

/**
 * Amazon Checkout API: SetPurchaseItems request model
 *
 * Fields:
 * <ul>
 * <li>PurchaseContractId: string</li>
 * <li>PurchaseItems: Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_ItemList</li>
 * </ul>
 *
 * This file is part of The Official Amazon Payments Magento Extension
 * (c) creativestyle GmbH <amazon@creativestyle.de>
 * All rights reserved
 *
 * Reuse or modification of this source code is not allowed
 * without written permission from creativestyle GmbH
 *
 * @category   Creativestyle
 * @package    Creativestyle_CheckoutByAmazon
 * @copyright  Copyright (c) 2011 - 2013 creativestyle GmbH (http://www.creativestyle.de)
 * @author     Marek Zabrowarny / creativestyle GmbH <amazon@creativestyle.de>
 */
class Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Request_SetPurchaseItems extends Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_Abstract {

    public function __construct($data = null) {
        $this->_fields = array(
            'PurchaseContractId' => array('FieldValue' => null, 'FieldType' => 'string'),
            'PurchaseItems' => array('FieldValue' => null, 'FieldType' => 'Creativestyle_CheckoutByAmazon_Model_Api_Model_Checkout_ItemList')
        );
        parent::__construct($data);
    }

    public function convertToQueryString() {
        $params = array();
        $params['Action'] = 'SetPurchaseItems';
        if ($this->issetPurchaseContractId()) {
            $params['PurchaseContractId'] = $this->getPurchaseContractId();
        }
        if ($this->issetPurchaseItems()) {
            $purchaseItemssetPurchaseItemsRequest = $this->getPurchaseItems();
            $purchaseItempurchaseItemsIndex = 1;
            foreach ($purchaseItemssetPurchaseItemsRequest->getPurchaseItem() as $purchaseItempurchaseItemsIndex1 => $purchaseItempurchaseItems) {
                if ($purchaseItempurchaseItems->issetMerchantItemId()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.MerchantItemId'] = $purchaseItempurchaseItems->getMerchantItemId();
                }
                if ($purchaseItempurchaseItems->issetSKU()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.SKU'] = $purchaseItempurchaseItems->getSKU();
                }
                if ($purchaseItempurchaseItems->issetMerchantId()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.MerchantId'] = $purchaseItempurchaseItems->getMerchantId();
                }
                if ($purchaseItempurchaseItems->issetTitle()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.Title'] = $purchaseItempurchaseItems->getTitle();
                }
                if ($purchaseItempurchaseItems->issetDescription()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.Description'] = $purchaseItempurchaseItems->getDescription();
                }
                if ($purchaseItempurchaseItems->issetUnitPrice()) {
                    $UnitPricepurchaseItem = $purchaseItempurchaseItems->getUnitPrice();
                    if ($UnitPricepurchaseItem->issetAmount()) {
                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.UnitPrice.Amount'] = $UnitPricepurchaseItem->getAmount();
                    }
                    if ($UnitPricepurchaseItem->issetCurrencyCode()) {
                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.UnitPrice.CurrencyCode'] = $UnitPricepurchaseItem->getCurrencyCode();
                    }
                }
                if ($purchaseItempurchaseItems->issetQuantity()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.Quantity'] = $purchaseItempurchaseItems->getQuantity();
                }
                if ($purchaseItempurchaseItems->issetURL()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.URL'] = $purchaseItempurchaseItems->getURL();
                }
                if ($purchaseItempurchaseItems->issetCategory()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.Category'] = $purchaseItempurchaseItems->getCategory();
                }
                if ($purchaseItempurchaseItems->issetFulfillmentNetwork()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.FulfillmentNetwork'] = $purchaseItempurchaseItems->getFulfillmentNetwork();
                }
                if ($purchaseItempurchaseItems->issetItemCustomData()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.ItemCustomData'] = $purchaseItempurchaseItems->getItemCustomData();
                }
                if ($purchaseItempurchaseItems->issetProductType()) {
                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.ProductType'] = $purchaseItempurchaseItems->getProductType();
                }
                if ($purchaseItempurchaseItems->issetPhysicalProductAttributes()) {
                    $physicalProductAttributespurchaseItem = $purchaseItempurchaseItems->getPhysicalProductAttributes();
                    if ($physicalProductAttributespurchaseItem->issetWeight()) {
                        $weightphysicalProductAttributes = $physicalProductAttributespurchaseItem->getWeight();
                        if ($weightphysicalProductAttributes->issetValue()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.Weight.Value'] = $weightphysicalProductAttributes->getValue();
                        }
                        if ($weightphysicalProductAttributes->issetUnit()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.Weight.Unit'] = $weightphysicalProductAttributes->getUnit();
                        }
                    }
                    if ($physicalProductAttributespurchaseItem->issetCondition()) {
                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.Condition'] = $physicalProductAttributespurchaseItem->getCondition();
                    }
                    if ($physicalProductAttributespurchaseItem->issetDeliveryMethod()) {
                        $deliveryMethodphysicalProductAttributes = $physicalProductAttributespurchaseItem->getDeliveryMethod();
                        if ($deliveryMethodphysicalProductAttributes->issetServiceLevel()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.DeliveryMethod.ServiceLevel'] = $deliveryMethodphysicalProductAttributes->getServiceLevel();
                        }
                        if ($deliveryMethodphysicalProductAttributes->issetDisplayableShippingLabel()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.DeliveryMethod.DisplayableShippingLabel'] = $deliveryMethodphysicalProductAttributes->getDisplayableShippingLabel();
                        }
                        if ($deliveryMethodphysicalProductAttributes->issetDestinationName()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.DeliveryMethod.DestinationName'] = $deliveryMethodphysicalProductAttributes->getDestinationName();
                        }
                        if ($deliveryMethodphysicalProductAttributes->issetShippingCustomData()) {
                            $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.DeliveryMethod.ShippingCustomData'] = $deliveryMethodphysicalProductAttributes->getShippingCustomData();
                        }
                    }
                    if ($physicalProductAttributespurchaseItem->issetItemCharges()) {
                        $itemChargesPhysicalProductAttributes = $physicalProductAttributespurchaseItem->getItemCharges();
                        if ($itemChargesPhysicalProductAttributes->issetTax()) {
                            $taxItemCharges = $itemChargesPhysicalProductAttributes->getTax();
                            if ($taxItemCharges->issetAmount()) {
                                $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Tax.Amount'] = $taxItemCharges->getAmount();
                            }
                            if ($taxItemCharges->issetCurrencyCode()) {
                                $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Tax.CurrencyCode'] = $taxItemCharges->getCurrencyCode();
                            }
                        }
                        if ($itemChargesPhysicalProductAttributes->issetShipping()) {
                            $shippingItemCharges = $itemChargesPhysicalProductAttributes->getShipping();
                            if ($shippingItemCharges->issetAmount()) {
                                $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Shipping.Amount'] = $shippingItemCharges->getAmount();
                            }
                            if ($shippingItemCharges->issetCurrencyCode()) {
                                $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Shipping.CurrencyCode'] = $shippingItemCharges->getCurrencyCode();
                            }
                        }
                        if ($itemChargesPhysicalProductAttributes->issetPromotions()) {
                            $promotionsItemCharges = $itemChargesPhysicalProductAttributes->getPromotions();
                            foreach ($promotionsItemCharges->getPromotion() as $promotionPromotionsIndex1 => $promotionPromotions) {
                                $promotionPromotionsIndex = $promotionPromotionsIndex1 + 1;
                                if ($promotionPromotions->issetPromotionId()) {
                                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Promotions.Promotion.' . ($promotionPromotionsIndex) . '.PromotionId'] = $promotionPromotions->getPromotionId();
                                }
                                if ($promotionPromotions->issetDescription()) {
                                    $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Promotions.Promotion.' . ($promotionPromotionsIndex) . '.Description'] = $promotionPromotions->getDescription();
                                }
                                if ($promotionPromotions->issetDiscount()) {
                                    $discountPromotion = $promotionPromotions->getDiscount();
                                    if ($discountPromotion->issetAmount()) {
                                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Promotions.Promotion.' . ($promotionPromotionsIndex) . '.Discount.Amount'] = $discountPromotion->getAmount();
                                    }
                                    if ($discountPromotion->issetCurrencyCode()) {
                                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.PhysicalProductAttributes.ItemCharges.Promotions.Promotion.' . ($promotionPromotionsIndex) . '.Discount.CurrencyCode'] = $discountPromotion->getCurrencyCode();
                                    }
                                }
                            }
                        }
                    }
                }
                if ($purchaseItempurchaseItems->issetDigitalProductAttributes()) {
                    $digitalProductAttributespurchaseItem = $purchaseItempurchaseItems->getDigitalProductAttributes();
                    if ($digitalProductAttributespurchaseItem->issetdummyDigitalProperty()) {
                        $params['PurchaseItems.PurchaseItem.' . ($purchaseItempurchaseItemsIndex) . '.DigitalProductAttributes.dummyDigitalProperty'] = $digitalProductAttributespurchaseItem->getdummyDigitalProperty();
                    }
                }
                $purchaseItempurchaseItemsIndex++;
            }
        }
        return $params;
    }

}
