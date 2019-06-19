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
        padding-top: 0px;
        padding-bottom:0px;
    }
    .el-container_{
        height: 580px;
    }
    .box-card_{
        min-height: 580px;
    }
    .el-container_kget{
        height: 521px;
    }
    .box-card_kget{
        min-height: 521px;
    }
    .el-container_template {
        height: 577px;
    }
    .box-card_template{
        min-height: 577px;
    }
</style>
<template>
    <el-container class="el-container_" style="margin-top: 25px;">
        <el-aside width="200px">
            <el-card class="box-card_">
                <aside-menu :permission="getPermission"></aside-menu>
            </el-card>
        </el-aside>
        <el-main>
            <el-card class="box-card_">
                <dropdown-menu :defaultActiveIndex="defaultActiveIndex" :is="whickDropDownMenu" :menuData="menuData" ></dropdown-menu>
                <component :is="whichFormGroup" :dataSource="dataSource" :dataType="dataType"></component>
                <component :is="whichTableGroup"></component>
            </el-card>
        </el-main>
    </el-container>
</template>

<script>
    import AsideMenu from './qats/AsideMenu.vue';
    import DropdownMenu from './qats/DropdownMenu.vue';
    import BCogFormTableComponent from './setting/BCogFormTableComponent.vue';
    import Site from './setting/Site.vue';
    import QatTemplateViewComponent from './template/QatTemplateViewComponent.vue';
    import QatCrontabViewComponent from './crontab/QatCrontabViewComponent.vue';
    import StorageManage from './task/StorageManage.vue';
    import QatKgetCrontab from './crontab/QatKgetCrontab.vue';

    import FormGroup from './qats/FormGroup.vue';
    import TableGroup from './qats/TableGroup.vue';
    import FormKgetGroup from './qats/kget/FormKgetGroup.vue';
    import FormCheckGroup from './qats/kget/FormCheckGroup.vue';
    import TableCheckGroup from './qats/kget/TableCheckGroup.vue';

    import Review from './user/Review.vue';
    import User from './user/User.vue';
    import Role from './user/Role.vue';

    export default {
        data() {
            return {
                asideMenu: {},
                dropdownMenu: {},
                menuData: {},
                whickDropDownMenu: 'DropdownMenu',
                whichFormGroup: '',
                whichTableGroup: '',
                dataSource: 'ENIQ',
                dataType: 'TDD',
                defaultActiveIndex: ''
            }
        },
        watch:{
            dropdownMenu(val){
                this.menuData = val[this.asideMenu[0]['index']];
                this.getDefaultActiveIndex(this.asideMenu[0]['index'], this.menuData);
            }
        },
        components: {
            AsideMenu,
            DropdownMenu,
            BCogFormTableComponent,
            Site,
            QatTemplateViewComponent,
            QatCrontabViewComponent,
            StorageManage,
            QatKgetCrontab,

            FormGroup,
            TableGroup,
            FormKgetGroup,
            FormCheckGroup,
            TableCheckGroup,

            Review: Review,
            User: User,
            Role: Role
        },
        mounted() {
            this.bus.$on('asideNav', types=> {
                this.menuData = this.dropdownMenu[types.dataSource];
                this.getDefaultActiveIndex(types.dataSource, this.menuData);
            });

            this.bus.$on('dataTypes', types=> {
            	this.whichTableGroup = '';
                this.dataSource = types.dataSource;
                this.dataType = types.dataType;
                switch (this.dataSource) {
                    case 'KGET' : 
                        switch (this.dataType) {
                            case 'CHECK' :
                                this.whichFormGroup = 'FormCheckGroup';
                            break;
                            default :
                                this.whichFormGroup = 'FormKgetGroup';
                            break;    
                        }
                        this.dataType = "ALL";
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
                            break;
                        }
                    break;
                    case 'Setting' : 
                        switch (this.dataType) {
                            case 'ENIQ' :
                                this.whichFormGroup = 'BCogFormTableComponent';
                            break;
                            default :
                                this.whichFormGroup = this.dataType;
                            break;
                        }
                    break;
                    case 'User' : 
                        this.whichFormGroup = this.dataType;
                    break;
                    default :
                        this.whichFormGroup = 'FormGroup';
                        this.whichTableGroup = 'TableGroup';
                    break;
                }
            });
        },
        computed: {
            getPermission() {
                switch (this.$store.getters.getUserDataStatus) {
                    case 2 :
                        this.dropdownMenu = this.$store.getters.getUserData.dropdownMenu;
                        this.asideMenu = this.$store.getters.getUserData.asideMenu;
                    break;
                    default:
                    break;
                }
            }
        },
        methods: {
            getDefaultActiveIndex(dataSource, menuData){
            	this.whichTableGroup = '';
                var dataType = '';
                var innerDataSource = '';
                if(menuData){
                    if(menuData.length > 0){
                        if(menuData[0]['children']){
                            this.defaultActiveIndex = menuData[0]['children'][0]['index'];
                        } else {
                            this.defaultActiveIndex = menuData[0]['index'];
                        }
                        dataType = this.defaultActiveIndex.split('-')[1];
                        innerDataSource = this.defaultActiveIndex.split('-')[0];
                    }
                }
                switch (dataSource) {
                    case 'Query' : 
                        switch (innerDataSource) {
                            case 'KGET' : 
                                this.dataSource = 'KGET';
                                this.dataType = "ALL";
                                switch (dataType) {
                                    case 'CHECK' :
                                        this.whichFormGroup = 'FormCheckGroup';
                                    break;
                                    default :
                                        this.whichFormGroup = 'FormKgetGroup';
                                    break;
                                }
                            break;
                            default :
                                this.dataSource = innerDataSource;
                                this.dataType = dataType;
                                this.whichFormGroup = 'FormGroup';
                                this.whichTableGroup = 'TableGroup';
                            break;
                        }
                    break;
                    case 'Task' :
                        this.whichFormGroup = 'StorageManage';
                    break;
                    case 'Setting' :
                        switch (dataType) {
                            case 'ENIQ' :
                                this.whichFormGroup = 'BCogFormTableComponent';
                            break;
                            default :
                                this.whichFormGroup = dataType;
                            break;
                        }
                    break;
                    case 'Template' : 
                        this.whichFormGroup = 'QatTemplateViewComponent';
                    break;
                    case 'Schedule' : 
                       switch (dataType) {
                            case 'KGET' :
                                this.dataSource = 'KGET';
                                this.dataType = "ALL";
                                this.whichFormGroup = 'QatKgetCrontab';
                            break;
                            default :
                                this.whichFormGroup = 'QatCrontabViewComponent';
                            break;
                        }
                    break;
                    case 'User' : 
                        this.whichFormGroup = dataType;
                    break;    
                    default:
                        this.whichFormGroup = '';
                    break;
                }
            }
                
        }

    }

</script>