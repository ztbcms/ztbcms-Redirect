<extend name="../../Admin/View/Common/base_layout"/>
<block name="content">
    <div id="app" style="padding: 8px;background: white;" v-cloak>
        <h4>地址列表</h4>
        <hr>
        <div class="search_type cc mb10">
            实际地址：<input type="text" name="url" class="input" v-model="where.message" placeholder="支持模糊搜索">
            时间：
            <input type="text" name="start_date" class="input datepicker">
            -
            <input type="text" name="end_date" class="input datepicker">
            <button class="btn btn-primary" style="margin-left: 8px;" @click="search">搜索</button>
            <button class="btn btn-primary" style="margin-left: 8px;" @click="add">新增</button>
        </div>
        <hr>
        <div class="table_list">
            <table class="table table-bordered table-hover">
                <thead>
                <tr style="background: gainsboro;">
                    <td align="center" width="80">ID</td>
                    <td align="center">实际地址</td>
                    <td align="center">短链接地址</td>
                    <td align="center">访问频率</td>
                    <td align="center" width="160">发布时间</td>
                    <td align="center" width="160">操作</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in urls">
                    <td align="center" class="id">{{item.id}}</td>
                    <td align="center">{{item.url}}</td>
                    <td align="center">
                        <div style="overflow-wrap: break-word; max-width: 500px;"><a v-bind:href="item.short_id" target="_blank">{{item.short_id}}</a>
                        </div>
                    </td>
                    <td align="center">{{item.frequency}}次</td>
                    <td align="center">{{item.input_time|getFormatTime}}</td>
                    <td>
                        <button class="btn btn-primary" style="margin-left: 8px;" @click="editItem($event)">编辑</button>
                        <input type="hidden" name="id" v-bind:value="item.id"/>
                        <button class="btn btn-danger" style="margin-left: 8px;" @click="deleteItem($event)">删除</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <div style="text-align: center">
                <ul class="pagination pagination-sm no-margin">
                    <button @click="toPage( parseInt(where.page) - 1 )" class="btn btn-primary">上一页</button>
                    {{ where.page }} / {{ total_page }}
                    <button @click="toPage( parseInt(where.page) + 1 )" class="btn btn-primary">下一页</button>
                    <span style="line-height: 30px;margin-left: 10px;"><input id="ipt_page"
                                                                              style="width:50px;text-align: center;"
                                                                              type="text" v-model="temp_page"></span>
                    <span><button class="btn btn-primary" @click="toPage( temp_page )">跳转</button></span>
                </ul>
            </div>
        </div>
    </div>
    <script src="{$config_siteurl}statics/js/common.js?v"></script>
    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    where: {
                        url: '',
                        start_date: '',
                        end_date: '',
                        page: 1,
                        limit: 20
                    },
                    urls: {},
                    temp_page: 1,
                    total_page: 0
                },
                filters: {
                    getFormatTime: function (value) {
                        var time = new Date(parseInt(value * 1000));
                        var y = time.getFullYear();
                        var m = time.getMonth() + 1;
                        var d = time.getDate();
                        var h = time.getHours();
                        var i = time.getMinutes();
                        var res = y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d) + '';
                        res += '  ' + (h < 10 ? '0' + h : h) + ':' + (i < 10 ? '0' + i : i);
                        return res;
                    },

                },
                methods: {
                    /**
                     * 格式化
                     * @param value 千分秒
                     */
                    formatTimeToYMD: function (value) {
                        var time = new Date(parseInt(value));
                        var y = time.getFullYear();
                        var m = time.getMonth() + 1;
                        var d = time.getDate();
                        var res = y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d) + '';
                        return res;
                    },
                    getList: function () {
                        var that = this;
                        $.ajax({
                            url: '{:U("Redirect/Index/getUrls")}',
                            data: that.where,
                            type: 'get',
                            dataType: 'json',
                            success: function (res) {
                                if (res.status) {
                                    that.urls = res.data.items;
                                    that.where.page = res.data.page;
                                    that.where.limit = res.data.limit;
                                    that.temp_page = res.data.page;
                                    that.total_page = res.data.total_page;
                                }
                            }
                        });
                    },
                    toPage: function (page) {
                        this.where.page = page;
                        if (this.where.page < 1) {
                            this.where.page = 1;
                        }
                        if (this.where.page > this.total_page) {
                            this.where.page = this.total_page;
                        }
                        this.getList();
                    },
                    search: function () {
                        this.where.page = 1;
                        this.where.start_date = $('input[name="start_date"]').val();
                        this.where.end_date = $('input[name="end_date"]').val();
                        this.where.url = $('input[name="url"]').val();
                        this.getList();
                    },
                    add: function () {
                        var that = this;
                        var url = '{:U("Redirect/Index/add")}';
                        window.location.href = url;
                    },
                    editItem: function (e) {
                        var url = '{:U("Redirect/Index/update")}';
                        var param = "&id=" + e.target.nextElementSibling.value;
                        window.location.href = url + param;
                    },
                    deleteItem: function (e) {
                        var that = this;
                        layer.alert('您确认要删除吗？', {
                            skin: 'layui-layer-molv' //样式类名  自定义样式
                            , closeBtn: 1    // 是否显示关闭按钮
                            , anim: 1 //动画类型
                            , btn: ['确认', '取消'] //按钮
                            , icon: 6    // icon
                            , yes: function () {
                                $.ajax({
                                    url: '{:U("Redirect/Index/deleteUrl")}',
                                    data: {
                                        'id': e.target.previousElementSibling.value
                                    },
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (res) {
                                        if (res.status) {
                                            //console.log(res.delete);
                                            if (res.data.delete) {
                                                that.getList()
                                            } else {
                                                layer.msg('删除失败', {'time': 1500});
                                            }
                                        }
                                    }
                                });
                                layer.closeAll();
                            }
                        });
                    }
                },
                mounted: function () {
                    //默认显示最近7日日志
                    var date_today = new Date();
                    var date_last = new Date(date_today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    $('input[name="start_date"]').val(this.formatTimeToYMD(date_last.getTime()));
                    $('input[name="end_date"]').val(this.formatTimeToYMD(date_today.getTime()));
                    this.getList();
                }
            });
        });
    </script>
</block>
