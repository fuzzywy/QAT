<style>
	.crontab {
        padding-top: 5px;
        padding-left: 0px;
        padding-bottom: 0px;
        padding-right: 0px;
    }
    .item {
      margin: 4px;
    }
</style>
<template>
	<el-tooltip class="item" effect="dark" content="定时任务" placement="bottom-end">
        <li class="nav-item dropdown">
            <a id="locale" href="#" class="nav-link crontab" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <router-link to="crontab" tag="div"><i class="el-icon-time"></i></i></router-link>
            </a>
        </li>
    </el-tooltip>
</template>
<script>
	export default {

	}
</script>