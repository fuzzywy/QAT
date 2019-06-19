<style>
 .menu-Bar:not(.el-menu--collapse) {
        width: 200px;
        min-height: 400px;
    }
</style>
<template>
    <el-menu :default-active="defaultActiveIndex" active-text-color="#409EFF" class="el-menu-demo"  mode="vertical" @select="handleSelect" :permission="getPermission"> 
        <menutree :data="asideMenu"></menutree> 
    </el-menu>
</template>
<script>
    import menutree from "./menutree";
    export default {
        components: {
            menutree: menutree
        },
        data() {
            return {
                defaultActiveIndex: '',
                asideMenu: {}
            }
        },
        mounted() {
            this.$store.dispatch('getUser');
        },
        computed: {
            getPermission() {
                switch (this.$store.getters.getUserDataStatus) {
                    case 2 :
                        this.asideMenu = this.$store.getters.getUserData.asideMenu;
                        this.defaultActiveIndex = this.asideMenu[0]['index'];
                    default:
                    break;
                }
            }
        },
        methods: {
            handleSelect(key, keyPath) {
                this.bus.$emit('asideNav', {dataType: keyPath[1], dataSource: keyPath[0]});
            }
        }
    }
</script>