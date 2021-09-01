<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard no-header>
        <CCardBody>

          <h3>Jobcard id:  {{ $route.params.id }}</h3>

          <CRow>
          <CCol sm="4">
              <label class="font-weight-bold">Date</label>
              <p>{{jobcard.start_date}}</p>
          </CCol>
          <CCol sm="4">
            <label class="font-weight-bold">Date Of Completion</label>
              <p>{{jobcard.end_date}}</p>
          </CCol>
          <CCol sm="4">
            <label class="font-weight-bold">Estimated Time</label>
              <p>{{jobcard.estimated_time}}</p>
          </CCol>
        </CRow>
        <CRow>
          <CCol sm="4">  
              <label class="font-weight-bold">Zone</label>
              <p>{{jobcard.zone}}</p>
            </CCol>
            <CCol sm="4">  
                <label class="font-weight-bold">Facility</label>
                <p>{{jobcard.facility}}</p>
            </CCol>
            <CCol sm="4">  
                <label class="font-weight-bold">Building</label>
                <p>{{jobcard.building}}</p>
            </CCol>
            </CRow>
          <CRow>
            <CCol sm="4">
            <label class="font-weight-bold">Company</label>
                <p>{{jobcard.company}}</p>
            </CCol>
            <CCol sm="4">
            <label class="font-weight-bold">Floor</label>
            <p>{{jobcard.floor}}</p>
            </CCol>
            <CCol sm="4">
            <label class="font-weight-bold">Service Type</label>
                <p>{{jobcard.service_type}}</p>
            </CCol>
          </CRow>  
          
            <CRow>
              <CCol sm="4">
               <label class="font-weight-bold">Contact Person</label>
                <p>{{jobcard.contact_person}}</p>
            </CCol>
              <CCol sm="4">
               <label class="font-weight-bold">Phone</label>
                <p>{{jobcard.phone}}</p> 
            </CCol>
              <CCol sm="4">
              <label class="font-weight-bold">Priority</label>
                <p>{{jobcard.priority}}</p> 
              </CCol>
            </CRow>

            <CRow>
              <CCol sm="4">
                <label class="font-weight-bold">Assigned To</label>
                <p>{{jobcard.assigned_to}}</p> 
              </CCol>  
            <CCol sm="4">
             <label class="font-weight-bold">Status</label>
                <p>{{jobcard.status}}</p> 
            </CCol>
            </CRow>
           <hr/>
            
            <h3>Updates</h3>

           <CDataTable
            hover
            striped
            :items="logitems"
            :fields="logfields"
            
              >
              
            </CDataTable>

            <table class="table">
              <thead>
                <tr><td></td><td>Description</td><td>Status</td><td></td></tr>
              </thead>
              <tbody>
              <tr v-for="(update, k) in updates" :key="k">
                  <td scope="row" class="trashIconContainer">
                      <i class="danger" @click="deleteRow(k, update)">X</i>
                  </td>
                  
                  <td>
                      <input class="form-control" type="text" v-model="update.description" />
                      
                  </td>
                  <td>
                    <CSelect
                      :value.sync="update.status"
                      :options="[{label:'Pending',value:'Pending'},{label:'Impeded',value:'Impeded'}, {label:'Completed',value:'Completed'}]"
                    >
                    </CSelect>
                  </td>
                  <td>
                    <CButton color="success" @click="addRow">+</CButton>
                  </td>
                  
                  
              </tr>
            </tbody>
  </table>

                
            
          <CButton color="primary" @click="goBack">Back</CButton>
          <CButton color="success" @click="saveUpdate">Save Update</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'Jobcard',
  /*
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  */
  data: () => {
    return {
      logitems: [],
      logfields: ['date', 'user', 'description', 'status'], 
      updates: [{
                date: '',
                user: '',
                description: '',
                status:'Pending'
            }],
      jobcard: {
          id: null,
          start_date: '',
          end_date: '',
          zone_id: 0,
        },
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    addRow() {
            this.updates.push({
                date: '',
                user: '',
                description: '',
                status: '',
                
            });
    },
    saveUpdate() {


          let self = this;
          axios.post(  this.$apiAdress + '/api/jobcard-updates?jobcard_id='+self.$route.params.id+'&token=' + localStorage.getItem("api_token"),
          {
            updates:JSON.stringify(self.updates)
          } 
          )
          .then(function (response) {
            
            self.message = 'Successfully added updates.';
            self.showAlert();
          }).catch(function (error) {
            if(error.response.data.message == 'The given data was invalid.'){
              self.message = '';
              for (let key in error.response.data.errors) {
                if (error.response.data.errors.hasOwnProperty(key)) {
                  self.message += error.response.data.errors[key][0] + '  ';
                }
              }
              self.showAlert();
            }else{
              console.log(error);
              self.$router.push({ path: 'login' }); 
            }
        });

          //  console.log(JSON.stringify(this.updates));
        },

    deleteRow(index, update) {
            var idx = this.updates.indexOf(update);
            console.log(idx, index);
            if (idx > -1) {
                this.updates.splice(idx, 1);
            }
            
        },    
    
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/jobcards/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
      self.jobcard = response.data
      var logs = response.data.logs
      if(logs.length>0){
         self.logitems = logs
      }
      else{
        //  self.updates =[{Date:"No Updates Yet",User:"", Description:""}]
      }
    }).catch(function (error) {
      console.log(error);
     // self.$router.push({ path: '/login' })
    });
  }
}


</script>
