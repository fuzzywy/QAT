<style>
    .cog {
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
    <el-tooltip class="item" effect="dark" content="连接配置" placement="bottom-end" >
        <li class="nav-item dropdown nav-link cog">
            <i class="el-icon-setting" @click.stop="onConfig()"></i>
        </li>
    </el-tooltip>
</template>
<script>
    export default {
        data () {
            return {
                form: {
                    ip: '',
                    port: '',
                    database: '',
                    user: '',
                    pwd: '',
                    type: null,
                    checked: []
                },
                types: [
                    { text: 'Select One', value: null },
                    'LTE', 'GSM'
                ],
                show: true,
                shows: true
            }
        },
        beforeDestroy () {
            this.bus.$off('cityClickData', this.handle)
            this.bus.$off('getTabsData', this.handle)
        },
        methods: {
            onSubmit (evt) {
                evt.preventDefault();
                this.$store.dispatch('uploadCog', {
                    ip: this.form.ip,
                    port: this.form.port,
                    database: this.form.database,
                    user: this.form.user,
                    pwd: this.form.pwd,
                    type: this.form.type
                })
                alert('上传成功')
            },
            onReset (evt) {
                evt.preventDefault();
                /* Reset our form values */
                this.form.ip = '';
                this.form.port = '';
                this.form.database = '';
                this.form.user = '';
                this.form.pwd = '';
                this.form.type = null;
                this.form.checked = [];
                /* Trick to reset/clear native browser form validation state */
                this.show = false;
                this.$nextTick(() => { this.show = true });
            },
            onConfig () {
                this.$router.push('cog');
            }
        }
    }
</script>