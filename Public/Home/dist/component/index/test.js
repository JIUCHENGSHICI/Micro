// @ts-nocheck
define(function () {


    var config = function (params) {

        var obj = {
            init: function (params) {
                return 'my is obj.init'
            }
        }

        return obj;
    }();
    return config;


});

