<style>
    .el-main{
        padding: 0 0 0 0;
    }
    .el-form-item{
        margin-bottom: 0px;
    }
    .el-card__body{
        padding-right: 0px;
        padding-left: 0px;
    }
    .el-container_{
        height: 580px;
    }
    .box-card_{
        min-height: 580px;
    }
</style>
<template>
    <el-container class="el-container_" style="margin-top: 25px;">
        <el-aside width="200px">
            <el-card class="box-card_">
                <aside-menu></aside-menu>
            </el-card>
        </el-aside>
        <el-main>
            <component :is="whichFormGroup" :dataSource="dataSource" :dataType="dataType"></component>
        </el-main>
    </el-container>
</template>

<script>
    import AsideMenu from './qats/AsideMenu.vue';
    import BCogFormTableComponent from './setting/BCogFormTableComponent.vue';
    import QatTemplateViewComponent from './template/QatTemplateViewComponent.vue';
    import QatCrontabViewComponent from './crontab/QatCrontabViewComponent.vue';
    //import LogUpload from './qats/task/LogUpload.vue';
    import StorageManage from './task/StorageManage.vue';
    import QatKgetCrontab from './crontab/QatKgetCrontab.vue';
    import QatQueryComponent from './QatQueryComponent.vue';
    import QatUserComponent from './user/QatUserComponent.vue';


    export default {
        data() {
            return {
                dataSource: 'ENIQ',
                dataType: 'TDD',
                whichFormGroup: 'QatQueryComponent',
            }
        },
        components: {
            AsideMenu,
            QatQueryComponent,
            BCogFormTableComponent,
            QatTemplateViewComponent,
            QatCrontabViewComponent,
            //LogUpload,
            StorageManage,
            QatKgetCrontab,
            QatUserComponent
        },
        mounted() {
            this.bus.$on('asideNav', types=> {
                this.dataSource = types.dataSource;
                this.dataType = types.dataType;
                switch (this.dataSource) {
                    case 'Query' : 
                        this.whichFormGroup = 'QatQueryComponent';
                    break;
                    /*case 'Task' : 
                        switch (this.dataType) {
                            case 'Log' :
                                this.whichFormGroup = 'LogUpload';
                            break;
                            default :
                                this.whichFormGroup = 'StorageManage';

                        }
                    break;*/
                    case 'Task' :
                        this.whichFormGroup = 'StorageManage';
                    break;
                    case 'Setting' : 
                        this.whichFormGroup = 'BCogFormTableComponent';
                    break;
                    case 'Template' : 
                        this.whichFormGroup = 'QatTemplateViewComponent';
                    break;
                    case 'Schedule' : 
                        switch (this.dataType) {
                            case 'KGET' :
                                this.dataSource = 'KGET';
                                this.dataType = "ALL";
                                this.whichFormGroup = 'QatKgetCrontab';
                            break;
                            default :
                                this.whichFormGroup = 'QatCrontabViewComponent';
                        }
                    break;
                    case 'User' : 
                        this.whichFormGroup = 'QatUserComponent';
                    break;
                    default :
                        this.whichFormGroup = 'QatQueryComponent';
                }
            });
        }

    }

</script>