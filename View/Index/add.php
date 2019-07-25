<Admintemplate file="Common/Head"/>
<style>
	.error_msg{
		color:red;
		font-size: 1.5rem;
		font-weight: bolder;
	}
</style>
<body class="J_scroll_fixed">
<div class="wrap jj" id="app">
    <div class="nav">
        <ul class="cc">
            <li class="current">添加地址</li>
        </ul>
    </div>
    <!--====================用户编辑开始====================-->
    <div class="h_a">地址信息</div>
    <div class="table_full">
        <table width="100%">
            <col class="th"/>
            <col/>
            <tr>
                <th>实际地址</th>
                <td><input type="text" class="input" value="{$data.url}" v-model.trim="url">
                </td>
            </tr>
        </table>
    </div>
    <div class="">
        <div class="btn_wrap_pd">
            <button class="btn btn_submit  J_ajax_submit_btn" @click="addItem">添加</button>
            <span class="error_msg" role="tooltip">{{msg}}</span>
        </div>
    </div>
</div>
<!-- <script src="{$config_siteurl}statics/js/common.js?v"></script> -->
<!-- <script language="Javascript" type="text/javascript"
        src="{$config_siteurl}statics/js/edit_area/edit_area_full.js"></script> -->
<script type="text/javascript">
var vm=new Vue({
	el:"#app",
	data:{
		msg:"",
		url:"",
	},
	methods: {
		addItem:function(){
			this.msg=''
			if(this.url.trim()==''){
				this.msg="地址不能为空！"
				return ;
			}
			$('.btn_submit').attr('disabled',true);
			var that=this;
			var add=$.ajax({
				url:'{:U("Redirect/Index/addUrl")}',
				data:{
					url:that.url
				},
				type:'post',
				dataType:'json',
				success(res){
					if(!res.status){
						that.msg=res.msg;
					}else{
						window.location.href=res.url
					}
				}
			})
			$.when(add).done(function(){
				$('.btn_submit').attr('disabled',false);
			})
		}
	},
})
</script>
</body>
</html>
