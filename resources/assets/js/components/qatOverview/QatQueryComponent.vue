<style>
    .el-card__body{
        padding-right: 0px;
        padding-left: 0px;
        padding-top: 0px;
    }
    .el-container_kget{
        height: 495px;
    }
    .box-card_kget{
        min-height: 495px;
    }
</style>
<template>
    <el-container class="el-container_">
        <el-main>
            <el-card class="box-card_">
                <dropdown-menu></dropdown-menu>
                <component :is="whichFormGroup" :dataSource="datasource" :dataType="datatype"></component>
                <component :is="whichTableGroup"></component>
            </el-card>
        </el-main>
    </el-container>
</template>

<script>
    import DropdownMenu from './qats/DropdownMenu.vue';
    import FormGroup from './qats/FormGroup.vue';
    import TableGroup from './qats/TableGroup.vue';
    import FormKgetGroup from './qats/kget/FormKgetGroup.vue';
    import FormCheckGroup from './qats/kget/FormCheckGroup.vue';
    import TableCheckGroup from './qats/kget/TableCheckGroup.vue';
    
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
            FormCheckGroup,
            TableCheckGroup
        },
        mounted() {
            if ( window.location.hash == '#/eniqfdd' ) {
                this.datasource = 'ENIQ';
                this.dataType = 'FDD';
            }
            this.bus.$on('dataTypes', types=> {
                this.datasource = types.datasource
                this.datatype = types.datatype
                switch (this.datasource) {
                    case 'KGET' : 
                        switch (this.datatype) {
                            case 'Check' :
                                this.whichFormGroup = 'FormCheckGroup';
                                this.whichTableGroup = '';
                            break;
                            default :
                                this.whichFormGroup = 'FormKgetGroup';
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