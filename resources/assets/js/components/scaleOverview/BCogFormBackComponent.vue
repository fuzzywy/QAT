<template>
    <li class="nav-item dropdown nav-link cogback" @click.stop="onBack()">
        返回主页
    </li>
</template>
<style>
    .cogback {
        padding-top: 8px;
        padding-left: 0px;
        padding-bottom: 0px;
        padding-right: 0px;
        cursor:pointer;
    }
</style>
<script>
    export default {
        methods:{
            onBack (){
                this.$router.push("/");
            }

        }
    }
</script>