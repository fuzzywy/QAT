<style scoped>
    .top {
        margin-top: 30px;
    }
</style>
<template>
    <div class="top">
        <el-table v-bind:data="tableData">
            <el-table-column>
                <template slot="header" slot-scope="scope">
                    <el-button @click="addRow" icon="el-icon-plus">新增</el-button>
                </template>
                <template slot-scope="scope">
                    <el-button @click="delRow(scope.$index)" size="mini" icon="el-icon-delete" circle></el-button>
                    <el-button @click="run(scope.row)" v-if="scope.row.status=='Stopped'" size="mini"
                               icon="el-icon-caret-right"
                               circle></el-button>
                    <el-button @click="run(scope.row)" v-else size="mini" icon="el-icon-close"
                               circle></el-button>
                </template>
            </el-table-column>
            <el-table-column prop='create' label="CreateTime">
                <template slot-scope="scope">
                    <i class="el-icon-time"></i>
                    <span>{{scope.row.create}}</span>
                </template>
            </el-table-column>
            <el-table-column prop='taskName' label="TaskName">
            </el-table-column>
            <el-table-column prop='schedule' label="Schedule">
                <template slot-scope="scope">
                    <el-select v-model="scope.row.schedule">
                        <el-option key="Daily" value="每天"></el-option>
                        <el-option key="Weekly" value="每周"></el-option>
                        <el-option key="Monthly" value="每月"></el-option>
                    </el-select>
                </template>
            </el-table-column>
            <el-table-column label="Template">
                <template slot-scope="scope">
                    <el-select v-model="scope.row.template">
                        <el-option key="template01" value="模板1"></el-option>
                        <el-option key="template02" value="模板2"></el-option>
                        <el-option key="template03" value="模板3"></el-option>
                    </el-select>
                </template>
            </el-table-column>
            <el-table-column width="245" prop="executeTime" label="ExecuteTime">
                <template slot-scope="scope">
                    <el-date-picker v-model="scope.row.executeTime" width="18" type="datetime">
                    </el-date-picker>
                </template>
            </el-table-column>
            <el-table-column prop='notify' label="Notify">
                <template slot-scope="scope">
                    <el-select v-model="scope.row.notify">
                        <el-option key="yang" value="yang"></el-option>
                        <el-option key="leon" value="leon"></el-option>
                    </el-select>
                </template>
            </el-table-column>
        </el-table>
        {{JSON.stringify(tableData)}}
    </div>
</template>
<script>
    export default {
        name: "ScheduleManage",
        data() {
            return {
                // Note that's just the test data
                tableData: [
                    {
                        'create': '2018-12-07',
                        'taskName': '高负荷小区日报',
                        'schedule': '每天',
                        'template': [
                            {
                                'name': 'template01',
                                'time': 'Weekly',
                                'location': 'city'
                            },
                            {
                                'name': 'template02',
                                'time': 'daily',
                                'location': 'erbs'
                            }
                        ],
                        'executeTime': '2018-12-08',
                        'notify': 'yang',
                        'lastExecuteTime': '2018-12-08',
                        'status': 'Stopped',
                        'operation': 'todo'
                    },
                    {
                        'create': '2018-12-07',
                        'taskName': '江苏关键指标周报',
                        'schedule': '每周',
                        'template': [
                            {
                                'name': 'template01',
                                'time': 'hour',
                                'location': 'city'
                            },
                            {
                                'name': 'template02',
                                'time': 'daily',
                                'location': 'erbs'
                            }
                        ],
                        'executeTime': '2018-12-08',
                        'notify': 'yang',
                        'lastExecuteTime': '2018-12-08',
                        'status': 'Running',
                        'operation': 'todo'
                    },
                    {
                        'create': '2018-12-07',
                        'taskName': '全国关键指标月报',
                        'schedule': '每月',
                        'template': [
                            {
                                'name': 'template01',
                                'time': 'hour',
                                'location': 'city'
                            },
                            {
                                'name': 'template02',
                                'time': 'daily',
                                'location': 'erbs'
                            }
                        ],
                        'executeTime': '2018-12-08',
                        'notify': 'yang',
                        'lastExecuteTime': '2018-12-08',
                        'status': 'Stopped',
                        'operation': 'todo'
                    },

                ]
            }
        },
        methods: {
            addRow: function () {
                this.tableData.push({'create': new Date().toDateString(), 'status': 'Stopped'});
            },
            delRow: function (index) {
                this.tableData.splice(index, 1);
            },
            run: function (row) {
                row.status = row.status == 'Running' ? 'Stopped' : 'Running';
            }
        }
    }
</script>