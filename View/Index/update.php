<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>修改链接</h3>
            <el-row>
                <el-col :span="8">
                    <div class="grid-content ">
                        <el-form ref="form" label-width="80px">
                            <el-form-item label="外部链接">
                                <el-input v-model="form.url"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="onSubmit">修改</el-button>
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
                        url: '{$url}',
                        id:'{$id}'
                    }
                },
                watch: {},
                filters: {},
                methods: {
                    onSubmit: function(){
                        if (this.form.url.trim() == '') {
                            this.msg = "地址不能为空！"
                            return;
                        }
                        var that = this;
                        $.ajax({
                            url: '{:U("Redirect/Index/updateUrl")}',
                            data: {
                                url: that.form.url,
                                id: that.form.id
                            },
                            type: 'post',
                            dataType: 'json',
                            success(res) {
                                if (!res.status) {
                                    that.$message.error(res.msg);
                                }
                                else {
                                    that.$message.success('修改成功');
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
