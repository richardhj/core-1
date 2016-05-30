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

namespace Isotope\Backend\Coupon;

use Haste\Http\Response\HtmlResponse;

class Callback extends \Backend
{
    const CODE_FIELD = 3;

    public function generateLabel($row, $label, \DataContainer $dc, $args)
    {
        if ($args[self::CODE_FIELD] == '') {
            $args[self::CODE_FIELD] = sprintf('<span style="color:#ccc">%s</span>', $GLOBALS['TL_LANG']['tl_iso_coupon']['codeUnavailable']);
        } else {
            switch ($row['status']) {
                case 'draft':
                    $color = '#ccc';
                    break;

                case 'available':
                    $color = 'green';
                    break;

                case 'redeemed':
                default:
                    $color = 'inherit';
                    break;

                case 'cancelled':
                    $color = 'red';
                    break;
            }

            $args[self::CODE_FIELD] = sprintf('<span style="color:%s">%s</span>', $color, $row['code']);
        }

        return $args;
    }

    /**
     * Return the "check" operation
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function checkIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if ('redeemed' !== $row['status'] && 'available' !== $row['status']) {
            return '';
        }

        if ('redeemed' === $row['status']) {
            $icon       = 'system/modules/isotope_coupons/assets/uncheck.png';
            $label      = $GLOBALS['TL_LANG']['tl_iso_coupon']['uncheck'][0];
            $title      = sprintf($GLOBALS['TL_LANG']['tl_iso_coupon']['uncheck'][1], $row['id']);
            $attributes = 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_iso_coupon']['uncheckConfirm'] . '\'))return false;Backend.getScrollOffset()"';
        }

        return '<a href="'.\Backend::addToUrl($href . '&amp;id=' . $row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
    }

    /**
     * Update code status and redirect back to list.
     *
     * @param $dc
     */
    public function toggleStatus($dc)
    {
        $coupon = \Database::getInstance()->prepare("SELECT * FROM tl_iso_coupon WHERE id=?")->execute($dc->id);
        
        switch ($coupon->status) {
            case 'available':
                \Database::getInstance()
                    ->prepare("UPDATE tl_iso_coupon SET status=? WHERE id=?")
                    ->execute('redeemed', $coupon->id)
                ;
                break;

            case 'redeemed':
                \Database::getInstance()
                     ->prepare("UPDATE tl_iso_coupon SET status=? WHERE id=?")
                     ->execute('available', $coupon->id)
                ;
                break;

            case 'draft':
            case 'cancelled':
            default:
                $response = new HtmlResponse('', 400);
                $response->send();
        }

        \Controller::redirect(\System::getReferer());
    }
}
