<?php

require_once 'automaticsoftcredit.civix.php';
use CRM_Automaticsoftcredit_ExtensionUtil as E;

function automaticsoftcredit_civicrm_postCommit($op, $objectName, $objectId, &$objectRef) {
  if (!($op == 'create' && $objectName == 'Contribution')) {
    return;
  }

  //Look up whether this person has a relationship_type_id that's automatically soft credited
  $relationshipsRaw = \Civi\Api4\RelationshipCache::get(FALSE)
    ->addSelect('relationship_type.automaticsoftcredit.softcreditrelationshiptype', 'relationship_type.automaticsoftcredit.softcreditdirection', 'far_contact_id', 'orientation')
    ->addWhere('is_active', '=', TRUE)
    ->addWhere('near_contact_id', '=', $objectRef->contact_id)
    ->addWhere('relationship_type.automaticsoftcredit.softcreditrelationshiptype', 'IS NOT NULL', '')
    ->execute();
  // Add a soft credit, skipping any relationships that go in the wrong direction.
  foreach ($relationshipsRaw as $k => $relationship) {
    if ($relationship['orientation'] === 'a_b' && $relationship['relationship_type.automaticsoftcredit.softcreditdirection'] === 2) {
      continue;
    }
    if ($relationship['orientation'] === 'b_a' && $relationship['relationship_type.automaticsoftcredit.softcreditdirection'] === 1) {
      continue;
    }
    // TODO: Civi 5.33 should support ContributionSoft in API4.
    civicrm_api3('ContributionSoft', 'create', [
      'contribution_id' => $objectId,
      'amount' => $objectRef->total_amount,
      'contact_id' => $relationship['far_contact_id'],
    ]);
  }
}

function automaticsoftcredit_civicrm_fieldOptions($entity, $field, &$options, $params) {
  if ($entity != 'RelationshipType') {
    return;
  }
  $softCreditTypeField = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID('softcreditrelationshiptype');
  $softCreditDirectionField = 'custom_' . CRM_Core_BAO_CustomField::getCustomFieldID('softcreditdirection');
  if ($field == $softCreditTypeField) {
    $softCreditOptions = civicrm_api3('OptionValue', 'get', ['option_group_id' => "soft_credit_type"])['values'];
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
