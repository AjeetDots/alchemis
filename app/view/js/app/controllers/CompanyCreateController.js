module.exports = function ($scope, CategoryService, $http) {

	CategoryService.list().success(function (data) {
		$scope.categories = data;
	});

	$scope.category = null;

	$scope.categoryChange = function () {
		CategoryService.listSubCategories($scope.category.id).success(function (data) {
			$scope.subcategories = data;
		});
	};

	$scope.companyChange = function (model) {
		$http.get('/index.php?cmd=ParentCompany&action=getMainCategory&id=' + model.model).success(function (data) {
			$scope.category = data;
			$scope.categoryChange();
		});
	};

};