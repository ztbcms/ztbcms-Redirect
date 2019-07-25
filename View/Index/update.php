<Admintemplate file="Common/Head" />

<body class="J_scroll_fixed">
    <div class="wrap jj" id="app">
        <div class="nav">
            <ul class="cc">
                <li class="current">修改地址</li>
            </ul>
        </div>

        <div class="h_a">地址信息</div>
        <div class="table_full">
            <table width="100%">
                <col class="th" />
                <col />
                <tr>
                    <th>实际地址</th>
                    <td>
                        <input name="id" type="hidden" v-model="id" />
                        <input name="url" type="text" class="input" v-model="url">
                    </td>
                </tr>
            </table>
        </div>
        <div class="">
            <div class="btn_wrap_pd">
                <button class="btn btn_submit" @click="updateItem">修改</button>
                <span class="error_msg" role="tooltip">{{msg}}</span>
            </div>
        </div>
    </div>

    <style>
        .error_msg{
            color:red;
            font-size: 1.5rem;
            font-weight: bolder;
        }
    </style>

    <script type="text/javascript">
        var vm = new Vue({
            el: "#app",
            data: {
                id: '{$id}',
                msg: "",
                url: "{$url}",
            },
            methods: {
                updateItem: function() {
                    this.msg = ''
                  
                    if (this.url.trim() == '') {
                        this.msg = "地址不能为空！"
                        return;
                    }

                    var that = this;
                    $.ajax({
                        url: '{:U("Redirect/Index/updateUrl")}',
                        data: {
                            url: that.url,
                            id: that.id
                        },
                        type: 'post',
                        dataType: 'json',
                        success(res) {
                            if (!res.status) {
                                layer.msg(res.msg)
                            } else {
                                layer.msg('操作成功')
                            }
                        }
                    })

                }
            },
        })
    </script>

</body>




</html>