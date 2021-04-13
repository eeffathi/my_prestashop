<?php
/**
* NOTICE OF LICENSE.
*
* This source file is subject to a commercial license from BSofts.
* Use, copy, modification or distribution of this source file without written
* license agreement from the BSofts is strictly forbidden.
*
*  @author    BSoft Inc
*  @copyright 2020 BSoft Inc.
*  @license   Commerical License
*/

header("Content-type: application/json");
class AdminGoogleAddressController extends ModuleAdminController
{
    public function init()
    {
        parent::init();
        $this->context = Context::getContext();
        $this->ajax = Tools::getValue('ajax', true);
        if (!$this->ajax) {
            Tools::redirectAdmin($this->module->getModuleUrl());
        }
    }
    
    public function ajaxProcessGetCountry()
    {
        $response = array('success' => true, 'id_country' => false, 'hasState' => true);
        $isoCountry = Tools::safeOutput(Tools::getValue('iso_country'));
        $idCountry = null;
        if (!empty($isoCountry)) {
            $idCountry = (int)Country::getByIso($isoCountry);
            $response['id_country'] = (int)$idCountry;
            $response['hasState'] = (int)Country::containsStates($idCountry);
        }
        die(Tools::jsonEncode($response));
    }

    public function ajaxProcessGetState()
    {
        $response = array('success' => true, 'id_state' => false);
        $isoState = Tools::safeOutput(Tools::getValue('iso_state'));
        if (!empty($isoState)) {
            $idCountry = null;
            $isoCountry = Tools::safeOutput(Tools::getValue('iso_country'));
            if (!empty($isoCountry)) {
                $idCountry = (int)Country::getByIso($isoCountry, true);
            }
            $idState = State::getIdByIso($isoState, $idCountry);
            $response['id_state'] = (int)$idState;
        }
        die(Tools::jsonEncode($response));
    }
}
