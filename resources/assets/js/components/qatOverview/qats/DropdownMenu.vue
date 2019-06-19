<style scoped>
    *{
        outline:none;
    }
</style>
<template>
    <el-menu active-text-color="#409EFF" class="el-menu-demo" mode="horizontal" @select="handleSelect" ref="menu">
        <menutree :data="menuData" :defaultActiveIndex="defaultActiveIndex"></menutree>
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
                activeIndex: 'ENIQ-TDD'
            }
        },
        props: ["menuData","defaultActiveIndex"],
        methods: {
            handleSelect(key, keyPath) {
                var dataType = key.split('-')[1];
                var dataSource = key.split('-')[0];
                this.$store.dispatch("getQatDataSource", {
                    dataSource: dataSource
                });
                this.$store.dispatch("getQatDataStyle", {
                    dataTypes: dataType
                });
                this.bus.$emit('dataTypes', {dataType: dataType, dataSource: dataSource});
            }
        },
        watch:{
            defaultActiveIndex(val){
                var _this = this;
                setTimeout(function (){
                    _this.$refs.menu.activeIndex = val;
                }, 500);
            }
        }
    }
</script>