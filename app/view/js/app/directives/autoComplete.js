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