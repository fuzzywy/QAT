<style>
	.dropdown {
		text-align: center;
	}
</style>

<template>
	<div id="dropdown" class="dropdown">
		<b-dropdown id="eniq" :text="eniqkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('ENIQ', 'TDD')" to="eniqtdd">TDD</b-dropdown-item>
		    <b-dropdown-item @click="types('ENIQ', 'FDD')" to="eniqfdd">FDD</b-dropdown-item>
		    <b-dropdown-item @click="types('ENIQ', 'GSM')" to="eniqgsm">GSM</b-dropdown-item>
		    <b-dropdown-item @click="types('ENIQ', 'NBIOT')" to="eniqnbiot">NBIOT</b-dropdown-item>
		    <b-dropdown-item disabled @click="types('ALARM')" to="eniqalarm">ALARM</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="nbm" :text="nbmkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('NBM', 'TDD')" to="nbmtdd">TDD</b-dropdown-item>
		    <b-dropdown-item @click="types('NBM', 'FDD')" to="nbmfdd">FDD</b-dropdown-item>
		    <b-dropdown-item @click="types('NBM', 'NBIOT')" to="nbmnbiot">NBIOT</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="kget" :text="kgetkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('KGET', '')">KGET</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="mr" :text="mrkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('MR', '')">MR</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="ctr" :text="ctrkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('CTR', '')">CTR</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="ctum" :text="ctumkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('CTUM', '')">CTUM</b-dropdown-item>
		</b-dropdown>
		<b-dropdown id="ebm" :text="ebmkpi" class="m-md-2">
			<b-dropdown-header>KPI</b-dropdown-header>
			<b-dropdown-divider></b-dropdown-divider>
		    <b-dropdown-item @click="types('EBM', '')">EBM</b-dropdown-item>
		</b-dropdown>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				eniqkpi: "ENIQ",
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
			types: function(ft, st) {
				this.eniqkpi = 'ENIQ';
				this.nbmkpi = 'NBM';
				this.kgetkpi = 'KGET';
				this.mrkpi = "MR";
				this.ctrkpi = "CTR";
				this.ctumkpi = "CTUM";
				this.ebmkpi = "EBM";
				if ( ft == 'ENIQ' ) {
					this.eniqkpi = 'ENIQ - ' + st;
				} else if( ft == 'NBM' ) {
					this.nbmkpi = 'NBM - ' + st;
				} else if( ft == 'KGET' ) {
					this.kgetkpi = 'KGET - ' + st;
				}  else if( ft == 'MR' ) {
					this.mrkpi = 'MR - ' + st;
				} else if( ft == 'CTR' ) {
					this.ctrkpi = 'CTR - ' + st;
				} else if( ft == 'CTUM' ) {
					this.ctumkpi = 'CTUM - ' + st;
				} else if( ft == 'EBM' ) {
					this.ebmkpi = 'EBM - ' + st;
				}
				this.bus.$emit('dataTypes', {datasource: ft, datatype: st});
			}
		}
	}
</script>