<style>
	.qatbox {
		top: 49px;
		min-height: -webkit-fill-available;
		/*border: 1px solid #cbcbcb;*/
		border-radius: 1px;
		background-color: #fff;
		/*height: 100%;*/
		width: 100%;
		position: absolute;
		z-index: 1;
	}
	.qatheader {
		position: relative;
		height: 50px;
		/*border-bottom: 1px solid #cbcbcb;*/
	}
	.qatbody {
		position: relative;
		/*height: 220px;*/
		padding: 0 0 15px 0;
		width: 80%;
		border: 1px solid #dcdfe6;
		margin: auto;
	}
	.qattable {
		position: relative;
		height: 100%;
		/*border-bottom: 1px solid #cbcbcb;*/
	}
</style>

<template>
	<div class="qatbox">
		<!-- header -->
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
                // console.log(this.datasource, this.datatype)
            });
            // console.log(this.$router)
		}
	}
</script>