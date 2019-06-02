<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class DisplayShopLogoByLang extends Module
{
    /**
     * @var array list of hooks used
     */
    public $hooks = [
        'actionFrontControllerSetVariables'
    ];

    /**
     * Configuration key used to store toggle for display logo
     */
    const CONFIGURATION_KEY_SHOP_LOGO = 'DISPLAYSHOPLOGOBYLANG_LOGO';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->name = 'displayshoplogobylang';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Matt75';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.5.0', // Need an newest version with change of hook actionFrontControllerSetVariables
            'max' => '1.7.99.99',
        ];

        parent::__construct();

        $this->displayName = $this->l('Shop Logo by language');
        $this->description = $this->l('Adds a Shop Logo displayed by language');
    }

    /**
     * Install Module.
     *
     * @return bool
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook($this->hooks)
            && Configuration::updateValue(
                static::CONFIGURATION_KEY_SHOP_LOGO,
                array_fill_keys(
                    Language::getIDs(false),
                    'prestashop@2x.png'
                )
            );
    }

    /**
     * Uninstall Module
     *
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName(static::CONFIGURATION_KEY_SHOP_LOGO);
    }

    /**
     * Override template vars to change Shop Logo
     *
     * @param array $params
     */
    public function hookActionFrontControllerSetVariables(array $params)
    {
        $params['templateVars']['shop']['logo'] = $this->getShopLogoUriByLang();
    }

    protected function getShopLogoUriByLang()
    {
        $shopLogoForCurrentLang = Configuration::get(
            DisplayShopLogoByLang::CONFIGURATION_KEY_SHOP_LOGO,
            (int) $this->context->language->id
        );

        return !empty($shopLogoForCurrentLang)
            ? _PS_IMG_ . $shopLogoForCurrentLang
            : Configuration::get('PS_LOGO');
    }
}
