var ffdApp = angular.module('ffdApp', ['tableSort', 'ui.bootstrap']).config(function($interpolateProvider){
	$interpolateProvider.startSymbol('[[').endSymbol(']]');
});

ffdApp.controller('RequestsListController', ['$scope', '$http', '$uibModal', function RequestsListController($scope, $http, $uibModal) {
	$scope.furnitureRequests = null;
	$http.get('/api/donation-requests/list').then(function(resp){
		$scope.furnitureRequests = resp.data;
	});

	$scope.openItemModal = function (item) {
	    var modalInstance = $uibModal.open({
	      ariaLabelledBy: 'modal-title',
	      ariaDescribedBy: 'modal-body',
	      templateUrl: 'itemModalContent.html',
	      controller: 'ItemModalCtrl'
	    });
	};

	$scope.openRequestDetailModal = function (request) {
	    var modalInstance = $uibModal.open({
	      ariaLabelledBy: 'modal-title',
	      ariaDescribedBy: 'modal-body',
	      templateUrl: 'requestDetailModalContent.html',
	      controller: 'RequestDetailModalCtrl'
	    });
	};

	$scope.clearSearchText = function() {
		$scope.searchText = '';
	};
}]);

ffdApp.controller('ItemModalCtrl', function ($scope, $uibModalInstance) {
  $scope.ok = function () {
    $uibModalInstance.close();
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});

ffdApp.controller('RequestDetailModalCtrl', function ($scope, $uibModalInstance) {
  $scope.ok = function () {
    $uibModalInstance.close();
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});

ffdApp.directive('showonhoverparent',
   function() {
      return {
         link : function(scope, element, attrs) {
            element.parent().bind('mouseenter', function() {
                element.show();
            });
            element.parent().bind('mouseleave', function() {
                 element.hide();
            });
       }
   };
});