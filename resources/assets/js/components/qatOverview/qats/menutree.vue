<style>
    .el-menu--horizontal>div>.el-submenu {
        float: left;
        //padding-top: 5px;
    }
    .el-menu--horizontal>div>.el-menu-item {
        float: left;
        //height: 60px;
        //line-height: 60px;
        //margin: 0;
        //border-bottom: 2px solid transparent;
        //color: #909399;
    }
    .el-menu--horizontal>div>.el-submenu .el-submenu__icon-arrow {
        position: static;
        vertical-align: middle;
        margin-left: 8px;
        margin-top: -3px;
    }
    .el-menu--horizontal>div>.el-submenu .el-submenu__title{
        padding-top: 0.5px;
    }
</style>
<template>
<div class="menutree">
    <template v-for="menu in data">
    <el-submenu :index="menu.index" v-if="menu.children">
        <template slot="title"><i :class="menu.icon"></i>{{menu.name}}</template>
        <menutree :data="menu.children"></menutree>
    </el-submenu>

    <el-menu-item v-else :index="menu.index">
        <i :class="menu.icon"></i>
        <span slot="title">{{menu.name}}</span>
    </el-menu-item>
    </template>
</div>
</template>
<script>
import menutree from "./menutree";
export default {
    name: "menutree",
    data() {
        return {
            menu_data: {}
        };
    },
    components: {
        menutree: menutree
    },
    props: ["data"]
};
</script>