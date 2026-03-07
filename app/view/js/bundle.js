(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
angular.module('alchemis', [
  require('./controllers'),
  require('./services'),
  require('./directives'),
  require('./filters')
]).config(function ($interpolateProvider) {
  $interpolateProvider.startSymbol('#(').endSymbol(')');
});
},{"./controllers":7,"./directives":9,"./filters":10,"./services":14}],2:[function(require,module,exports){
module.exports = function ($scope, CampaignCharacteristicService) {

    $scope.defaultCharacteristics = [];

    $scope.characteristicsSetUp = function (initId, objectType, parentObjectId) {
       CampaignCharacteristicService.getByInitiative(initId, parentObjectId).then(function (items) {
            $scope.defaultCharacteristics = items;
        });
    }
};
},{}],3:[function(require,module,exports){
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
},{}],4:[function(require,module,exports){
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
},{}],5:[function(require,module,exports){
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
},{}],6:[function(require,module,exports){
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
},{}],7:[function(require,module,exports){
module.exports = name = 'alchemis.controllers';

angular.module(name, [])
  .controller('DedupeController', require('./DedupeController'))
  .controller('CharacteristicController', require('./CharacteristicController'))
  .controller('CompanyCreateController', require('./CompanyCreateController'))
  .controller('ParentCompanyCreateController', require('./ParentCompanyCreateController'))
  .controller('WorkspaceCategoryController', require('./WorkspaceCategoryController'));
},{"./CharacteristicController":2,"./CompanyCreateController":3,"./DedupeController":4,"./ParentCompanyCreateController":5,"./WorkspaceCategoryController":6}],8:[function(require,module,exports){
module.exports = function ($http, $compile, $timeout, $parse, $q) {
  return {
    restrict: 'A',
    scope: {
      onChangeCallback: '@acOnChange',
    },
    link: function (scope, element, attributes) {

      scope.matches = [];
      // url that points to the json array of autocomplete values
      scope.url = 'index.php?cmd=' + attributes.autoComplete + '&action=autocomplete';
      // value that should display and matched against
      scope.display = attributes.acText;
      // value that should be put in hidden field
      scope.model = attributes.acValue;
      // name of the hidden field
      scope.hidden = attributes.acHidden;
      // value of hidden field
      scope.initValue = attributes.acInitValue;
      // ignore this model
      scope.ignore = attributes.acIgnore;
      // hide hint
      scope.hint = attributes.acHint ? parseInt(attributes.acHint) : true;

      scope.value = '';
      scope.hiddenValue = scope.initValue;
      scope.selected = 0;

      var container = angular.element('.autocomplete-container');
      var template = '<span class="autocomplete" style="visibility: visible; -webkit-transform-origin: 0px 0px; -webkit-transform: scale(1, 1); bottom: auto; right: auto;"><ul><li ng-repeat="match in matches" ng-mousedown="select($index)" ng-class="{selected: selected == $index}" ng-bind="match.display"></li></ul></span>';
      scope.ele = $compile(template)(scope);

      if(scope.hidden){
        var hidden = '<input type="hidden" name="#(hidden)" value="#(hiddenValue)">';
        scope.hiddenEle = $compile(hidden)(scope);
      }

      var canceler = null;
      scope.getMatches = function (value) {
        if(canceler) canceler.resolve();
        if(!value){
          scope.matches = matches;
          scope.$apply();
          return;
        }
        var matches = [];
        if(scope.hint){
          matches = [{
            model: null,
            display: value
          }];
        }
        scope.selected = 0;
        // request values
        canceler = $q.defer();
        $http.get(scope.url, {
          params: {
            query: value
          },
          timeout: canceler.promise
        }).success(function (data) {
          // parse the values
          if(!data) return;
          data.forEach(function (o) {
            var val = $parse(scope.display)(o);
            var parsedModel = $parse(scope.model)(o);
            if(parsedModel == scope.ignore) return;
            matches.push({
              model: parsedModel,
              display: val
            });
          });
          scope.matches = matches;
        });

      };

      scope.create = function () {
        container.append(scope.ele);
        if(scope.hiddenEle) element.after(scope.hiddenEle);
      };

      $timeout(function () {
        // wait for digest cycle
        scope.create();
      });

      element.on('keyup', function (e) {
        if(e.keyCode == 40 || e.keyCode == 38){
          return false;
        }
        scope.value = element.val();
        scope.getMatches(scope.value);
      });

      element.on('keydown', function (e) {
        if(e.keyCode == 40){
          scope.down();
          return false;
        }else if(e.keyCode == 38){
          scope.up();
          return false;
        }else if(e.keyCode == 13){
          scope.replace();
          scope.hide();
          return false;
        }
      });

      element.on('focus', function () {
        scope.show();
      });

      element.on('blur', function () {
        $timeout(function () {
          scope.hide();
        }, 300);
      });

      scope.$on('$destroy', function () {
        scope.ele.remove();
        element.remove();
      });

      scope.show = function () {
        scope.layout();
        scope.ele.show();
      };

      scope.hide = function () {
        scope.ele.hide();
      };

      scope.layout = function () {
        var offset = element.offset();
        var top = offset.top + element.outerHeight();
        var left = offset.left;
        var width = element.outerWidth();

        scope.ele.css({
          top: top,
          left: left,
          'min-width': width
        });
      };

      scope.up = function () {
        if(scope.selected > 0) scope.selected -= 1;
        scope.$apply();
        scope.scroll();
      };

      scope.down = function () {
        if(scope.selected < scope.matches.length - 1) scope.selected += 1;
        scope.$apply();
        scope.scroll();
      };

      scope.scroll = function () {
        var item = angular.element(scope.ele.find('li').get(scope.selected));
        var parent = scope.ele.find('ul');
        var scroll = parent.scrollTop() + item.position().top;
        parent.scrollTop(scroll);
      };

      scope.replace = function () {
        var selected = scope.matches[scope.selected];
        element.val(selected.display);
        // model to hidden input
        scope.hiddenValue = selected.model;
        // pass model to change callback
        if(scope.onChangeCallback){
  				var cb = $parse(scope.onChangeCallback);
  				cb(scope.$parent, {model: selected});
        }
      };

      scope.select = function (i) {
        scope.selected = i;
        scope.replace();
      };
    }
  };

};
},{}],9:[function(require,module,exports){
module.exports = name = 'alchemis.directives';

angular.module(name, [])
  .directive('autoComplete', require('./autoComplete'));
},{"./autoComplete":8}],10:[function(require,module,exports){
module.exports = name = 'alchemis.filters';

angular.module(name, []);
},{}],11:[function(require,module,exports){
module.exports = function ($http) {
    var url = '/index.php?cmd=CampaignCharacteristics';


    var CampaignCharacteristic = (function () {
        function CampaignCharacteristic() {

        }

        CampaignCharacteristic.prototype.createCharacteristic = function () {
            addCharacteristic(this.characteristic_id);
        };

        return CampaignCharacteristic;
    })();

    return {

        getByInitiative: function (initId, parentId) {
            var self = this;

            return $http.get(url, {
                params: {
                    initId: initId,
                    parentId: parentId
                }
            }).then(function (res) {
                if (res.data) {
                    return self.loadAll(res.data);
                }
            });
        },

        loadAll: function (items) {
            var characteristics = [];
            items.forEach(function (item) {
                characteristics.push(angular.extend(item, new CampaignCharacteristic()));
            });
            return characteristics;
        }
    }
};
},{}],12:[function(require,module,exports){
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
},{}],13:[function(require,module,exports){
module.exports = function ($http) {
  var url = '/index.php?cmd=Dedupe';
  return {
    status: function () {
      return $http.get(url + '&action=statusjson');
    },
    mismatches: function (page, search) {
      return $http.get(url + '&action=mismatchesjson&page=' + page + '&search=' + search);
    },
    getMismatch: function (id) {
      return $http.get(url + '&action=getMismatch&id=' + id);
    },
    removeMismatch: function (id) {
      return $http.get(url + '&action=removeMismatch&id=' + id);
    },
    saveAdditions: function (additions) {
      return $http.post(url + '&action=saveadditions', {
        additions: additions
      });
    }
  };
};
},{}],14:[function(require,module,exports){
module.exports = name = 'alchemis.services';

angular.module(name, [])
  .factory('DedupeService', require('./DedupeService'))
  .factory('CampaignCharacteristicService', require('./CampaignCharacteristicService'))
  .factory('CategoryService', require('./CategoryService'));
},{"./CampaignCharacteristicService":11,"./CategoryService":12,"./DedupeService":13}]},{},[1]);
