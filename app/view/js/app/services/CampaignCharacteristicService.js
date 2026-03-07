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