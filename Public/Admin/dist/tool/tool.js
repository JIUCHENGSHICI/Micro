var tool = function () {

    var obj = {
        init: function () {

            window['l'] = console.log;
            window['w'] = console.warn;
            window['e'] = console.error;
            window['i'] = console.info;
        }

    }

    obj.init();

    return obj;



}();