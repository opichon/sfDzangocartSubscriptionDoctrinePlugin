<?php
class DzangocartSubscriptionAccount extends Doctrine_Template {
  
  /**
   * Set table definition for DzangocartSubscriptionAccount behavior
   *
   * @return void
   */
  public function setTableDefinition() {
    $this->hasColumn('plan_id', 'integer', 11, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 11,
    ));
    
    $this->hasColumn('expires_at', 'timestamp', null, array(
             'type' => 'date',
             'notnull' => false,
    ));

    $this->hasOne('Plan', array(
             'local' => 'plan_id',
             'foreign' => 'id'));

  }
  
  public function isExpired() {
    return method_exists($this->getInvoker(),  'isExpired') ?
             $this->getInvoker()->isExpired() :
             $this->getInvoker()->getExpiresAt() < date('Y-m-d h:i:s');
  }
  
}