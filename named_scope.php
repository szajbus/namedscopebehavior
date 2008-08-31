<?php
class NamedScopeBehavior extends ModelBehavior {
	var $settings = array();
	
	function setup(&$model, $settings = array()) {
		$this->settings[$model->name] = $settings;
	}
	
	function beforeFind(&$model, &$queryData) {
	  $scopes = array();
	  
	  // passed as scope
    if (!empty($queryData['scope'])) {
      $scope = !is_array($queryData['scope']) ? array($queryData['scope']) : $queryData['scope'];
      $scopes = am($scopes, $scope);
    }
    
    // passed as conditions['scope']
    if (!empty($queryData['conditions']['scope'])) {
      $scope = !is_array($queryData['conditions']['scope']) ? array($queryData['conditions']['scope']) : $queryData['conditions']['scope'];
      unset($queryData['conditions']['scope']);
      $scopes = am($scopes, $scope);
    }
    
    foreach ($scopes as $scope) {
      if (!empty($this->settings[$model->name][$scope])) {
        $queryData['conditions'][] = $this->settings[$model->name][$scope];
      }
    }
    return $queryData;
	}
}
?>