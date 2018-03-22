$(function(){


    function timeFormat(time){
        var t = new Date(time);
        var tpl = "{year}/{mouth}/{date} {hours}:{minutes}";
        var timeHash = {
            year:    t.getFullYear(),
            mouth:   t.getMonth() + 1 < 10 ? "0" + (t.getMonth() + 1) : t.getMonth() + 1,
            date:    t.getDate() < 10 ? "0"+ t.getDate() : t.getDate(),
            hours:   t.getHours() < 10 ? "0" + t.getHours(): t.getHours(),
            minutes: t.getMinutes() < 10 ? "0" + t.getMinutes() : t.getMinutes(),
            seconds: t.getSeconds() < 10 ? "0" + t.getSeconds() : t.getSeconds()
        }

        tpl = tpl.replace(/\{(\w+)\}/g, function(a,b){
            return timeHash[b];
        })
        return tpl;
    }


    var listApi = "http://www.acfun.cn/video/getPage.aspx";
    var vedios = null;
    function getHtml(res){
        res.timeFormat = timeFormat;
        var tpl = [
            "<div style='height: 260px; overflow: scroll;'>",
                "<table id='J_vedio_list'>",
                    "<thead>",
                        "<tr>",
                            "<th><input type=\"checkbox\" data-op=\"selectAll\"></th>",
                            "<th>视频状态</th>",
                            "<th>视频id</th>",
                            "<th>文件名称</th>",
                            "<th>上传于</th>",
                        "</tr>",
                    "</thead>",
                    "<tbody id='J_vedio_list_body'>",
                        "{{~it.videos:item:index}}",
                            "<tr id='J_{{=item.id}}'>",
                                "<td><input class='J_val_item' value='{{=JSON.stringify(item)}}' type='hidden' />{{? item.status==6 || item.status ==5 }}<div class='dis-choose'></div>{{??}}<input  type=\"checkbox\" data-op=\"selectItem\"><input class='J_v_id' value='{{=item.id}}' type='hidden' />{{?}}</td>",
                                "<td>",
                                    "<em class='{{? item.sourceStatus==-3}}t-code-fail{{?? item.sourceStatus == 2}}t-code-encoding{{??}}t-code-success{{?}}'>",
                                        "{{? item.sourceStatus==-3}}转码失败{{?? item.sourceStatus==2}}转码中{{??}}转码成功{{?}}",
                                    "</em>",
                                "</td>",
                                "<td>{{=item.id}}</td>",
                                "<td class='file-name-m'>{{? item.fileName}}{{=item.fileName}}{{?}}</td>",
                                "<td>{{= it.timeFormat(item.createTime) }}</td>",
                            "</tr>",
                        "{{~}}",
                    "</body>",
                "</table>",
            "</div>",
            "<p class='post-count'>已选中<span id='J_count'>0</span>个文件</p><p class='v-error' id='J_v_error' style='display: none;' >视频源信息错误，请选择其他视频</p>"
        ].join('');
        if(!res.success) return;
        var tempFn = doT.template(tpl);
        return tempFn(res);
    }

    function getVedioList(url, opt){
        return $.ajax({
            url: url,
            type: "GET",
            data: {
                _: new Date().getTime(),
                order: 4,
                sourceType: 'zhuzhan'
            },
            beforeSend: function(){

            }
        }).done(function(res){
            var d;
            if(res.videos == 0){
                d = dialog(opt.dF);
                d.width(418).height(250).showModal();
            }else{
                vedios = res.videos
                d = dialog(opt.dS);
                d.width(600).showModal();
                d.content(getHtml(res));

                var dialogDom = $(dialog.getCurrent().node);
                var selectAllBtn =  dialogDom.find("[data-op='selectAll']");
                var selectItemBtn = dialogDom.find("[data-op='selectItem']");
                var sbBtn =         dialogDom.find("[i-id='J_sub_btn_vedio_list']");

                var checkIsSelected = function(){
                    var isSelected = false;
                    selectItemBtn.each(function(){
                        if($(this).prop("checked")){
                            isSelected = true;
                            return false;
                        }
                    });
                    isSelected ? sbBtn.prop("disabled", false) : sbBtn.prop("disabled", true);
                    
                    return isSelected;
                }

                var checkSelectedLength = function(){
                    var i = $("#J_vedio_list_body").find("input:checked").length;
                    $("#J_count").text(i);
                }

                var checkIsAllSelected = function(){
                    var isAllSelected = true;
                    selectItemBtn.each(function(){
                        if(!$(this).prop("checked")){
                            isAllSelected = false;
                            return false;
                        }
                    });
                    
                    return isAllSelected;
                }


                selectAllBtn.on("change", function(){
                    var _this = $(this);
                    if(_this.prop("checked")){
                        selectItemBtn.each(function(){
                            var _item = $(this);
                            if(!_item.prop("disabled") ) _item.prop("checked", true).parents("tr").addClass("active");
                        });
                    }else{
                        selectItemBtn.each(function(){
                            var _item = $(this);
                            if(!_item.prop("disabled") ) _item.prop("checked", false).parents("tr").removeClass("active");
                        });
                    }
                    checkIsSelected();
                    checkSelectedLength();

                });

                dialogDom.on("change", "[data-op='selectItem']", function(){
                    var tr = $(this).parents("tr");
                    if(this.checked && checkIsAllSelected()){
                        selectAllBtn.prop("checked", true);
                    }else{
                        selectAllBtn.prop("checked", false);
                        tr.removeClass("active");
                    }
                    if(this.checked){
                        tr.addClass("active")
                    }else{
                        tr.removeClass("active");
                    }
                    checkIsSelected();
                    checkIsAllSelected();
                    checkSelectedLength();

                })

            }
        })
        .fail(function(err){
            $.info('error::' + '服务器出问题啦，请稍后重试');
        });
    }


    var itemTpl = [
        //'{{~ it.list: item:index}}',
            '<li class="cfix thread" draggable="true">',
                '<div class="phand fl">',
                    '<a class="phand-up"></a>',
                    '<div class="pnum ">P</div>',
                    '<a class="phand-down"></a>',
                '</div>',
                '<div class="pinfo fl">',
                    '<div class="cfix ptittlebox">',
                        '<input type="text" value="" class="ptitles fl" placeholder="填写分P标题">',
                        '<span class="num fr">50</span>',
                    '</div>',
                    '<p class="ptext" title="视频id{{=it.id}}">视频id:{{=it.id}}</p>',
                    '<div class="pbox">',
                        '<div class="pgray cfix"><span class="psize">转码成功</span></div>',
                        '<div class="pgreen" style="width: 100%;" ></div>',
                    '</div>',
                '</div>',
                '<div class="pmovement fl cfix">',
                    '<i class="pstatus fl icon-sts-4"></i>',
                    '<i class="pmoves fl icon-del"></i>',
                '</div>',
                '<div class="dividers"></div>',
            '</li>',
         // '{{~}}'   
    ].join('');



    function followTip(ele,text,num){
        var offset = $(ele).offset(),
            $elem =$(ele),
            type = 'type-'+num,
            dom,
            offHeight = ele.offsetHeight+31;
        if(num==='five')
            offHeight+=-27;
        if(num){
            dom = '<div class="follow-tips ' + type + '"' +
                ' style="margin-top:-'+offHeight+'px">' +
                '<i class="icon-am"></i>'+
                text +'</div>';
        }
        if(!$elem.has('.follow-tips').length){
            $elem.append(dom);
        }
    }

    function checkWaterPaper(){
        var waterIcon = $("input[name='J_water_icon']");
        if(waterIcon.length == 0) return true;
        var waterIconVal = waterIcon.val();
        var toggleBtn = document.querySelector(".J_toggle_btn");
        if(!waterIconVal) {
            followTip(toggleBtn, "请选择水印", 100);
            return false;
        }else{
            //$(toggleBtn).find(".follow-tips").remove();
            return true;
        }
    }

    //上传业务JS是个单独闭包，未暴露接口，实在没办法，才暴露这么个全局变量，咱也不能大改吧，大改风险太大。
    window.ACPERSONINFOSELECEDITEM = {};    //防重复
    function setVedioSuccss(items){
        // if(!checkWaterPaper()){
        //     return;
        // }
        var usedVedios = [];
        items.forEach(function(item, index){
            var _status = item.status;
            if(!ACPERSONINFOSELECEDITEM[item.id]){
               usedVedios.push(item); 
               ACPERSONINFOSELECEDITEM[item.id] = item;
            }
        });
        $("#J_bridge").val(JSON.stringify(usedVedios)).trigger("change");
        
    }



    function checkVedio(ids, items){
        var data = {videoIds: ids.join(',')};
        $.ajax({
            url: "http://www.acfun.cn/video/checkVideos.aspx",
            type: "GET",
            data: data,
            beforeSend: function(){

            }
        }).done(function(res){
            if(!res.success){
               //提示错误信息
               $("#J_v_error").show();
               for(var i = 0; i < res.videoIds.length; i++){
                   $("#J_"+res.videoIds[i]).css("background-color", "#FFE4E6");
               }
               
            }else{
                //视频校验通过
                setVedioSuccss(items);
            }
        }).fail(function(err){
            //setVedioSuccss(items);
            $.info('error::' + "服务器出错，请稍后重试");
        });
    }

    

    $("#J_cloud_files").on("click", function(){
        var opt = {
            dS:  {
                title: '选择未投稿视频(仅支持添加AcFun视频源、且为转码中或转码成功状态的视频源）',
                content: 'table content',
                okValue: '提交',
                skin: "vedio-list-dialog",
                id: 'J_vedio_list',
                button: [
                    {
                        value: '提交',
                        callback: function () {
                            var trs = $("#J_vedio_list tbody tr");
                            var ids = [];
                            var items = [];
                            trs.each(function(item, index){
                                var _this = $(this);
                                var isCheck = _this.find("input[type='checkbox']").is(':checked');
                                if(isCheck){
                                    var id = _this.find(".J_v_id").val();
                                    var itemVal = JSON.parse(_this.find(".J_val_item").val());
                                    ids.push(id);
                                    items.push(itemVal);
                                }
                            });
                            
                            checkVedio(ids, items);
                        },
                        id: "J_sub_btn_vedio_list",
                        autofocus: false,
                        disabled: true
                    },
                    {
                        value: '取消'
                    }
                ],
                fixed: true
            },
            dF: {
                title: ' ',
                skin: "no-vedio-list-dialog",
                content: "<div><img src='/dotnet/date/style/image/dialog-ac-person.png' width=109 height=150 /><p>您近期没有未投稿的视频哦<br>请从本地上传文件</p></div>",
                fixed: true
            }
        }
        getVedioList(listApi, opt);
    })

});