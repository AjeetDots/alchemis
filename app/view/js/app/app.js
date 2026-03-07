angular.module('alchemis', [
  require('./controllers'),
  require('./services'),
  require('./directives'),
  require('./filters')
]).config(function ($interpolateProvider) {
  $interpolateProvider.startSymbol('#(').endSymbol(')');
});