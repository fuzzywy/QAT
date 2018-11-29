<template>
    <div>
        <li class="nav-item dropdown">
            <a id="locale" href="#" class="nav-link cog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <router-link to="cog" tag="div"><i class="icon-ali-cog"></i></router-link>
            </a>
        </li>
    </div>
</template>
<style>
    .cog {
        padding-top: 5px;
        padding-left: 0px;
        padding-bottom: 0px;
        padding-right: 0px;
    }
    .cog-form {
        width: 800px;
    }
    .cog-form > div {
        padding: 8px 8px 8px 8px;
    }

</style>
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
            }
        }
    }
</script>