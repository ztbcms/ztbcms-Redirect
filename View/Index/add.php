
<Admintemplate file="Common/Head"/>
<body class="J_scroll_fixed">
<div class="wrap jj">
    <div class="nav">
        <ul class="cc">
            <li class="current">添加地址</li>
        </ul>
    </div>
    <!--====================用户编辑开始====================-->
    <form class="J_ajaxForm" id="J_bymobile_form" action="{:U("Index/addUrl")}" method="post">
    <div class="h_a">地址信息</div>
    <div class="table_full">
        <table width="100%">
            <col class="th" />
            <col/>
            <tr>
                <th>实际地址</th>
                <td><input name="url" type="text" class="input length_5 required" value="{$data.url}">
                    <span id="J_reg_tip_nickname" role="tooltip"></span></td>
            </tr>
        </table>
    </div>
    <div class="">
        <div class="btn_wrap_pd">
            <button type="submit" class="btn btn_submit  J_ajax_submit_btn">提交</button>
        </div>
    </div>
    </form>
</div>
<script language="Javascript" type="text/javascript" src="{$config_siteurl}statics/js/edit_area/edit_area_full.js"></script>
<script type="text/jscript">
Wind.use('validate','ajaxForm', function(){
	//表单js验证开始
	$("#J_bymobile_form").validate({
		//当未通过验证的元素获得焦点时,并移除错误提示
		focusCleanup:true,
		//错误信息的显示位置
		errorPlacement:function(error, element){
			//错误提示容器
			$('#J_reg_tip_'+ element[0].name).html(error);
		},
		//获得焦点时不验证
		focusInvalid : false,
		onkeyup: false,
		//设置验证规则
		rules:{
			url:{
				required:true,//验证条件：必填
				byteRangeLength: [3,15]
			}
		},
		//设置错误信息
		messages:{
			url:{
				required: "请填写用户名",
				byteRangeLength: "用户名必须在3-15个字符之间(一个中文字算2个字符)"
			}
		}
	});
});
</script>
</body>
</html>
