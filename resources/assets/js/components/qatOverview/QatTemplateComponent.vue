<style>
	.template {
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
	<el-tooltip class="item" effect="dark" content="模板" placement="bottom-end">
        <li class="nav-item dropdown">
            <a id="locale" href="#" class="nav-link template" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <router-link to="template" tag="div"><i class="el-icon-edit-outline"></i></i></router-link>
            </a>
        </li>
    </el-tooltip>
</template>
<script>
	export default {

	}
</script>