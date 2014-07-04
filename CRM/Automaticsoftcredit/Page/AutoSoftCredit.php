<?php

require_once 'CRM/Core/Page.php';

class CRM_Automaticsoftcredit_Page_AutoSoftCredit extends CRM_Core_Page {
/*  function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('AutoSoftCredit'));

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }
*/
  function buildQuickForm() {
    $this->addCheckBox(
      'relationship_type_ids',
      ts('Relationship Types'),
      array_flip(autosoftcredit_get_all_relationship_types())
    );
  }
}
