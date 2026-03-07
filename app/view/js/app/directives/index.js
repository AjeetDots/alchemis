module.exports = name = 'alchemis.directives';

angular.module(name, [])
  .directive('autoComplete', require('./autoComplete'));