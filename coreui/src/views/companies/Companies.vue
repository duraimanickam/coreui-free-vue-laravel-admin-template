<template>
  <CRow>
    <CCol col="12" xl="8">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Buildings
        </CCardHeader>
        <CCardBody>
          <CButton color="primary" @click="createCompany()">Create Company</CButton>
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
              <CButton color="primary" @click="showBuilding( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="editBuilding( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteBuilding( item.id )">Delete</CButton>
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
  name: 'Companies',
  data: () => {
    return {
      items: [],
      fields: ['id', 'name', 'building', 'show', 'edit', 'delete'],
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
    companyLink (id) {
      return `companies/${id.toString()}`
    },
    createCompany () {
      this.$router.push({path: 'companies/create'});
    },
    editLink (id) {
      return `companies/${id.toString()}/edit`
    },
    showCompany ( id ) {
      const companyLink = this.companyLink( id );
      this.$router.push({path: companyLink});
    },
    editCompany ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteCompany ( id ) {
      let self = this;
      let buildingId = id;
      axios.post(  this.$apiAdress + '/api/companies/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted company.';
          self.showAlert();
          self.getCompanies();
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
    getCompanies (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/companies?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
        
      }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getCompanies();
  }
}
</script>
