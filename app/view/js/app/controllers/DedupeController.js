module.exports = function ($scope, DedupeService) {

  function getStatus () {
    DedupeService.status().success(function (data) {
      $scope.status = data;
    });
  }

  getStatus();

  $scope.mismatches = [];
  $scope.page = 1;
  $scope.searchInput = '';
  function getMismatches () {
    DedupeService.mismatches($scope.page, $scope.searchInput).success(function (data) {
      $scope.mismatches = data;
    });
  }

  getMismatches();

  $scope.get = function () {
    getMismatches();
  };
  
  $scope.getMismatch = function (row) {
    row.show = !row.show;
    $scope.matches = [];
    DedupeService.getMismatch(row.m.id).success(function (data) {
      row.mismatch = data;
    });
  };
  
  $scope.matches = [];
  $scope.selectMatch = function (row) {
    row.selected = !row.selected;
    var id = row.c.id;
    if(row.selected){
      $scope.matches.push(id);
    }else{
      $scope.matches.splice($scope.matches.indexOf(id), 1);
    }
  };

  $scope.save = function (mismatch) {
    $scope.matches.push(mismatch.company_id);

    DedupeService.saveAdditions($scope.matches).success(function (data) {
      $scope.mismatches.forEach(function (m, i) {
        if($scope.matches.indexOf(m.company_id) !== -1){
          $scope.mismatches.splice(i, 1);
        }
      });
    });
  };

  $scope.remove = function (mismatch) {
    DedupeService.removeMismatch(mismatch.id).success(function () {
      $scope.mismatches.splice($scope.mismatches.indexOf(mismatch), 1);
    });
  };

  $scope.search = function () {
    $scope.page = 1;
    getMismatches();
  };

  $scope.next = function () {
    $scope.page++;
    getMismatches();
  };

  $scope.last = function () {
    $scope.page--;
    getMismatches();
  };

};