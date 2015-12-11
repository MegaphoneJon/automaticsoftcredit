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

/**
 * Helper function for storing persistant data
 * for this extension.
 **/

function automaticsoftcredit_save_setting($key, $value) {
  $group = 'Automatic Soft Credits';
  CRM_Core_BAO_Setting::setItem($value, $group, $key);
}

/**
 * Helper function for getting persistant data
 * for this extension.
 **/

function automaticsoftcredit_get_setting($key, $default = NULL) {
  $group = 'Automatic Soft Credits';
  $ret = CRM_Core_BAO_Setting::getItem($group, $key);
  if(empty($ret)) return $default;
  return $ret;
}

/**
 * Get all relationship types
 **/

function automaticsoftcredit_get_all_relationship_types() {
  $values = array();
  CRM_Core_PseudoConstant::populate($values, 'CRM_Contact_DAO_RelationshipType', $all = TRUE);
  return $values;
}

// This is the main function of this extension, for Civi 4.6.
function automaticsoftcredit_civicrm_pre($op, $objectName, $id, &$params) {
  if (!($op == 'create' && $objectName == 'Contribution')) {
    return;
  }

  $cid = $params['contact_id'];

  CRM_Core_Error::debug_var('hi', $cid);

  //Look up whether this person has a relationship_type_id that's automatically soft credited
  $apiParams = array(
    'version' => 3,
    'sequential' => 1,
    'is_active' => 1,
    'contact_id_a' => $cid,
    'relationship_type_id' => 11, //FIXME: This relationship_type_id is currently hardcoded, we should load it from settings
  );
  $result = civicrm_api('Relationship', 'get', $apiParams);
CRM_Core_Error::debug_var('relationship result', $result);

  //if we have the auto soft credit relationship for one or more contacts, create a soft credit for each
  if($result['count'] > 0) {
    foreach ($result['values'] as $relationship) {
      $params['soft_credit'][] = array(
        'contact_id' => $relationship['contact_id_b'],
        'amount' => $params['total_amount'],
        'soft_credit_type_id' => NULL,
      );
    }
  }
}

/* Where the magic happens */
/*
function automaticsoftcredit_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {
  if($op == 'create' && $objectName == 'Contribution'){
    $cid = $objectRef->contact_id;

    //Look up whether this person has a relationship_type_id that's automatically soft credited
    $params = array(
     'version' => 3,
      'sequential' => 1,
      'contact_id_a' => $cid,
      'relationship_type_id' => 11, //FIXME: This relationship_type_id is currently hardcoded, we should load it from settings
    );
    $result = civicrm_api('Relationship', 'get', $params);
    watchdog('Auto Soft Credit', "Result Count: " . $result['count']);
    watchdog('Auto Soft Credit', "Contribution ID: " . $objectId);
    //if we have the auto soft credit relationship for one or more contacts, create a soft credit for each
    if($result['count'] > 0) {
      foreach ($result['values'] as $relationship) {
        watchdog('Auto Soft Credit', "Contact B: " . $relationship['contact_id_b']);
        $params = array(
          'version' => 3,
          'sequential' => 1,
          'contribution_id' => $objectId,
          'contribution_soft_contact_id' => $relationship['contact_id_b'],
          'amount' => $objectRef->total_amount,
        );
        $result = civicrm_api('ContributionSoft', 'create', $params);
      }
    }

  }
}
*/
