<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Jobcards
        </CCardHeader>
        <CCardBody>
          <CButton color="primary" @click="createJobcard()">Create Jobcard</CButton>
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
              <CButton color="primary" @click="showJobcard( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="editJobcard( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="deleteJobcard( item.id )">Delete</CButton>
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
  name: 'Jobcards',
  data: () => {
    return {
      items: [],
      fields: ['id', 'start_date', 'end_date', 'estimated_time', 'zone', 'facility',
       'floor', 'service_type', 'contact_person', 'phone',
       'priority', 'assigned_to','status', 'show', 'delete'],
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
    jobcardLink (id) {
      return `jobcards/${id.toString()}`
    },
    createJobcard () {
      this.$router.push({path: 'jobcards/create'});
    },
    editLink (id) {
      return `jobcards/${id.toString()}/edit`
    },
    showJobcard ( id ) {
      const jobcardLink = this.jobcardLink( id );
      this.$router.push({path: jobcardLink});
    },
    editJobcard ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    deleteJobcard ( id ) {
      let self = this;
      let jobcardId = id;
      axios.post(  this.$apiAdress + '/api/jobcards/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted jobcard.';
          self.showAlert();
          self.getJobcards();
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
    getJobcards (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/jobcards?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
        
      }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getJobcards();
  }
}
</script>
