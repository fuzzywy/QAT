<template>
    <div class="top-cog">
        <b-container fluid>
        <!-- User Interface controls -->
        <b-row>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Filter" class="mb-0">
              <b-input-group>
                <b-form-input v-model="filter" placeholder="Type to Search" />
                <b-input-group-append>
                  <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Sort" class="mb-0">
              <b-input-group>
                <b-form-select v-model="sortBy" :options="sortOptions">
                  <option slot="first" :value="null">-- none --</option>
                </b-form-select>
                <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                  <option :value="false">Asc</option>
                  <option :value="true">Desc</option>
                </b-form-select>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Add ip address" class="mb-0">
              <b-button size="sm" @click.stop="addColumn">
                Add
              </b-button>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Per page" class="mb-0">
              <b-form-select :options="pageOptions" v-model="perPage" />
            </b-form-group>
          </b-col>
        </b-row>
        <!-- Main table element -->
        <b-table striped 
                 hover 
                 show-empty
                 stacked="md"
                 :items="items"
                 :fields="fields"
                 :current-page="currentPage"
                 :per-page="perPage"
                 :filter="filter"
                 :sort-by.sync="sortBy"
                 :sort-desc.sync="sortDesc"
                 :sort-direction="sortDirection"
                 :fixed="fixed"
                 @filtered="onFiltered"
                 v-show="showCog==2"
        >
          <template slot="actions" slot-scope="row">
            <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
            <b-button size="sm" @click.stop="del(row.item, row.index, $event.target)" class="mr-1">
              Delete
            </b-button>
            <b-button size="sm" @click.stop="row.toggleDetails" @click.top="modifyData(row.item, row.index, $event.target)">
              {{ row.detailsShowing ? 'Hide' : 'Modify' }} Details
            </b-button>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-card>
                <b-row class="mb-2" v-for="(value, key) in row.item" :key="key" v-if="key!== '_showDetails' && key!== 'id'">
                    <b-col sm="4" class="text-sm-right"><b>{{ key }}:</b></b-col>
                    <b-col md="5">
                        <b-form-input v-model="modify[key]" v-if="key !== 'type'"/>
                        <b-form-input placeholder="LTE or GSM" v-model="modify[key]" v-if="key === 'type'"/>
                    </b-col>
                </b-row>
                <div style="text-align: center;">
                    <b-button size="sm" @click="modifyOK">modify</b-button>
                </div>
            </b-card>
          </template>
        </b-table>
        <b-row>
          <b-col md="6" class="my-1">
            <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
          </b-col>
        </b-row>
        <!-- Info modal -->
        <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
          <pre>{{ modalInfo.content }}</pre>
        </b-modal>
        <b-modal id="confirm" :title="confirm.title" @ok="handleOk" @hide="resetModal">
            <pre> {{ confirm.content }} </pre>
        </b-modal>
        <b-modal id="confirmModify" :title="confirmModify.title" @ok="updateok">
            <pre> {{ confirmModify.content }} </pre>
        </b-modal>
      </b-container>
    </div>
</template>
<style>
    .top-cog {
        background-color: white; 
        padding: 15px 15px 15px 15px;
        margin-top: 25px;
    }
    .line-limit-length {
        max-width: 150px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;

    }
    
</style>
<script>
    var items = [];
    export default {
        data () {
            return {
              items: items,
              fields: [
                { key: 'type', label: 'Type', sortable: true, class: 'text-center', sortDirection: 'desc'  },
                { key: 'conn', label: 'Conn', sortable: true, sortDirection: 'desc' },
                { key: 'city', label: 'city', sortable: true, sortDirection: 'desc' },
                { key: 'ip', label: 'IP address', sortable: true, sortDirection: 'desc' },
                { key: 'port', label: 'Port', sortable: true, class: 'text-center' },
                { key: 'database', label: 'Database' },
                { key: 'user', label: 'User' },
                { key: 'pwd', label: 'Password' },
                { key: 'subNetworkTdd', label: 'subNetworkTdd', class: 'line-limit-length' },
                { key: 'subNetworkFdd', label: 'subNetworkFdd', class: 'line-limit-length' },
                { key: 'subNetworkNbiot', label: 'subNetworkNbiot', class: 'line-limit-length' },
                { key: 'actions', label: 'Actions' }
              ],
              fixed: false,
              currentPage: 1,
              perPage: 5,
              totalRows: items.length,
              pageOptions: [ 5, 10, 15 ],
              sortBy: null,
              sortDesc: false,
              sortDirection: 'asc',
              filter: null,
              modalInfo: { title: '', content: '' },
              confirm: { title: '', content: '' },
              confirmModify: { title: '', content: '' },
              delete: [],
              modify: []
            }
          },
          computed: {
            sortOptions () {
              // Create an options list from our fields
              return this.fields
                .filter(f => f.sortable)
                .map(f => { return { text: f.label, value: f.key } })
            },
            showCog () {
                items = this.$store.getters.getShowCog
                this.items = items
                this.totalRows =  items.length
                this.currentPage = 1
                return this.$store.getters.getShowCogStatus
            }
          },
          created() {
            this.$store.dispatch('showCog')
          },
          methods: {
            addColumn () {
                // this.$store.dispatch('showCog')
                // this.fields.
                this.filter = null
                this.currentPage = 1
                this.modify = []
                this.modify.push({ id: '-1', _showDetails: true, ip: '', conn: '', city: '', port: '', database: '', user: '', pwd: '', subNetworkTdd: '', subNetworkFdd: '', subNetworkNbiot: '', type: '' })
                //限制add数量只能是一个
                for (var i = this.items.length - 1; i >= 0; i--) {
                    if( this.items[i].id == -1 ) {
                        this.items[i]._showDetails = true
                        return;
                    }
                }
                for (var i = this.items.length - 1; i >= 0; i--) {
                    this.modify.push(this.items[i])
                }
                this.items = this.modify.sort(function(a, b) {
                    return a.id - b.id
                })
            },
            modifyData ( item, index, button ) {
                this.modify = item
            },
            modifyOK () {
                this.$store.dispatch('uploadCog', {
                    ip: this.modify.ip,
                    conn: this.modify.conn,
                    city: this.modify.city,
                    port: this.modify.port,
                    database: this.modify.database,
                    user: this.modify.user,
                    pwd: this.modify.pwd,
                    type: this.modify.type,
                    subNetworkTdd: this.modify.subNetworkTdd,
                    subNetworkFdd: this.modify.subNetworkFdd,
                    subNetworkNbiot: this.modify.subNetworkNbiot
                })
                this.confirmModify.title = ''
                this.confirmModify.content = '修改成功'
                this.$root.$emit('bv::show::modal', 'confirmModify', '#confirmModify')
            },
            del ( item, index, button ) {
                this.delete = item
                this.confirm.title = '确认删除'
                this.confirm.content = JSON.stringify(item, null, 2)
                this.$root.$emit('bv::show::modal', 'confirm', button)
            },
            handleOk ( evt ) {
                // evt.preventDefault()
                this.$store.dispatch('deleteCog', {
                    ip: this.delete.ip,
                    conn: this.delete.conn,
                    city: this.delete.city,
                    port: this.delete.port,
                    database: this.delete.database,
                    user: this.delete.user,
                    pwd: this.delete.pwd,
                    type: this.delete.type,
                    subNetworkTdd: this.delete.subNetworkTdd,
                    subNetworkFdd: this.delete.subNetworkFdd,
                    subNetworkNbiot: this.delete.subNetworkNbiot
                })
                this.modify = []
                //this.$store.dispatch('showCog')
                for (var i = this.items.length - 1; i >= 0; i--) {
                    if( this.items[i].id != this.delete.id ) {
                       this.modify.push(this.items[i])
                    }
               }
                this.items = this.modify.sort(function(a, b) {
                    return a.id - b.id
                })
                this.totalRows =  this.items.length
                if ( this.items.length/this.perPage < this.currentPage ) {
                    this.currentPage = Math.ceil(this.items.length/this.perPage)
                }
            },
            //更新table
            updateok () {
                this.$store.dispatch('showCog')
                this.modify = []
                // this.newItems = []
            },
            info (item, index, button) {
              this.modalInfo.title = `Row index: ${index}`
              this.modalInfo.content = JSON.stringify(item, null, 2)
              this.$root.$emit('bv::show::modal', 'modalInfo', button)
            },
            resetModal () {
              this.modalInfo.title = ''
              this.modalInfo.content = ''
            },
            onFiltered (filteredItems) {
              // Trigger pagination to update the number of buttons/pages due to filtering
              this.totalRows = filteredItems.length
              this.currentPage = 1
            }
          }
    }
</script>