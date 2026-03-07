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