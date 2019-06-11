<style>
 
</style>
<template>
    <el-menu class="el-menu-demo" mode="vertical" @select="handleSelect" :permission="getPermission">
        <el-menu-item index="Query">
            <i class="el-icon-search"></i>
            <span slot="title">Query</span>
        </el-menu-item>
        <el-menu-item index="Template">
            <i class="el-icon-edit-outline"></i>
            <span slot="title">Template</span>
        </el-menu-item>
        <!--<el-submenu index="Task">
            <template slot="title"><i class="el-icon-document"></i>Task</template>
            <el-menu-item index="Log">{{$t('messages.menu.Log')}}</el-menu-item>
            <el-menu-item index="Storage">{{$t('messages.menu.Storage')}}</el-menu-item>
        </el-submenu>-->
        <el-menu-item index="Task">
            <i class="el-icon-document"></i>
            <span slot="title">Task</span>
        </el-menu-item>
        <el-submenu index="Schedule">
            <template slot="title"><i class="el-icon-time"></i>Schedule</template>
            <el-menu-item index="ENIQ">ENIQ</el-menu-item>
            <el-menu-item index="KGET">KGET</el-menu-item>
        </el-submenu>
        <el-menu-item v-show="isShow" index="Setting">
            <i class="el-icon-setting"></i>
            <span slot="title">Setting</span>
        </el-menu-item>
        <el-menu-item v-show="isShow" index="User">
            <i class="el-icon-user"></i>
            <span slot="title">User</span>
        </el-menu-item>
    </el-menu>
</template>
<script>
    export default {
        data() {
            return {
                isShow: false
            }
        },
        mounted() {
            this.$store.dispatch('getUser');
        },
        computed: {
            getPermission() {
                switch (this.$store.getters.getUserDataStatus) {
                    case 2 :
                        if (this.$store.getters.getUserData.type == 'admin') this.isShow = true;
                    default:
                    break;
                }
            }
        },
        methods: {
            handleSelect(key, keyPath) {
                var dataSource = '';
                var dataType = '';
                dataSource =  keyPath[0];
                dataType = keyPath[1];
                this.bus.$emit('asideNav', {dataType: dataType, dataSource: dataSource});
            }
        }
    }
</script>