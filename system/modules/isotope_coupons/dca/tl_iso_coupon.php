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

/**
 * Table tl_iso_coupon
 */
$GLOBALS['TL_DCA']['tl_iso_coupon'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'closed'                      => true,
        'notEditbable'                => true,
        'notCopyable'                 => true,
        'notDeletable'                => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('dateAdded DESC'),
            'flag'                    => 1,
            'panelLayout'             => 'filter;sort,search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('document_number', 'product_name', 'code'),
            'showColumns'             => true,
            'label_callback'          => array('Isotope\Backend\Coupon\Callback', 'generateLabel')
        ),
        'operations' => array
        (
            'check' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_iso_coupon']['check'],
                'href'                => 'key=check',
                'icon'                => 'system/modules/isotope_coupons/assets/check.png',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_iso_coupon']['checkConfirm'] . '\'))return false;Backend.getScrollOffset()"',
                'button_callback'     => array('Isotope\Backend\Coupon\Callback', 'checkIcon'),
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_iso_coupon']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),
        ),
    ),

    // Palettes
    'palettes' => array(),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
        ),
        'dateAdded' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['dateAdded'],
            'sorting'                 => true,
            'filter'                  => true,
            'flag'                    => 6,
            'eval'                    => array('rgxp'=>'datim'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'product_collection_item' => array
        (
            'eval'                    => array('doNotShow' => true),
            'sql'                     => "int(10) NOT NULL",
        ),
        'document_number' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['document_number'],
            'search'                  => true,
            'sql'                     => "varchar(64) NOT NULL",
        ),
        'product_name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['product_name'],
            'search'                  => true,
            'sorting'                 => true,
            'sql'                     => "varchar(255) NOT NULL",
        ),
        'owner' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['owner'],
            'search'                  => true,
            'sql'                     => "varchar(255) NOT NULL",
        ),
        'code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['code'],
            'search'                  => true,
            'sql'                     => "varchar(255) NULL",
        ),
        'status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_iso_coupon']['status'],
            'filter'                  => true,
            'options'                 => ['draft', 'available', 'redeemed', 'cancelled'],
            'reference'               => &$GLOBALS['TL_LANG']['tl_iso_coupon']['status'],
            'sql'                     => "varchar(16) NOT NULL",
        ),
    )
);
