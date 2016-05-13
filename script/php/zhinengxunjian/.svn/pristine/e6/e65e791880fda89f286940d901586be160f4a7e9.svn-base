/***
 * 
 *处理上传图片或者视频的函数
 */


//按钮onclick提交上传 @form_id表单ID, @file_hidden_id隐藏接收地址ID, @upload_url上传地址
function uploadUserAvatar(form_id, file_hidden_id, upload_url) {
    $(form_id).ajaxSubmit({
        url: upload_url,
        beforeSubmit: checkForm, // pre-submit callback	
        success: complete, // post-submit callback
        dataType: 'json',
        type: 'post'
                //iframe: true	
    });
    function checkForm() {
        //可以在此添加其它判断
        if ($("#" + file_hidden_id + "_file").val() == "") {
            alert('请选择上传图片！');
            return false;
        }
        //wait
        $("#imgshow" + file_hidden_id).html("<span><img src='__PUBLIC__/admin/img/ajax_loader.gif' style='border:0;'></span>");
    }
    function complete(json_data) {
        if (!json_data) {
            alert("上传失败");
            return false;
        }
        //上传回调
        if (json_data.status == 1) {
            $("#" + file_hidden_id).val(json_data.data.img);
            $("#img_file_upload").css("display", "none");
            //移除wait
            $("#imgshow" + file_hidden_id).remove("span");
            $("#imgshow" + file_hidden_id).html("<div'><img src=" + json_data.data.img + " id='photo'></div>");
            alert(json_data.info);
            //if (ie) {
            //    $("#" + file_hidden_id + "_file").replaceWith("<input id=" + file_hidden_id + "_file name=" + file_hidden_id + "_file type='file'>");
            //} else {
            $("#" + file_hidden_id + "_file").attr("value", "");
            // }
        } else {
            alert(json_data.info);
        }
    }
}



//按钮onclick提交上传 @form_id表单ID, @file_hidden_id隐藏接收地址ID, @upload_url上传地址
function uploadvideos(form_id, file_hidden_id, upload_url) {
    $(form_id).ajaxSubmit({
        url: upload_url,
        beforeSubmit: checkForm, // pre-submit callback	
        success: complete, // post-submit callback
        dataType: 'json',
        type: 'post'
                //iframe: true	
    });
    function checkForm() {
        //可以在此添加其它判断
        if ($("#" + file_hidden_id + "_file").val() == "") {
            alert('请选择要上传的视频文件！');
            return false;
        }
        //wait
        $("#" + file_hidden_id + "loading").css("display", "block");
    }
    function complete(json_data) {
        if (!json_data) {
            alert("上传失败");
            return false;
        }
        //上传回调
        if (json_data.status == 1) {
            $("#" + file_hidden_id).val(json_data.data.video);
            $("#videoshow" + file_hidden_id).css("display", "none");
            $("#" + file_hidden_id + "_file_upload").css("display", "none");
            //移除wait
            $("#" + file_hidden_id + "loading").css("display", "none");
            //显示播放器
            videoshow(json_data.data.video);
            $("#videoshow").css("display", "block");
            alert(json_data.info);
            //if (ie) {
            // $("#" + file_hidden_id + "_file").replaceWith("<input id=" + file_hidden_id + "_file name=" + file_hidden_id + "_file type='file'>");
            //} else {
            $("#" + file_hidden_id + "_file").attr("value", "");
            //}
        } else {
            alert(json_data.info);
        }
    }
    /**
     * 显示播放器
     */
    function videoshow($url) {
        var flashvars = {
            f: $url,
            c: 0,
            b: 1
        };
        var params = {bgcolor: '#FFF', allowFullScreen: true, allowScriptAccess: 'always'};
        CKobject.embedSWF('__PUBLIC__/admin/ckplayer6.3/ckplayer/ckplayer.swf', 'a1', 'ckplayer_a1', '600', '400', flashvars, params);
        /*
         CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
         下面三行是调用html5播放器用到的
         */
        var video = [$url];
        var support = ['iPad', 'iPhone', 'ios', 'android+false', 'msie10+false'];
        CKobject.embedHTML5('video', 'ckplayer_a1', 600, 400, video, flashvars, support);
    }
}
function exist_hidden_trouble(obj) {
    //是否存在隐患
    var value = obj.value;
    if (value == "yes") {
        //显示上传图片和视频的表单
        $("#exist_hidden_trouble").css("display", "block");
    } else {
        //隐藏上传图片和视频的表单
        $("#exist_hidden_trouble").css("display", "none");
    }
}