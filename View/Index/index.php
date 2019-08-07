<extend name="../../Admin/View/Common/element_layout"/>

<block name="content">
    <div id="app" style="padding: 8px;" v-cloak>
        <el-card>
            <h3>外部链接列表</h3>
            <el-alert
                    title="使用短链接时需要配置路由，详情请看模块的readme.md"
                    type="success">
            </el-alert>
            <div class="filter-container" style="margin-top: 10px;">

                <el-input v-model="listQuery.url" placeholder="原链接" style="width: 200px;"
                          class="filter-item"></el-input>
                <el-date-picker
                        v-model="s_e_date"
                        type="daterange"
                        align="right"
                        unlink-panels
                        clearable
                        range-separator="至"
                        start-placeholder="开始日期"
                        end-placeholder="结束日期"
                        value-format="yyyy-MM-dd">
                </el-date-picker>
                <el-button class="filter-item" type="primary"  @click="search">
                    搜索
                </el-button>
                <el-button class="filter-item" style="margin-left: 10px;" type="primary"
                           @click="addItem">
                    添加
                </el-button>

            </div>
            <el-table
                    :key="tableKey"
                    :data="urls"
                    border
                    fit
                    highlight-current-row
                    style="width: 100%;"
                    @sort-change="sortChange"
            >
                <el-table-column label="ID" prop="id" sortable="custom" align="center" width="80"
                                 :class-name="getSortClass()">
                    <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="日期" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.input_time | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="原链接" align="center">
                    <template slot-scope="scope">
                        <span>{{ scope.row.url }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="短链接" align="center">
                    <template slot-scope="scope">
                        <p>非短链接:<a :href="scope.row.short_url_v1" target="_blank">{{ scope.row.short_url_v1 }}</a></p>
                        <p>短链接:<a :href="scope.row.short_url_v2" target="_blank">{{ scope.row.short_url_v2 }}</a></p>
                    </template>
                </el-table-column>
                <el-table-column label="访问量" align="center" width="95">
                    <template slot-scope="scope">
                        <span>{{ scope.row.frequency }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
                    <template slot-scope="{row}">
                        <el-button type="primary" size="mini" @click="updateItem(row.id)">
                            编辑
                        </el-button>
                        <el-button size="mini" type="danger"
                                   @click="deleteItem(row.id)">
                            删除
                        </el-button>
                    </template>
                </el-table-column>

            </el-table>

            <div class="pagination-container">
                <el-pagination
                        background
                        layout="prev, pager, next, jumper"
                        :total="total"
                        v-show="total>0"
                        :current-page.sync="listQuery.page"
                        :page-size.sync="listQuery.limit"
                        @current-change="getList"
                >
                </el-pagination>
            </div>

        </el-card>
    </div>

    <style>
        .filter-container {
            padding-bottom: 10px;
        }

        .pagination-container {
            padding: 32px 16px;
        }
    </style>

    <script>
        $(document).ready(function () {
            new Vue({
                el: '#app',
                data: {
                    form: {},
                    tableKey: 0,
                    list: [],
                    urls: [],
                    total: 0,
                    listQuery: {
                        url: '',
                        start_date: '',
                        end_date: '',
                        page: 1,
                        limit: 20,
                        sort: 1
                    },
                    s_e_date: [],
                    sortOptions: [{label: 'ID 升序', key: '+id'}, {label: 'ID 降序', key: '-id'}],
                },
                watch: {},
                filters: {
                    parseTime: function (time, format) {
                        return Ztbcms.formatTime(time, format)
                    },
                },
                methods: {
                    getList: function () {
                        var that = this;
                        $.ajax({
                            url: '{:U("Redirect/Index/getUrls")}',
                            data: that.listQuery,
                            type: 'get',
                            dataType: 'json',
                            success: function (res) {
                                if (res.status) {
                                    that.urls = res.data.items;
                                    that.total = res.data.total - 1 + 1;
                                    that.listQuery.page = res.data.page - 1 + 1;
                                }
                            }
                        });
                    },
                    search: function () {

                        if (this.s_e_date instanceof Array) {
                            this.listQuery.start_date = this.s_e_date[0];
                            this.listQuery.end_date = this.s_e_date[1];
                        } else {
                            this.listQuery.start_date = this.listQuery.end_date = '';
                        }
                        this.listQuery.page = 1;
                        this.getList();
                    },
                    addItem: function () {
                        window.Ztbcms.openNewIframeByUrl('添加外部链接', '{:U("Redirect/Index/add")}')
                    },
                    updateItem: function (id) {
                        window.Ztbcms.openNewIframeByUrl('添加外部链接', '{:U("Redirect/Index/update")}' + "&id=" + id)
                    },
                    deleteItem: function (id) {
                        var that = this;
                        $.ajax({
                            url: '{:U("Redirect/Index/deleteUrl")}',
                            data: {
                                'id': id
                            },
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.status) {
                                    that.getList()
                                    that.$message.success('删除成功');
                                } else {
                                    that.$message.error('删除失败');
                                }
                            }
                        });
                    },
                    handleFilter: function () {
                        this.listQuery.page = 1
                        this.getList()
                    },
                    sortByID: function (order) {
                        if (order === 'ascending') {
                            this.listQuery.sort = 1
                        } else {
                            this.listQuery.sort = -1
                        }
                        this.handleFilter()
                    },
                    sortChange: function (data) {
                        var order = data.order
                        var prop = data.prop
                        if (prop === 'id') {
                            this.sortByID(order)
                        }
                    },
                    getSortClass: function () {
                        const sort = this.listQuery.sort
                        if (sort === 1) {
                            return 'ascending';
                        } else {
                            return 'descending';
                        }
                    },
                    //以窗口形式打开链接
                    openArticleLink: function (url) {
                        layer.open({
                            type: 2,
                            title: '预览',
                            content: url,
                            area: ['60%', '70%'],
                        })
                    }
                },
                mounted: function () {
                    this.getList();
                },

            })
        })
    </script>
</block>
