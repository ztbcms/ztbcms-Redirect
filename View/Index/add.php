<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>添加跳转链接</h3>
            <el-row>
                <el-col :span="20">
                    <div class="grid-content ">
                        <el-form ref="form" :model="form" label-width="80px">
                            <el-form-item label="跳转链接">
                                <el-input placeholder="请输入链接" v-model="form.url"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">添加</el-button>
                                <el-button @click="onCancel">取消</el-button>
                            </el-form-item>
                        </el-form>
                    </div>
                </el-col>
                <el-col :span="16"><div class="grid-content "></div></el-col>
            </el-row>


        </el-card>
    </div>

    <style>

    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    form: {
                        url: '',
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    onSubmit: function(){
                        if(this.form.url.trim()==''){
                            this.$message.error('链接不能为空');
                            return ;
                        }
                        var that=this;
                        $.ajax({
                            url: '{:U("Redirect/Index/addUrl")}',
                            data: {
                                url: that.form.url
                            },
                            type: 'post',
                            dataType: 'json',
                            success(res) {
                                if (!res.status) {
                                    that.$message.error(res.msg);
                                } else {
                                    that.$message.success(res.msg);
                                }
                            }
                        })
                    },
                    onCancel: function(){
                        this.$message.error('已取消');
                    },
                },
                mounted: function () {

                },

            })
        })
    </script>
</block>
