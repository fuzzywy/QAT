<style>
    .dropdown {
        /*top: 5px;*/
        text-align: center;
    }
</style>
<template>
    <div id="dropdown" class="dropdown">
        <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
            <el-submenu index="ENIQ">
                <template slot="title">{{eniqkpi}}</template>
                <el-menu-item index="TDD">TDD</el-menu-item>
                <el-menu-item index="FDD">FDD</el-menu-item>
                <el-menu-item index="GSM">GSM</el-menu-item>
                <el-menu-item index="NBIOT">NBIOT</el-menu-item>
                <el-menu-item index="ALARM" disabled>ALARM</el-menu-item>
            </el-submenu>
            <el-submenu index="NBM" disabled>
                <template slot="title">{{nbmkpi}}</template>
                <el-menu-item index="TDD">TDD</el-menu-item>
                <el-menu-item index="FDD">FDD</el-menu-item>
                <el-menu-item index="NBIOT">NBIOT</el-menu-item>
            </el-submenu>
            <el-submenu index="KGET" disabled>
                <template slot="title">{{kgetkpi}}</template>
            </el-submenu>
            <el-submenu index="MR" disabled>
                <template slot="title">{{mrkpi}}</template>
            </el-submenu>
            <el-submenu index="MR" disabled>
                <template slot="title">{{mrkpi}}</template>
            </el-submenu>
            <el-submenu index="CTR" disabled>
                <template slot="title">{{ctrkpi}}</template>
            </el-submenu>
            <el-submenu index="CTUM" disabled>
                <template slot="title">{{ctumkpi}}</template>
            </el-submenu>
            <el-submenu index="EBM" disabled>
                <template slot="title">{{ebmkpi}}</template>
            </el-submenu>
        </el-menu>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                activeIndex: 'ENIQ',
                eniqkpi: "ENIQ-TDD",
                nbmkpi: "NBM",
                kgetkpi: "KGET",
                mrkpi: "MR",
                ctrkpi: "CTR",
                ctumkpi: "CTUM",
                ebmkpi: "EBM"
            }
        },
        methods: {
            handleSelect(key, keyPath) {
                var ft = keyPath.join('-');
                this.eniqkpi = 'ENIQ';
                this.nbmkpi = 'NBM';
                this.kgetkpi = 'KGET';
                this.mrkpi = "MR";
                this.ctrkpi = "CTR";
                this.ctumkpi = "CTUM";
                this.ebmkpi = "EBM";
                switch (key)
                {
                    case 'ENIQ':
                        this.eniqkpi = ft;
                        break;
                    case 'NBM':
                        this.nbmkpi = ft;
                        break;
                    case 'KGET':
                        this.kgetkpi = ft;
                        break;
                    case 'MR':
                        this.mrkpi = ft;
                        break;
                    case 'CTR':
                        this.ctrkpi = ft;
                        break;
                    case 'CTUM':
                        this.ctumkpi = ft;
                        break;
                    case 'EBM':
                        this.ebmkpi = ft;
                        break;
                    default:
                        this.eniqkpi = ft;
                        break;
                }
                this.bus.$emit('dataTypes', {datatype: key, datasource: keyPath[0]});
            }
        }
    }
</script>