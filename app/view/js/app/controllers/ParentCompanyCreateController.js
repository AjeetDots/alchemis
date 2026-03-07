module.exports = function ($scope, CategoryService) {

	CategoryService.list().success(function (data) {
		$scope.categories = data;
	});

	$scope.category = null;

	$scope.categoryChange = function () {
		CategoryService.listSubCategories($scope.category.id).success(function (data) {
			$scope.subcategories = data;
		});
	};

};