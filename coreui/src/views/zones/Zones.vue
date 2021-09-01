<template>
  <CRow>
    <CCol col="12" xl="8">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Zones
        </CCardHeader>
        <CCardBody>
          <CButton color="primary" @click="createZone()">Create Zone</CButton>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CDataTable
            hover
            striped
            :items="items"
            :fields="fields"
            :items-per-page="5"
            :columnFilter='{ external: false, lazy: false }'
            pagination
          >
          <template #status="{item}">
            <td>
              <CBadge :color="getBadge(item.status)">{{ item.status }}</CBadge>
            </td>
          </template>
          <template #show="{item}">
            <td>
              <CButton color="primary" @click="showZone( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="editZone( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteZone( item.id )">Delete</CButton>
            </td>
          </template>
        </CDataTable>
        </CCardBody>
      </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Zones',
  data: () => {
    return {
      items: [],
      fields: ['id', 'name', 'show', 'edit', 'delete'],
      currentPage: 1,
      perPage: 1,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    getBadge (status) {
      return status === 'Active' ? 'success'
        : status === 'Inactive' ? 'secondary'
          : status === 'Pending' ? 'warning'
            : status === 'Banned' ? 'danger' : 'primary'
    },
    zoneLink (id) {
      return `zones/${id.toString()}`
    },
    createZone () {
      this.$router.push({path: 'zones/create'});
    },
    editLink (id) {
      return `zones/${id.toString()}/edit`
    },
    showZone ( id ) {
      const zoneLink = this.zoneLink( id );
      this.$router.push({path: zoneLink});
    },
    editZone ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteZone ( id ) {
      let self = this;
      let zoneId = id;
      axios.post(  this.$apiAdress + '/api/zones/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted zone.';
          self.showAlert();
          self.getZones();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getZones (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/zones?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
        
      }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getZones();
  }
}
</script>
