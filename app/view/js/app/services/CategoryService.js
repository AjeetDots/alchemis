module.exports = function ($http) {
	var url = '/index.php?cmd=Category';
	return {
		list: function () {
			return $http.get(url + '&action=lists');
		},
		listSubCategories: function (id) {
			return $http.get(url + '&action=listSubCategories&id=' + id);
		}
	};
};