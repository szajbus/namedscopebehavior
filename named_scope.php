<?php
class NamedScopeBehavior extends ModelBehavior {
	static $__settings = array();
	
	function setup(&$model, $settings = array()) {
	  self::$__settings[$model->name] = $settings;
	}
	
	function beforeFind(&$model, &$queryData) {
	  $scopes = array();
	  // passed as scopes
    if (!empty($queryData['scopes'])) {
      $scope = !is_array($queryData['scopes']) ? array($queryData['scopes']) : $queryData['scopes'];
      $scopes = am($scopes, $scope);
    }
    
    // passed as conditions['scopes']
    if (is_array($queryData['conditions']) && !empty($queryData['conditions']['scopes'])) {
      $scope = !is_array($queryData['conditions']['scopes']) ? array($queryData['conditions']['scopes']) : $queryData['conditions']['scopes'];
      unset($queryData['conditions']['scopes']);
      $scopes = am($scopes, $scope);
    }
    
    foreach ($scopes as $scope) {
      if (strpos($scope, '.')) {
        list($scopeModel, $scope) = explode('.', $scope);
      } else {
        $scopeModel = $model->name;
      }
      if (!empty(self::$__settings[$scopeModel][$scope])) {
        $conditions = self::$__settings[$scopeModel][$scope];
        if (!is_array($conditions)) {
          $conditions = array($conditions);
        }
        foreach ($conditions as $condition) {
          $queryData['conditions'][] = $condition;
        }
      }
    }
    
    return $queryData;
	}
}
?>