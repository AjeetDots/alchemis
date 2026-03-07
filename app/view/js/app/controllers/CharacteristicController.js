module.exports = function ($scope, CampaignCharacteristicService) {

    $scope.defaultCharacteristics = [];

    $scope.characteristicsSetUp = function (initId, objectType, parentObjectId) {
       CampaignCharacteristicService.getByInitiative(initId, parentObjectId).then(function (items) {
            $scope.defaultCharacteristics = items;
        });
    }
};