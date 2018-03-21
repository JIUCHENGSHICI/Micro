// @ts-nocheck
/**
 * 分类js
 */
var classTool = (function () {

    var obj = {


        add: function () {

        }


    }

    return obj;

}());


(function () {
    var eventName = 'mouseover';
    var el = '.class-box li';
    var fun = function (event) {
        var $this = $(this);
        var offset = $this.offset();
        var $tool = $this.find('.tool');
        $tool.show();
        $tool.height($this.innerHeight());
        var level = $tool.attr('data-level');
        var left = level == 1 ? (offset.left - 100) : (offset.left + $this.innerWidth());
        $tool.offset({
            left: left,
            top: offset.top,
        });
    }

    $(document).on(eventName, el, fun);

}());
(function () {
    var eventName = 'mouseout';
    var el = '.class-box li';
    var fun = function (event) {
        var $this = $(this);
        var $tool = $this.find('.tool');
        $tool.hide();
    }
    $(document).on(eventName, el, fun);
}());


(function () {
    var eventName = 'click';
    var el = '.class-box li';
    var fun = function (event) {
        var $this = $(this);
        $('.class-box li').removeClass('active');
        $this.addClass('active');
    }

    $(document).on(eventName, el, fun);

}());


var config = (function (params) {
    var obj = {
        ip: '127.0.0.1',
        route: '',
    }
    return obj;

}());


var class1App = new Vue({
    el: '#class1App',

    data: {
        new_content: '内容',
        server_content: '服务器的内容',
        items: [],
        dels: [],
        activeClass_id: null
    },
    methods: {
        edit: function (class_id, index) {
            var _this = this;

            //保存类别字段
            layer.prompt({
                value: _this.items[index].class_title
            }, function (value, prompt, elem) {

                w('新改的值是：' + value);

                _this.items[index].class_title = value;

                var save = {
                    table: 'class',
                    id: class_id,
                    save: {
                        'class_title': value
                    }
                };
                _this.saveField(save, false);
                layer.close(prompt);
            });



        },
        save: function (save) {
            var _this = this;

            if (save == null) {
                return;

            }

            (function () {

                var url = config.ip + '/save';
                var obj = save;
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        layer.msg('修改成功！正在异步更新……');
                        _this.getList();
                    } else {
                        layer.msg('修改失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();






        },
        add: function () {
            var _this = this;


            var random = Math.floor(Math.random() * 10);

            var content = this.new_content + '【' + random + '】';

            var add = {
                table: 'class',
                add: {
                    'test_content': content,
                    money: random + 0.99
                }
            };


            (function () {

                var url = config.ip + '/add';
                var obj = add;
                var fun = function (res) {


                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        layer.msg('修改成功！正在异步更新……');
                        _this.getList();
                    } else {
                        layer.msg('修改失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();

        },
        getList: function () {
            //1

            var _this = this;
            _this.items = [];

            var get = {
                table: 'class',
                where: {
                    level: 1,
                    brand_id: config.brand_id
                },
                order: 'sort'
            };

            (function () {

                var url = config.ip + '/getList';
                var obj = get;

                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        _this.items = res.msg;
                        // layer.msg('刷新成功！');

                        var activeClass_id;

                        if (_this.activeClass_id) {
                            // 有
                            activeClass_id = _this.activeClass_id;
                        } else {
                            // 没有
                            activeClass_id = _this.items[0].class_id;
                        }

                        class1App.showClass2(activeClass_id);


                    }

                    if (res.res < 0) {
                        layer.msg('刷新失败：' + res.msg, {
                            anim: 6
                        });
                    }


                };
                $.get(url, obj, fun);

            })();

        },
        del: function (id) {

            var _this = this;

            (function () {

                var url = config.ip + '/del';
                var obj = {
                    table: 'class',
                    id: id,
                    link_del: [
                        {
                            table: 'class',
                            where: {
                                super_id: id
                            }
                        }
                    ]
                };

                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }

                    if (res.res > 0) {
                        layer.msg('删除成功！');

                        _this.getList();
                    }
                    if (res.res < 0) {
                        layer.msg('删除失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            }());

        },
        delAll: function () {

            var _this = this;
            var arr = this.dels;

            (function () {

                var url = config.ip + '/delAll';;
                var obj = {
                    table: 'class',
                    id: arr
                };
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    console.log(res);

                    if (res.res > 0) {
                        layer.msg('删除成功！');
                        _this.getList();


                    } else {
                        layer.msg('删除失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();

        },
        getOne: function (id) {

            var _this = this;

            var get = {
                table: 'class',
                id: id,
                field: 'test_content,add_time',
            };



            (function () {

                var url = config.ip + '/getOne';;
                var obj = get;
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }

                    if (res.res > 0) {

                        layer.msg('从服务器加载到你点击的内容是：' + res.msg.test_content + '<br/>添加时间：【' + res.msg.add_time + '】');

                    } else {
                        layer.msg('从服务加载失败', {
                            anim: 6
                        });

                    }

                };
                $.get(url, obj, fun);

            })();

        },
        //第一个是要保存的数据
        //第二个是是否刷新整个列表，可空，默认是true
        saveField: function (save, update) {

            if (update == null) {
                update = true;
            }

            //保存一个数据的字段
            if (save == null) {
                return;
            }
            var _this = this;

            (function () {

                var url = config.ip + '/saveField';;
                var obj = save;
                var fun = function (res) {


                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        return
                    }
                    console.log(res);

                    if (res.res > 0) {
                        layer.msg('修改成功');
                        if (update !== null && update == true) {
                            _this.getList();
                        } else {

                        }
                    } else {
                        layer.msg('修改失败', {
                            anim: 6
                        });
                    }

                };
                $.post(url, obj, fun);

            })();
        },
        showClass2: function (super_id) {
            this.activeClass_id = super_id;
            class2App.getList(super_id);
        }
    },

});

var class2App = new Vue({
    el: '#class2App',
    data: {
        new_content: '内容',
        server_content: '服务器的内容',
        items: [],
        dels: []
    },
    methods: {
        edit: function (class_id, index) {
            var _this = this;

            //保存类别字段
            w(class_id);
            layer.prompt({
                value: _this.items[index].class_title
            }, function (value, prompt, elem) {

                w('新改的值是：' + value);

                _this.items[index].class_title = value;

                var save = {
                    table: 'class',
                    id: class_id,
                    save: {
                        'class_title': value
                    }
                };
                _this.saveField(save, false);
                layer.close(prompt);
            });



        },
        save: function (save) {
            var _this = this;

            if (save == null) {
                return;
            }

            (function () {

                var url = config.ip + '/save';
                var obj = save;
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        layer.msg('修改成功！正在异步更新……');
                        _this.getList();
                    } else {
                        layer.msg('修改失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();






        },
        add: function () {
            var _this = this;


            var random = Math.floor(Math.random() * 10);

            var content = this.new_content + '【' + random + '】';

            var add = {
                table: 'class',
                add: {
                    'test_content': content,
                    money: random + 0.99
                }
            };


            (function () {

                var url = config.ip + '/add';
                var obj = add;
                var fun = function (res) {


                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        layer.msg('修改成功！正在异步更新……');
                        _this.getList();
                    } else {
                        layer.msg('修改失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();

        },
        getList: function (super_id) {

            if (super_id == null) {
                super_id = class1App.activeClass_id;
            }
            //获得列表
            var _this = this;
            _this.items = [];
            var get = {
                table: 'class',
                key: super_id,
                key_where: 'super_id'

            };

            (function () {

                var url = config.ip + '/getListWhere';
                var obj = get;
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        _this.items = res.msg;
                        // layer.msg('刷新成功！');

                    } else {
                        _this.items = [];

                        // layer.msg('并没有二级分类！' + res.msg, {
                        //     // anim: 6
                        // });
                    }

                };
                $.get(url, obj, fun);

            })();

        },
        del: function (id) {

            var _this = this;

            (function () {

                var url = config.ip + '/del';;
                var obj = {

                    table: 'class',
                    id: id,

                };
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    if (res.res > 0) {
                        layer.msg('删除成功！');
                        _this.getList();
                    } else {
                        layer.msg('删除失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();

        },
        delAll: function () {

            var _this = this;
            var arr = this.dels;

            (function () {

                var url = config.ip + '/delAll';;
                var obj = {
                    table: 'class',
                    id: arr
                };
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }
                    console.log(res);

                    if (res.res > 0) {
                        layer.msg('删除成功！');
                        _this.getList();


                    } else {
                        layer.msg('删除失败：' + res.msg, {
                            anim: 6
                        });
                    }
                };
                $.post(url, obj, fun);

            })();

        },
        getOne: function (id) {

            var _this = this;

            var get = {
                table: 'class',
                id: id,
                field: 'test_content,add_time',
            };



            (function () {

                var url = config.ip + '/getOne';;
                var obj = get;
                var fun = function (res) {

                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        layer.msg('异步接口出错！', {
                            anim: 6
                        });
                        return
                    }

                    if (res.res > 0) {

                        layer.msg('从服务器加载到你点击的内容是：' + res.msg.test_content + '<br/>添加时间：【' + res.msg.add_time + '】');

                    } else {
                        layer.msg('从服务加载失败', {
                            anim: 6
                        });

                    }

                };
                $.get(url, obj, fun);

            })();

        },
        //第一个是要保存的数据
        //第二个是是否刷新整个列表，可空，默认是true
        saveField: function (save, update) {

            if (update == null) {
                update = true;
            }

            //保存一个数据的字段
            if (save == null) {
                return;
            }
            var _this = this;

            (function () {

                var url = config.ip + '/saveField';
                var obj = save;
                var fun = function (res) {


                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        //转换错误
                        return
                    }
                    console.log(res);

                    if (res.res > 0) {
                        layer.msg('修改成功');
                        if (update !== null && update == true) {
                            _this.getList();
                        } else {

                        }
                    } else {
                        layer.msg('修改失败', {
                            anim: 6
                        });
                    }

                };
                $.post(url, obj, fun);

            })();





        }
    },

});