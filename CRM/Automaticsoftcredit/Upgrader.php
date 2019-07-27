<?php
use CRM_Automaticsoftcredit_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Automaticsoftcredit_Upgrader extends CRM_Automaticsoftcredit_Upgrader_Base {

   private function addCustomData() {
    // Allow custom fields on relationship type.
    $optionValues = civicrm_api3('OptionValue', 'get', [
      'option_group_id' => 'cg_extend_objects',
      'name' => 'civicrm_relationship_type'
    ]);
    if (!$optionValues['count']) {
      civicrm_api3('OptionValue', 'create', [
        'option_group_id' => 'cg_extend_objects',
        'name' => 'civicrm_relationship_type',
        'label' => ts('Relationship Type'),
        'value' => 'RelationshipType',
      ]);
    }
    $optionGroup = civicrm_api3('OptionGroup', 'get', [
      'name' => 'auto_soft_credit_directions'
    ]);
    if (!$optionGroup['count']) {
      $optionGroup = civicrm_api3('OptionGroup', 'create', [
        'name' => 'auto_soft_credit_directions',
        'title' => E::ts('Automatic Soft Credit Directions'),
        'is_reserved' => 1,
      ]);
      $directions = [
        1 => 'When Contact A gives, soft credit Contact B',
        2 => 'When Contact B gives, soft credit Contact A',
        3 => 'Soft Credit in both directions',
      ];
      foreach ($directions as $key => $direction) {
        civicrm_api3('OptionValue', 'create', [
          'name' => E::ts($direction),
          'value' => $key,
          'is_reserved' => 1,
          'option_group_id' => $optionGroup['id'],
        ]);
      }
    }

    $customGroups = civicrm_api3('CustomGroup', 'get', [
      'extends' => 'RelationshipType',
      'name' => 'automaticsoftcredit',
    ]);
    if (!$customGroups['count']) {
      $customGroups = civicrm_api3('CustomGroup', 'create', [
        'extends' => 'RelationshipType',
        'name' => 'automaticsoftcredit',
        'title' => E::ts('Automatic Soft Credits'),
      ]);
    }
    $customFields = civicrm_api3('CustomField', 'get', [
      'custom_group_id' => $customGroups['id'],
    ]);
    if (!$customFields['count']) {
      civicrm_api3('CustomField', 'create', [
        'custom_group_id' => $customGroups['id'],
        'name' => 'softcreditrelationshiptype',
        'label' => E::ts('Soft Credit Type'),
        'data_type' => 'Int',
        'default_value' => NULL,
        'html_type' => 'Select',
        'required' => 0,
        'is_searchable' => 1,
        'help_post' => E::ts("If this field isn't blank, a soft credit will be created whenever someone with this relationship makes a contribution (subject to Soft Credit Direction)."),
        'weight' => 1,
      ]);
      civicrm_api3('CustomField', 'create', [
        'custom_group_id' => $customGroups['id'],
        'name' => 'softcreditdirection',
        'label' => E::ts('Soft Credit Direction'),
        'data_type' => 'Int',
        'default_value' => 1,
        'html_type' => 'Select',
        'required' => 1,
        'is_searchable' => 1,
        'option_group_id' => $optionGroup['id'],
        'weight' => 2,
      ]);
    }
  }

  private function removeCustomData() {
    $customGroup = civicrm_api3('CustomGroup', 'get', ['name' => 'automaticsoftcredit']);
    if ($customGroup['count']) {
      civicrm_api3('CustomGroup', 'delete', ['id' => $customGroup['id']]);
    }

    $optionGroup = civicrm_api3('OptionGroup', 'get', ['name' => 'auto_soft_credit_directions']);
    if ($optionGroup['count']) {
      civicrm_api3('OptionGroup', 'delete', ['id' => $optionGroup['id']]);
    }
  }

  /**
   * Example: Run an external SQL script when the module is installed.
   */
  public function install() {
    $this->addCustomData();
  }

  public function uninstall() {
    $this->removeCustomData();
  }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   *
  public function postInstall() {
    $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
      'return' => array("id"),
      'name' => "customFieldCreatedViaManagedHook",
    ));
    civicrm_api3('Setting', 'create', array(
      'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
    ));
  }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
  public function uninstall() {
   $this->executeSqlFile('sql/myuninstall.sql');
  }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  public function enable() {
    $this->addCustomData();
  }

  /**
   * Example: Run a simple query when a module is disabled.
   *
  public function disable() {
    CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   *
  public function upgrade_4200() {
    $this->ctx->log->info('Applying update 4200');
    CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
    CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
    return TRUE;
  } // */


  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4201() {
    $this->ctx->log->info('Applying update 4201');
    // this path is relative to the extension base dir
    $this->executeSqlFile('sql/upgrade_4201.sql');
    return TRUE;
  } // */


  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4202() {
    $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

    $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
    $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
    $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
    return TRUE;
  }
  public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  public function processPart3($arg5) { sleep(10); return TRUE; }
  // */


  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws Exception
  public function upgrade_4203() {
    $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

    $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
    $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
    for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
      $endId = $startId + self::BATCH_SIZE - 1;
      $title = E::ts('Upgrade Batch (%1 => %2)', array(
        1 => $startId,
        2 => $endId,
      ));
      $sql = '
        UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
        WHERE id BETWEEN %1 and %2
      ';
      $params = array(
        1 => array($startId, 'Integer'),
        2 => array($endId, 'Integer'),
      );
      $this->addTask($title, 'executeSql', $sql, $params);
    }
    return TRUE;
  } // */

}
