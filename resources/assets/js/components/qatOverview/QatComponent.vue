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
        margin-bottom: 5px;
    }
    .qatbody {
        position: relative;
        padding: 0 0 15px 0;
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
            <form-group :dataSource="datasource" :dataType="datatype"></form-group>
        </div>
        <br />
        <div class="qattable">
            <table-group></table-group>
        </div>
    </div>
</template>

<script>
    import DropdownMenu from './qats/DropdownMenu.vue';
    import FormGroup from './qats/FormGroup.vue';
    import TableGroup from './qats/TableGroup.vue';
    export default {
        data() {
            return {
                datasource: 'ENIQ',
                datatype: 'TDD'
            }
        },
        components: {
            DropdownMenu,
            FormGroup,
            TableGroup
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
            });
        }
    }
</script>