<style>
	.template {
        padding-top: 5px;
        padding-left: 0px;
        padding-bottom: 0px;
        padding-right: 0px;
        cursor:pointer;
    }
    .item {
      margin: 4px;
    }
</style>
<template>
	<el-tooltip class="item" effect="dark" content="模板" placement="bottom-end">
        <li class="nav-item dropdown nav-link template">
            <!-- <a id="locale" href="#" class="nav-link template" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <router-link to="template" tag="div"><i class="el-icon-edit-outline"></i></router-link>
            </a> -->
            <i class="el-icon-edit-outline" @click.stop="link()"></i>
        </li>
    </el-tooltip>
</template>
<script>
    export default {
        mounted() {
            this.bus.$on('dataTypes', types=> {
                this.datasource = types.datasource
                this.datatype = types.datatype
            });
        },
        methods: {
            link() {
                // if ( this.datasource === undefined ) {
                //     this.datasource = 'TDD';
                // }
                // if ( this.datatype === undefined ) {
                //     this.datatype = 'ENIQ';
                // }
                // this.$router.push({ path: '/template', query: { datatype:this.datatype, datasource: this.datasource } });
                this.$router.push({ path: '/template' });
            }
        }
    }
</script>