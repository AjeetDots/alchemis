module.exports = name = 'alchemis.services';

angular.module(name, [])
  .factory('DedupeService', require('./DedupeService'))
  .factory('CampaignCharacteristicService', require('./CampaignCharacteristicService'))
  .factory('CategoryService', require('./CategoryService'));