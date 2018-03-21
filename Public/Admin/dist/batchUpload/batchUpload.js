// @ts-nocheck

var config = {};



var app = new Vue({
    el: '#app',
    data: {
        th: [],
        tr: []
    }
});
var list;
var saveArr = [];
var loadElement;


var loadC;
function read(content) {
    app.tr = [];

    var msg = layer.msg('正在加载基本数据', {
        icon: 16,
        time: 2000 * 2000 //2秒关闭（如果不配置，默认是3秒）
    });

    if (list == null) {
        (function () {

            var url = config.controller + '/getBatchUploadList';

            var fun = function (res) {

                try {
                    res = JSON.parse(res);
                } catch (error) {
                    //转换错误
                    return
                }
                list = res;
                layer.close(msg);
                loadC = layer.msg('正在生成表结构', {
                    icon: 16,
                    time: 2000 * 2000 //2秒关闭（如果不配置，默认是3秒）
                });
                test(content);
            };
            setTimeout(function () {
                $.get(url, fun);
            }, 0);

        }());
    } else {
        layer.close(msg);
        loadC = layer.msg('正在生成表结构', {
            icon: 16,
            time: 2000 * 2000 //2秒关闭（如果不配置，默认是3秒）
        });
        test(content);
    }




}



(function () {
    var eventName = 'dragenter';
    var el = '#upCSV';
    var fun = function (event) {
        var $this = $(this);
        $this.addClass('drag');
        $('#upCSV_title').text('松开鼠标上传');
        $('#sk').addClass('sk-spinner sk-spinner-pulse');
    }

    $(document).on(eventName, el, fun);

}());

(function () {
    var eventName = 'dragleave';
    var el = '#upCSV';
    var fun = function (event) {
        var $this = $(this);
        $('#upCSV_title').text('拖拽文件或点击上传CSV文');
        $this.removeClass('drag');
        $('#sk').removeClass('sk-spinner sk-spinner-pulse');
    }
    $(document).on(eventName, el, fun);

}());
(function () {
    var eventName = 'click';
    var el = '#ok';
    var fun = function (event) {

        var $this = $(this);
        addList();
        return false;
    }
    $(el).on(eventName, fun);

}());


function addList() {
    var load = layer.load(2);
    w(saveArr);

    (function () {

        var url = config.use + '/addAll';
        var obj = {
            table: 'goods',
            add: saveArr

        };
        var fun = function (res) {
            layer.close(load);
            try {
                res = JSON.parse(res);
            } catch (error) {
                //转换错误
                return
            }
            if (res.res > 0) {
                layer.msg("添加成功！");
            } else {
                layer.msg("添加失败！：" + res.msg);
                w(res);
            }

        };
        $.post(url, obj, fun);

    }());


}

(function () {
    var eventName = 'drop';
    var el = '#upCSV';
    var fun = function (event) {
        var $this = $(this);
        $this.removeClass('drag');
        $('#sk').removeClass('sk-spinner sk-spinner-pulse');
    }
    $(document).on(eventName, el, fun);

}());

function test(content) {

    // $.get(config.root + '/d.csv',
    //     function (data) {

    //     },
    //     dataType = 'text');//申明读取的类型是text

    var dataStr = new String(content);//将data存为一个字符串
    var lines = content.split('\n');//将dataStr分割为一行一行存到数组，每一行为一个数组元素

    var arr = [];
    // 颜色，大小（规格），尺码，货号，文字描述


    for (let index = 0; index < lines.length; index++) {
        const element = lines[index];

        if (element != null && element !== '') {
            var a = element.split(',');
            arr.push(a);
        }
    }

    var count = 0;
    $('#loadBox').show();
    loadElement.progress('demo', 0 + '%');

    // $(".dial").knob({
    //     'min': 0,
    //     'max': 100,
    // });
    // $(".dial").val(0).trigger('change');;

    var Interval = setInterval(function () {

        if (count >= arr.length - 1) {
            clearInterval(Interval);
            // $('.thead').css('position', 'fixed');
            $('#loadBox').hide();
            layer.close(loadC);
            layer.msg('数据装载完成~', {
                icon: 1,
            });
            $('#upCSV_title').text('请检查您的表格数据是否正确~');
            $('#ok').show();
            return;
        }
        var element = arr[count + 1];
        var brand_title = element[2];
        var class_title_1 = element[3];
        var class_title_2 = element[4];

        if (list[brand_title] == null) {
            $('#upCSV_title').text('品牌：【' + brand_title + '】不存在！');
            clearInterval(Interval);
            // $('.thead').css('position', 'fixed');
            $('#loadBox').hide();
            layer.close(loadC);
            layer.msg('品牌：【' + brand_title + '】不存在！', {
                anim: 6
            });
            $('#ok').hide();
            return;
        }
        if (list[brand_title].class_1[class_title_1] == null) {
            $('#upCSV_title').text('一级分类：【' + class_title_1 + '】不存在！');
            clearInterval(Interval);
            // $('.thead').css('position', 'fixed');
            $('#loadBox').hide();
            layer.close(loadC);
            layer.msg('一级分类：【' + class_title_1 + '】不存在！', {
                anim: 6
            });
            $('#ok').hide();
            return;
        }
        if (list[brand_title].class_1[class_title_1].class_2[class_title_2] == null) {

            $('#upCSV_title').text('二级分类：【' + class_title_2 + '】不存在！');
            clearInterval(Interval);
            // $('.thead').css('position', 'fixed');
            $('#loadBox').hide();
            layer.close(loadC);
            layer.msg('二级分类：【' + class_title_2 + '】不存在！', {
                anim: 6
            });
            $('#ok').hide();
            return;

        }

        var item = {
            goods_title: element[0],
            money: element[1],
            head_img: '/Public/Upload/goods/' + element[5],
            color: element[6],
            size: element[7],
            size2: element[8],
            goods_no: element[9],
            info_head_img: '/Public/Upload/goods/' + element[10],
            info: info,
            brand_id: list[brand_title].brand_info.brand_id,
            class_id_1: list[brand_title].class_1[class_title_1].class_id,
            class_id_2: list[brand_title].class_1[class_title_1].class_2[class_title_2].class_id
        };

        for (let j = 0; j < element.length; j++) {
            var element_j = element[j];
            switch (j) {
                case 6:
                case 7:
                case 8:
                case 12:
                    element[j] = getArr(element_j);
            }

        }
        //组装详情页：
        var p = '<p>' + element[11] + '</p>';
        var info = p;
        for (var key in element[12]) {
            var img = element[12][key];
            var $img = $('<img/>');
            var url = config.root + '/Public/Upload/goods/' + img;

            url = url.replace(/\s+/g, "");
            $img.attr('src', url);

            var img_text = $img[0].outerHTML;

            info += img_text;
        }

        // item.info = HtmlUtil.htmlEncode(info);
        item.info = info;
        saveArr.push(item);

        count++;
        app.tr.push(element);
        var width = (count / (lines.length - 2)) * 100;
        width = Math.ceil(width);
        loadElement.progress('demo', width + '%');

        //进度


    }, 0);

    app.th = arr[0];

}

function getArr(str, code) {
    if (code == null) {
        code = '|';
    }
    var arr = [];
    if (str.indexOf(code) > 0) {
        arr = str.split(code);
    } else {
        arr = [];
        arr[0] = str;
    }

    return arr
}


layui.use(['upload', 'element'], function () {
    var upload = layui.upload;
    loadElement = layui.element;
    //执行实例
    var uploadInst = upload.render({
        elem: '#upCSV' //绑定元素
        , url: '/upload/' //上传接口
        , accept: 'file' //允许上传的文件类型
        , exts: 'csv'
        , auto: false
        , drag: true
        , done: function (res) {
            //上传完毕回调
        }
        , error: function () {
            //请求异常回调
        }
        , choose: function (obj) {
            //将每次选择的文件追加到文件队列
            var files = obj.pushFile();
            $('#ok').hide();
            $('#upCSV_title').text('正在处理中……');

            //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
            obj.preview(function (index, file, result) {
                // console.log(result); //得到文件base64编码，比如图片

                var base = new Base64();
                //2.解密      
                var result2 = base.decode(result);
                read(result2);
                //这里还可以做一些 append 文件列表 DOM 的操作

                //obj.upload(index, file); //对上传失败的单个文件重新上传，一般在某个事件中使用
                //delete files[index]; //删除列表中对应的文件，一般在某个事件中使用
            });
        }
    });
});


