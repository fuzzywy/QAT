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
            </el-submenu>
            <el-submenu index="NBM">
                <template slot="title">{{nbmkpi}}</template>
                <el-menu-item index=" TDD">TDD</el-menu-item>
                <el-menu-item index=" FDD">FDD</el-menu-item>
                <el-menu-item index=" NBIOT">NBIOT</el-menu-item>
            </el-submenu>
            <el-submenu index="ALARM">
                <template slot="title">{{alarmkpi}}</template>
                <el-submenu index="GSM">
                    <template slot="title">{{gsmkpi}}</template>
                    <el-menu-item index="Current">当前告警</el-menu-item>
                    <el-menu-item index="History">历史告警</el-menu-item>
                </el-submenu>
                <el-submenu index="LTE">
                    <template slot="title">{{ltekpi}}</template>
                        <el-submenu index="TDD">
                            <template slot="title">{{tddkpi}}</template>
                            <el-menu-item index="Current">当前告警</el-menu-item>
                            <el-menu-item index="History">历史告警</el-menu-item>
                        </el-submenu>
                        <el-submenu index="FDD">
                            <template slot="title">{{fddkpi}}</template>
                            <el-menu-item index="Current">当前告警</el-menu-item>
                            <el-menu-item index="History">历史告警</el-menu-item>
                        </el-submenu>
                </el-submenu>
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
                ebmkpi: "EBM",
                alarmkpi: "ALARM",
                gsmkpi: "GSM",
                ltekpi: "LTE",
                tddkpi: "TDD",
                fddkpi: "FDD"
            }
        },
        methods: {
            handleSelect(key, keyPath) {
                var ft = keyPath.join('-');
                var datasoure = keyPath[0];
                this.eniqkpi = 'ENIQ';
                this.nbmkpi = 'NBM';
                this.kgetkpi = 'KGET';
                this.mrkpi = "MR";
                this.ctrkpi = "CTR";
                this.ctumkpi = "CTUM";
                this.ebmkpi = "EBM";
                switch (datasoure)
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
                    case 'ALARM':
                        this.alarmkpi = ft;
                        switch(keyPath[1]) {
                            case 'GSM':
                                this.$store.dispatch("getAlarmStyle", {
                                    alarmStyle: keyPath[1],
                                    alarmTime: keyPath[2]
                                });
                                key = keyPath[1];
                                break;
                            case "LTE":
                                this.$store.dispatch("getAlarmStyle", {
                                    alarmStyle: keyPath[1],
                                    alarmTime: keyPath[3]
                                });
                                 key = keyPath[1]+'-'+keyPath[2];
                                break;
                        }
                        break;
                    default:
                        this.eniqkpi = ft;
                        break;
                }
                this.$store.dispatch("getQatDataSource", {
                    dataSource: keyPath[0]
                });
                this.$store.dispatch("getQatDataStyle", {
                    dataTypes: key
                });
                this.bus.$emit('dataTypes', {datatype: key, datasource: keyPath[0]});
            }
        }
    }
</script>