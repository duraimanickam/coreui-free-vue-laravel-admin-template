<template>
  <CRow>
    <CCol col="12" xl="8">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Floors
        </CCardHeader>
        <CCardBody>
          <CButton color="primary" @click="createFloor()">Create Floor</CButton>
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
              <CButton color="primary" @click="showFloor( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="editFloor( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteFloor( item.id )">Delete</CButton>
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
  name: 'Floors',
  data: () => {
    return {
      items: [],
      fields: ['id', 'name', 'company', 'show', 'edit', 'delete'],
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
    floorLink (id) {
      return `floors/${id.toString()}`
    },
    createFloor () {
      this.$router.push({path: 'floors/create'});
    },
    editLink (id) {
      return `floors/${id.toString()}/edit`
    },
    showFloor ( id ) {
      const floorLink = this.floorLink( id );
      this.$router.push({path: floorLink});
    },
    editFloor ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteFloor ( id ) {
      let self = this;
      let floorId = id;
      axios.post(  this.$apiAdress + '/api/floors/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted floor.';
          self.showAlert();
          self.getFloors();
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
    getFloors (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/floors?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
        
      }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getFloors();
  }
}
</script>
