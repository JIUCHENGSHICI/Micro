// @ts-nocheck

// <script src="__VENDOR__/jquery/jquery.js"></script>
//     <script src="__VENDOR__/layer/layer.js"></script>
//     <script src="__VENDOR__/vue/vue.js"></script>
//     <script src="https://cdn.bootcss.com/popper.js/1.12.5/umd/popper.min.js"></script>
//     <script src="https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

require(["vue"], function (Vue) {

    var testApp = new Vue({
        el: '#testApp',
        data: {
            new_content: '内容',
            server_content: '服务器的内容',
            items: [],
            dels: []
        },
        methods: {
            save: function () {
                var _this = this;

                var save = {
                    tableName: 'Test',
                    id: '1',
                    save: {
                        'test_content': this.new_content
                    }
                };

                (function () {

                    var url = 'admin/Index/save';
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
                    tableName: 'Test',
                    add: {
                        'test_content': content,
                        money: random + 0.99
                    }
                };


                (function () {

                    var url = 'admin/Index/add';
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

                var _this = this;
                var get = {
                    tableName: 'Test',
                    // id: '1',
                    // field: 'test_content',
                };

                (function () {

                    var url = 'admin/Index/getList';
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
                            layer.msg('刷新成功！');

                        } else {
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

                    var url = 'admin/Index/del';
                    var obj = {

                        tableName: 'Test',
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

                    var url = 'admin/Index/delAll';
                    var obj = {
                        tableName: 'Test',
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
                    tableName: 'Test',
                    id: id,
                    field: 'test_content,add_time',
                };



                (function () {

                    var url = 'admin/Index/getOne';
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
            saveField: function (id) {
                //保存一个数据的字段

                var _this = this;


                var random = Math.floor(Math.random() * 10);

                var save = {
                    tableName: 'Test',
                    id: id,
                    save: {
                        'test_content': '修改后的数据' + random
                    }
                };

                (function () {

                    var url = 'admin/Index/saveField';
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
                            _this.getList();
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
    testApp.getList();

});


define(function () {


    require(["Public/dist/component/index/tool"], function (tool) {
        console.log(tool.init());
    });


    require(["test"], function (config) {

        console.log(config.init());

    });

});







// var app = new Vue({
//     el: '',
//     data: {

//     },
//     method: {

//     }
// });
// var a = 1;
// a = 'asd';s


// function test() {
//     var aaa = {
//         tableName: 'Test',
//         id: '1',
//         save: {
//             'test_content': '这是个测试内容'
//         }
//     };
//     save(aaa);
// }

// function save(save) {
//     $.post('__MODULE__/Index/save', save, function (res) {
//         try {
//             res = JSON.parse(res);
//         } catch (error) {
//             layer.msg('异步接口出错！');
//         }


//         if (res.res > 0) {
//             layer.msg('修改成功！');
//         } else {
//             layer.msg('修改失败：' + res.msg);
//         }


//     });

// }