module.exports = function ($scope, CategoryService) {

	CategoryService.list().success(function (data) {
		$scope.company_categories = data;
		$scope.site_categories = data;
	});

	$scope.category = null;

	$scope.companyCategoryChange = function () {
		CategoryService.listSubCategories($scope.company_category.id).success(function (data) {
			$scope.company_subcategories = data;
		});
	};

	$scope.siteCategoryChange = function () {
		CategoryService.listSubCategories($scope.site_category.id).success(function (data) {
			$scope.site_subcategories = data;
		});
	};

};