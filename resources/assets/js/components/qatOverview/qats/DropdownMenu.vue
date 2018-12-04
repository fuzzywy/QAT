<style>
    .dropdown {
        top: 5px;
        text-align: center;
    }
</style>

<template>
    <div id="dropdown" class="dropdown">
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{eniqkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="ENIQ-TDD">TDD</el-dropdown-item>
                <el-dropdown-item command="ENIQ-FDD">FDD</el-dropdown-item>
                <el-dropdown-item command="ENIQ-GSM">GSM</el-dropdown-item>
                <el-dropdown-item command="ENIQ-NBIOT">NBIOT</el-dropdown-item>
                <el-dropdown-item disabled command="ENIQ-ALARM">ALARM</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{nbmkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="NBM-TDD">TDD</el-dropdown-item>
                <el-dropdown-item command="NBM-FDD">FDD</el-dropdown-item>
                <el-dropdown-item command="NBM-NBIOT">NBIOT</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{kgetkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="KGET-">KGET</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{mrkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="MR-">MR</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{ctrkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="CTR-">CTR</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{ctumkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="CTUM-">CTUM</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <el-dropdown @command="handleCommand">
            <el-button type="primary" size="medium">
                {{ebmkpi}}<i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item disabled>KPI</el-dropdown-item>
                <el-dropdown-item divided command="EBM-">EBM</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                eniqkpi: "ENIQ-TDD",
                nbmkpi: "NBM",
                kgetkpi: "KGET",
                mrkpi: "MR",
                ctrkpi: "CTR",
                ctumkpi: "CTUM",
                ebmkpi: "EBM"
            }
        },
        watch: {
        },
        methods: {
            handleCommand(command) {
                var ft = command.split('-')[0], 
                    st = command.split('-')[1];
                this.eniqkpi = 'ENIQ';
                this.nbmkpi = 'NBM';
                this.kgetkpi = 'KGET';
                this.mrkpi = "MR";
                this.ctrkpi = "CTR";
                this.ctumkpi = "CTUM";
                this.ebmkpi = "EBM";
                switch (ft)
                {
                    case 'ENIQ':
                        this.eniqkpi = command;
                        break;
                    case 'NBM':
                        this.nbmkpi = command;
                        break;
                    case 'KGET':
                        this.kgetkpi = command;
                        break;
                    case 'MR':
                        this.mrkpi = command;
                        break;
                    case 'CTR':
                        this.ctrkpi = command;
                        break;
                    case 'CTUM':
                        this.ctumkpi = command;
                        break;
                    case 'EBM':
                        this.ebmkpi = command;
                        break;
                    default:
                        this.eniqkpi = command;
                        break;
                }
                this.bus.$emit('dataTypes', {datasource: ft, datatype: st})
            }
        }
    }
</script>