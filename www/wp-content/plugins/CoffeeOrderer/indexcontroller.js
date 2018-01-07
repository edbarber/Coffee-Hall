angular.module("coffeeApp", []).controller("coffeeCtrl", function ($scope, $http, $timeout) {
  $scope.model = {};

  $scope.submitForm = function () {    
    $http.post("/slim/api/orders", $scope.model).then(
      function (data) {
        $scope.model = {};      
        $scope.message="Your order is placed!";
        $scope.showMessage = true;
        $timeout(function(){
           $scope.showMessage = false;
        }, 3000);
        
      }, function (err) {
        $scope.message=JSON.stringify(err);
      });
  }
});