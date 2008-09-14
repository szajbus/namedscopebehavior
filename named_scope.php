<?php
class NamedScopeBehavior extends ModelBehavior {
	static $__settings = array();
	
	function setup(&$model, $settings = array()) {
	  self::$__settings[$model->name] = $settings;
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
      if (strpos($scope, '.')) {
        list($scopeModel, $scope) = explode('.', $scope);
      } else {
        $scopeModel = $model->name;
      }
      if (!empty(self::$__settings[$scopeModel][$scope])) {
        $queryData['conditions'][] = self::$__settings[$scopeModel][$scope];
      }
    }
    return $queryData;
	}
}
?>