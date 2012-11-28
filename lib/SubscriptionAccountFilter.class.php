<?php
class SubscriptionAccountFilter extends sfFilter {
  
  /**
   * Executes the filter chain.
   *
   * @param sfFilterChain $filterChain
   */
  public function execute($filterChain) {
    $user = $this->getContext()->getUser();
    if ($user->isAuthenticated()) {
      $account = $user->getAccount();
      if (($this->isExpired($account)) && 
            !$this->allowPage($this->getContext()->getModuleName(), $this->getContext()->getActionName())) {
        $this->onExpired($account);
      }
    }
    
    $filterChain->execute();
  }
  
  protected function isExpired($account) {
    return $account->isExpired();
  }
  
  protected function onExpired($account) {
    $this->getContext()->getController()->redirect($this->getRouteForExpired());
  }
  
  public function allowPage($module, $action) {
    $allowed = $this->getAllowedPages();
    if (!$allowed) { return false; }
    
    if (array_key_exists($module, $allowed)) {
      if (is_array($allowed[$module])) {
        return in_array($action, $allowed[$module]);
      } 
      return '*' == $allowed[$module];
    }
    return false;
  }
  
  public function getAllowedPages() {
    return sfConfig::get('app_account_expired_allowed_pages', array('account' => '*'));
  }
  
  public function getRouteForExpired() {
    return sfConfig::get('app_dzangocart_account_expired_route', 'account/expired');
  }
  
}