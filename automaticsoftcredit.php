<?php

require_once 'automaticsoftcredit.civix.php';
use CRM_Automaticsoftcredit_ExtensionUtil as E;

// This is the main function of this extension, for Civi 4.6.
function automaticsoftcredit_civicrm_pre($op, $objectName, $id, &$params) {
  if (!($op == 'create' && $objectName == 'Contribution')) {
    return;
  }
  $softCreditTypeField = CRM_Core_BAO_CustomField::getCustomFieldID('softcreditrelationshiptype', NULL, TRUE);
  $softCreditDirectionField = CRM_Core_BAO_CustomField::getCustomFieldID('softcreditdirection', NULL, TRUE);
  //Look up whether this person has a relationship_type_id that's automatically soft credited
  $apiParams = [
    'return' => ["relationship_type_id.$softCreditTypeField", "contact_id_a", "contact_id_b"],
    'is_active' => 1,
    'contact_id_a' => $params['contact_id'],
    "relationship_type_id.$softCreditDirectionField" => ['IN' => [1, 3]],
    "relationship_type_id.$softCreditTypeField" => ['IS NOT NULL' => 1],
  ];
  $result = civicrm_api3('Relationship', 'get', $apiParams)['values'];
  // Do it again in reverse and append the results that are different.
  $apiParams = [
    'return' => ["relationship_type_id.$softCreditTypeField", "contact_id_a", "contact_id_b"],
    'is_active' => 1,
    'contact_id_b' => $params['contact_id'],
    "relationship_type_id.$softCreditDirectionField" => ['IN' => [2, 3]],
    "relationship_type_id.$softCreditTypeField" => ['IS NOT NULL' => 1],
  ];
  $result += civicrm_api3('Relationship', 'get', $apiParams)['values'];
  //if we have the auto soft credit relationship for one or more contacts, create a soft credit for each
  foreach ($result as $relationship) {
    // Get the correct "other person" in the relationships.
    if ($relationship['contact_id_a'] == $params['contact_id']) {
      $softCreditee = $relationship['contact_id_b'];
    }
    else {
      $softCreditee = $relationship['contact_id_a'];
    }
    $params['soft_credit'][] = [
      'contact_id' => $softCreditee,
      'amount' => $params['total_amount'],
      'soft_credit_type_id' => $relationship["relationship_type_id.$softCreditTypeField"],
    ];
  }


}

function automaticsoftcredit_civicrm_fieldOptions($entity, $field, &$options, $params) {
  if ($entity != 'RelationshipType') {
    return;
  }
  $softCreditTypeField = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID('softcreditrelationshiptype');
  $softCreditDirectionField = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID('softcreditdirection');
  if ($field == $softCreditTypeField) {
	  $softCreditOptions = civicrm_api3('OptionValue', 'get', ['option_group_id' => "soft_credit_type",])['values'];
    foreach ($softCreditOptions as $softCreditOption) {
      $options[$softCreditOption['value']] = $softCreditOption['label'];
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function automaticsoftcredit_civicrm_config(&$config) {
  _automaticsoftcredit_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function automaticsoftcredit_civicrm_xmlMenu(&$files) {
  _automaticsoftcredit_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function automaticsoftcredit_civicrm_install() {
  _automaticsoftcredit_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function automaticsoftcredit_civicrm_postInstall() {
  _automaticsoftcredit_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function automaticsoftcredit_civicrm_uninstall() {
  _automaticsoftcredit_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function automaticsoftcredit_civicrm_enable() {
  _automaticsoftcredit_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function automaticsoftcredit_civicrm_disable() {
  _automaticsoftcredit_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function automaticsoftcredit_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _automaticsoftcredit_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function automaticsoftcredit_civicrm_managed(&$entities) {
  _automaticsoftcredit_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function automaticsoftcredit_civicrm_caseTypes(&$caseTypes) {
  _automaticsoftcredit_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function automaticsoftcredit_civicrm_angularModules(&$angularModules) {
  _automaticsoftcredit_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function automaticsoftcredit_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _automaticsoftcredit_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function automaticsoftcredit_civicrm_entityTypes(&$entityTypes) {
  _automaticsoftcredit_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function automaticsoftcredit_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function automaticsoftcredit_civicrm_navigationMenu(&$menu) {
  _automaticsoftcredit_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _automaticsoftcredit_civix_navigationMenu($menu);
} // */
