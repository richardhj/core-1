<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2014 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace Isotope\EventListener;

use Isotope\Interfaces\IsotopeOrderableCollection;
use Isotope\Model\Product\Coupon;
use Isotope\Model\ProductType;

class PostCheckoutCoupons
{

    public function onPostCheckout(IsotopeOrderableCollection $order)
    {
        foreach ($order->getItems() as $item) {
            if (!$item->hasProduct()) {
                continue;
            }

            $product = $item->getProduct();

            if ($product instanceof Coupon) {
                $time = time();
                $type = $product->getType();

                try {
                    \Database::getInstance()->lockTables(array('tl_iso_coupon' => 'WRITE'));

                    for ($i = 0; $i < $item->quantity; $i++) {
                        \Database::getInstance()
                             ->prepare(
                                 "INSERT INTO tl_iso_coupon (tstamp, dateAdded, product_collection_item, document_number, product_name, owner, code, status) VALUES (?,?,?,?,?,?,?,?)"
                             )
                             ->execute(
                                 $time,
                                 $time,
                                 $item->id,
                                 $order->getDocumentNumber(),
                                 $item->name,
                                 '',
                                 $this->generateCode($type),
                                 'draft'
                             )
                        ;;
                    }

                    \Database::getInstance()->unlockTables();
                } catch (\Exception $e) {
                    // Make sure tables are always unlocked
                    \Database::getInstance()->unlockTables();

                    throw $e;
                }
            }
        }
    }

    private function generateCode(ProductType $type)
    {
        $code = $this->getPrefixedNumber($type->coupon_prefix, $type->coupon_numbers) . '-';

        $code .= Coupon::generateRandomCode(
            str_split($type->coupon_alphabet),
            $type->coupon_chars
        );

        return $code;
    }

    private function getPrefixedNumber($prefix, $length)
    {
        $prefix = \Controller::replaceInsertTags($prefix, false);
        $skip   = utf8_strlen($prefix) + 2;

        if ('' !== $prefix) {
            $query = "SELECT code FROM tl_iso_coupon WHERE code LIKE ? ORDER BY CAST(SUBSTRING(code, $skip) AS UNSIGNED) DESC";
        } else {
            $query = "SELECT code FROM tl_iso_coupon ORDER BY CAST(code AS UNSIGNED) DESC";
        }

        $highest = \Database::getInstance()->prepare($query)->limit(1)->execute($prefix . '%')->code;
        $highest = (int) substr($highest, $skip - 1);

        return $prefix . ($prefix ? '-' : '') . str_pad($highest + 1, $length, '0', STR_PAD_LEFT);
    }
}
