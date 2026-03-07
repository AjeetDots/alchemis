module.exports = name = 'alchemis.controllers';

angular.module(name, [])
  .controller('DedupeController', require('./DedupeController'))
  .controller('CharacteristicController', require('./CharacteristicController'))
  .controller('CompanyCreateController', require('./CompanyCreateController'))
  .controller('ParentCompanyCreateController', require('./ParentCompanyCreateController'))
  .controller('WorkspaceCategoryController', require('./WorkspaceCategoryController'));