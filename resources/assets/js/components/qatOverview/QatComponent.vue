<style>
    .qatbox {
        top: 49px;
        min-height: -webkit-fill-available;
        border-radius: 1px;
        background-color: #fff;
        width: 100%;
        position: absolute;
        z-index: 1;
    }
    .qatheader {
        position: relative;
        /*height: 50px;*/
        /*margin-bottom: 5px;*/
    }
    .qatbody {
        position: relative;
        /*padding: 0 0 15px 0;*/
        width: 100%;
        /*border: 1px solid #dcdfe6;*/
        margin: auto;
    }
    .qattable {
        position: relative;
        height: 100%;
        width: 100%;
    }
</style>
<template>
    <div class="qatbox">
        <div class="qatheader">
            <dropdown-menu></dropdown-menu>
        </div>
        <div class="qatbody">
            <component :is="whichFormGroup" :dataSource="datasource" :dataType="datatype"></component>
            <!--<form-group :dataSource="datasource" :dataType="datatype"></form-group>-->
        </div>
        <!--<br />-->
        <div class="qattable">
            <component :is="whichTableGroup"></component>
            <!--<table-group></table-group>-->
        </div>
    </div>
</template>

<script>
    import DropdownMenu from './qats/DropdownMenu.vue';
    import FormGroup from './qats/FormGroup.vue';
    import TableGroup from './qats/TableGroup.vue';
    import FormKgetGroup from './qats/kget/FormKgetGroup.vue';
    import TableKgetGroup from './qats/kget/TableKgetGroup.vue';
    import FormCheckGroup from './qats/kget/FormCheckGroup.vue';
    import TableCheckGroup from './qats/kget/TableCheckGroup.vue';
    import LogUpload from './qats/task/LogUpload.vue';
    import StorageManage from './qats/task/StorageManage.vue';
    
    export default {
        data() {
            return {
                datasource: 'ENIQ',
                datatype: 'TDD',
                whichFormGroup: 'FormGroup',
                whichTableGroup: 'TableGroup'
            }
        },
        components: {
            DropdownMenu,
            FormGroup,
            TableGroup,
            FormKgetGroup,
            TableKgetGroup,
            FormCheckGroup,
            TableCheckGroup,
            LogUpload,
            StorageManage
        },
        mounted() {
            if ( window.location.hash == '#/eniqfdd' ) {
                // console.log(window.location.hash)
                this.datasource = 'ENIQ';
                this.dataType = 'FDD';
            }
            this.bus.$on('dataTypes', types=> {
                this.datasource = types.datasource
                this.datatype = types.datatype
                // console.log(types.datasource, types.datatype)
                switch (this.datasource) {
                    case 'KGET' : 
                        switch (this.datatype) {
                            case 'Check' :
                                this.whichFormGroup = 'FormCheckGroup';
                                this.whichTableGroup = '';
                            break;
                            default :
                                this.whichFormGroup = 'FormKgetGroup';
                                this.whichTableGroup = 'TableKgetGroup';

                        }
                    break;
                    case 'TASK' : 
                        switch (this.datatype) {
                            case 'Log' :
                                this.whichFormGroup = 'LogUpload';
                                this.whichTableGroup = '';
                            break;
                            default :
                                this.whichFormGroup = 'StorageManage';
                                this.whichTableGroup = '';

                        }
                    break;
                    default :
                        this.whichFormGroup = 'FormGroup';
                        this.whichTableGroup = 'TableGroup';
                }
            });
        }
    }
</script>