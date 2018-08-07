<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Registercodetogroup extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'registercodetogroup';
        $this->tab = 'others';
        $this->version = '0.1.0';
        $this->author = 'Andrés Vieira & Samuel Rodríguez';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Group registration codes');
        $this->description = $this->l('This module allows users register with a custom code/password that adds them automatically into a user group. This way shop owners can give promo-codes that offer users discounts or special products, for example.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('REGISTERCODETOGROUP_DATA', serialize(array()));


        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('validateCustomerFormFields') &&
            $this->registerHook('actionCustomerAccountAdd') &&
            $this->registerHook('displayCustomerAccountForm');
    }

    public function uninstall()
    {
        Configuration::deleteByName('REGISTERCODETOGROUP_DATA');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitBtn')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        //Assign variables to template
        $data = unserialize(Configuration::get('REGISTERCODETOGROUP_DATA'));
        $form_action = $this->context->link->getAdminLink('AdminModules', false)
                .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
                .'&token='.Tools::getAdminTokenLite('AdminModules');
        $groups = Group::getGroups($this->context->language->id);

        foreach ($data as $key => $value) {
            $data[$key]["groups_names"] = Array();
            foreach ($value["groups_assigned"] as $gak => $gav) {
                array_push($data[$key]["groups_names"], $groups[$gav]['name']);
            }
        }

        $this->context->smarty->assign('groups', $groups);
        $this->context->smarty->assign('form_action', $form_action);
        $this->context->smarty->assign('data', $data);

        //Template file
        return $this->display(__FILE__, 'moduleConfiguration.tpl');
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array();
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $data = unserialize(Configuration::get('REGISTERCODETOGROUP_DATA'));
        $data[Tools::getValue("code_id")] = Array(
            "code" => Tools::getValue("code"),
            "groups_assigned" => Tools::getValue("groups_assigned")
        );

        Configuration::updateValue("REGISTERCODETOGROUP_DATA", serialize($data));
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookValidateCustomerFormFields()
    {
        /* Place your code here. */
    }

    public function hookDisplayCustomerAccountForm()
    {

        //Get PrestaShop groups
        $groups = Group::getGroups($this->context->language->id);

        //Assign variables to template
        $this->context->smarty->assign('customerGroups', $groups);

        //Template file
        return $this->display(__FILE__, 'hookDisplayCustomerAccountForm.tpl');
    }

    public function hookActionCustomerAccountAdd($params)
    {
        //Retrieve variable for group code from the registration form
        $group_code = Tools::getValue('group_code','');
        $group_info = unserialize(Configuration::get('REGISTERCODETOGROUP_DATA'));

        foreach ($group_info as $key => $value) {
            if($value["code"] == $group_code){
                $params['newCustomer']->addGroups($value["groups_assigned"]);
            }
        }

    }
}
