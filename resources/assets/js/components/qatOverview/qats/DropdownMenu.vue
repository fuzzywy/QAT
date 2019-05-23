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
                <el-menu-item index="ENIQ-TDD">TDD</el-menu-item>
                <el-menu-item index="ENIQ-FDD">FDD</el-menu-item>
                <el-menu-item index="ENIQ-GSM">GSM</el-menu-item>
                <el-menu-item index="ENIQ-NBIOT">NBIOT</el-menu-item>
            </el-submenu>
            <el-submenu index="NBM">
                <template slot="title">{{nbmkpi}}</template>
                <el-menu-item index="NBM-TDD">TDD</el-menu-item>
                <el-menu-item index="NBM-FDD">FDD</el-menu-item>
                <el-menu-item index="NBM-NBIOT">NBIOT</el-menu-item>
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
            <el-submenu index="KGET">
                <template slot="title">{{kgetkpi}}</template>
                <el-menu-item index="ALL">{{$t('messages.kget.QUERY')}}</el-menu-item>
                <el-menu-item index="Check">{{$t('messages.kget.CHECK')}}</el-menu-item>
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
            <el-submenu index="TASK">
                <template slot="title">{{taskkpi}}</template>
                <el-menu-item index="Log">{{$t('messages.task.Log')}}</el-menu-item>
                <el-menu-item index="Storage">{{$t('messages.task.Storage')}}</el-menu-item>
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
                kgetkpi: "",
                mrkpi: "MR",
                ctrkpi: "CTR",
                ctumkpi: "CTUM",
                ebmkpi: "EBM",
                alarmkpi: "ALARM",
                gsmkpi: "GSM",
                ltekpi: "LTE",
                tddkpi: "TDD",
                fddkpi: "FDD",
                taskkpi: ""
            }
        },
        mounted() {
            this.kgetkpi = this.$t('messages.kget.KGET');
            this.taskkpi = this.$t('messages.task.TASK');
        },
        methods: {
            handleSelect(key, keyPath) {
                var ft = keyPath.join('-');
                var datasoure = keyPath[0];
                this.eniqkpi = 'ENIQ';
                this.nbmkpi = 'NBM';
                this.kgetkpi = this.$t('messages.kget.KGET');
                this.mrkpi = "MR";
                this.ctrkpi = "CTR";
                this.ctumkpi = "CTUM";
                this.ebmkpi = "EBM";
                this.alarmkpi = "ALARM";
                this.taskkpi = this.$t('messages.task.TASK');
                switch (datasoure)
                {
                    case 'ENIQ':
                        this.eniqkpi = ft;
                        break;
                    case 'NBM':
                        this.nbmkpi = ft;
                        break;
                    case 'KGET':
                        this.kgetkpi = this.kgetkpi+'-'+keyPath[1];
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
                    case 'TASK':
                        this.taskkpi = this.taskkpi+'-'+keyPath[1];
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