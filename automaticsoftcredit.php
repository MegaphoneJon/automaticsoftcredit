<?php

require_once 'automaticsoftcredit.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function automaticsoftcredit_civicrm_config(&$config) {
  _automaticsoftcredit_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function automaticsoftcredit_civicrm_xmlMenu(&$files) {
  _automaticsoftcredit_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function automaticsoftcredit_civicrm_install() {
  return _automaticsoftcredit_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function automaticsoftcredit_civicrm_uninstall() {
  return _automaticsoftcredit_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function automaticsoftcredit_civicrm_enable() {
  return _automaticsoftcredit_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function automaticsoftcredit_civicrm_disable() {
  return _automaticsoftcredit_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function automaticsoftcredit_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _automaticsoftcredit_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function automaticsoftcredit_civicrm_managed(&$entities) {
  return _automaticsoftcredit_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function automaticsoftcredit_civicrm_caseTypes(&$caseTypes) {
  _automaticsoftcredit_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function automaticsoftcredit_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _automaticsoftcredit_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_navigationMenu
 */
function automaticsoftcredit_civicrm_navigationMenu( &$params ) {
  // Add menu entry for extension administration page
  _automaticsoftcredit_civix_insert_navigation_menu($params, 'Administer/Customize Data and Screens', array(
    'name'       => 'Automatic Soft Credits',
    'url'        => 'civicrm/admin/setting/auto-soft-credits',
    'permission' => 'administer CiviCRM',
  ));
}

