<template>
  <CRow>
    <CCol col="12" xl="8">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Facilities
        </CCardHeader>
        <CCardBody>
          <CButton color="primary" @click="createFacility()">Create Unit</CButton>
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
            :items-per-page="20"
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
              <CButton color="primary" @click="showFacility( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="editFacility( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteFacility( item.id )">Delete</CButton>
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
  name: 'Facilities',
  data: () => {
    return {
      items: [],
      fields: ['id', 'name', 'zone', 'show', 'edit', 'delete'],
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
    facilityLink (id) {
      return `facilities/${id.toString()}`
    },
    createFacility () {
      this.$router.push({path: 'facilities/create'});
    },
    editLink (id) {
      return `facilities/${id.toString()}/edit`
    },
    showFacility ( id ) {
      const facilityLink = this.facilityLink( id );
      this.$router.push({path: facilityLink});
    },
    editFacility ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteFacility ( id ) {
      let self = this;
      let facilityId = id;
      axios.post(  this.$apiAdress + '/api/facilities/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted unit.';
          self.showAlert();
          self.getFacilities();
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
    getFacilities (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/facilities?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
        
      }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getFacilities();
  }
}
</script>
