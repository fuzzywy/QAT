<template>
    <li class="nav-item dropdown" @click="onBack">
        <a id="locale" href="#" class="nav-link cogback" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            返回主页
        </a>
    </li>
</template>
<style>
    .cogback {
        padding-top: 8px;
        padding-left: 0px;
        padding-bottom: 0px;
        padding-right: 0px;
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