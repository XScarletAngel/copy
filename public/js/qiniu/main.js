/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */


$(function() {
    var uploader = Qiniu.uploader({
        // 上传设置
        // domain: 'xkht-img',            // bucket域名，下载资源时用到，必需
        // domain: 'http://up-z1.qiniu.com',
        domain: "http://ooylo9ron.bkt.clouddn.com",
        runtimes: 'html5,flash,html4',  // 上传模式，依次退化
        browse_button: 'file',          // 上传选择的点选按钮，必需。被点击的东西的id
        uptoken_url: $('#uptoken_url').val(),   // 获取上传凭证的地址
        get_new_uptoken: true,                 // 设置上传文件的时候是否每次都重新获取新的uptoken
        multi_selection: false,                 // 设置多选文件

        // 拖拽设置
        dragdrop: true,                 // 开启可拖曳上传
        container: 'file_container',    // 上传区域DOM ID，默认是browser_button的父元素。按钮外部的div的id
        drop_element: 'file_container', // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传

        flash_swf_url: './Moxie.swf', // TODO 引入flash，相对路径。引用后无效果?

        // 文件限制
        chunk_size: '4mb',              // 分块上传时，每块的体积
        filters : {
            max_file_size : 100000000,  // 最大文件体积限制
            prevent_duplicates: true,
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"}, // 限定jpg,gif,png后缀上传
                {title : "Video files", extensions : "rm,rmvb,avi,mp4"}, // 视频
                {title : "soft files",  extensions : "dmg"}, // 安装包
            ]
        },
        // downtoken_url: '/downtoken',     // 私有空间时使用
        unique_names: false,                // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
        save_key: false,                    // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
        // x_vars: {
        //     'id': '1234',
        //     'time': function(up, file) {
        //         var time = (new Date()).getTime();
        //         // do something with 'time'
        //         return time;
        //     },
        // },
        auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
        log_level: 5,                       // 应该是日志级别，qiniu.js中有定义logger MUTE: 0, FATA: 1, ERROR: 2, WARN: 3, INFO: 4, DEBUG: 5, TRACE: 6, level: 0
        init: {
            'FilesAdded': function(up, files) {
                /*
                $('#juhua').show();
                plupload.each(files, function(file) {
                    var progress = new FileProgress(file, 'fsUploadProgress');
                    progress.setStatus("等待中...");
                    progress.bindUploadCancel(up);
                });
                */
            },
            'BeforeUpload': function(up, file) {
                $('#progress-msg').html('');
                $('#caozuo').hide();
                /*
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                if (up.runtime === 'html5' && chunk_size) {
                    progress.setChunkProgess(chunk_size);
                }
                 */
            },

            // 上传过程这个函数会不断的执行,直到上传完成
            'UploadProgress': function(up, file) {
                //console.log(up);
                //console.log(file);
                //var progress = new FileProgress(file, 'fsUploadProgress');
                //var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                //progress.setProgress(file.percent + "%", file.speed, chunk_size);
                //console.log(file.percent + "%");
                if (file.percent > 0 && file.percent <= 99) {
                    $('#progress').css('width', file.percent + '%').html(file.percent + '%');
                    $('#progress-msg').html('主人,正在拼命的加载中...');
                } else {
                    $('#progress-msg').html('主人,马上就要上传好了,正在做垂死挣扎中...');
                }
                /*
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                progress.setProgress(file.percent + "%", file.speed, chunk_size);
                */
            },
            'UploadComplete': function() {
                $('#progress').css('width', '100%').html('100%');
                $('#progress-msg').html('上传成功');
                $('#caozuo').show();
                /*
                var res = $.parseJSON(info);
                var domain = up.getOption('domain');
                url = domain + encodeURI(res.key);
                */
            },
            'FileUploaded': function(up, file, info) {
                var res = $.parseJSON(info);
                $('#video-url').val(res.key);
                /*
                var progress = new FileProgress(file, 'fsUploadProgress');
                progress.setComplete(up, info);

                //上传完成时将url放入隐藏的input[name=file]
                var res = $.parseJSON(info);
                var domain = up.getOption('domain');
                url = domain + encodeURI(res.key);
                $('input[name=files]').val(url);
                $('#juhua').hide();
                $('.shuoming').html('文件已上传！');
                */
            },
            'Error': function(up, err, errTip) {
                $('#progress-msg').html(err.message);
                swal("错误", err.message, "error");
                /*
                $('table').show();
                var progress = new FileProgress(err.file, 'fsUploadProgress');
                progress.setError();
                progress.setStatus(errTip);
                */
            },
            'Key': function(up, file) {
                // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                // 该配置必须要在 unique_names : false，save_key: false时才生效

                var fileName = file['name'];
                var iLastIndex = fileName.lastIndexOf('.'); //得到后缀
                var ext = fileName.substring(iLastIndex + 1);  //得到最后一个点的坐标
                var prename= fileName.substring(0, iLastIndex);  //得到最后一个点之前的字符串

                var time = Date.parse(new Date())/1000;
                $("input[name='ftype']").val(prename);

                return time + '.' + ext;
            }
        }
    });
});
