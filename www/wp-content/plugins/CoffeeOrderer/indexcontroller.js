angular.module("coffeeApp", []).controller("coffeeCtrl", function ($scope, $http) {
  $scope.model = {};

  $scope.submitForm = function () {    
    $http.post("/slim/api/orders", $scope.model).then(
      function (data) {
        $scope.model = {};      
        $scope.message = "Your order is placed!";
        $scope.showMessage = true;
      }, function (err) {
        $scope.message = JSON.stringify(err);
      }
    );
  }

  $scope.goBack = function () {
    window.history.back();
  }
});