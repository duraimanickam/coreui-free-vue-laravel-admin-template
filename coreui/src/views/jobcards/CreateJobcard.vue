<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Jobcard
          </h3>
          
          <CAlert 
          color="success" 
          :show.sync="dismissCountDown"
          closeButton
          >
          {{ message }}
        </CAlert>
        <CRow>
          <CCol sm="4">
          <CInput label="Date" type="date" placeholder="Date" v-model="jobcard.start_date"></CInput>
          </CCol>
          <CCol sm="4">
            <CInput label="Date Of Completion" type="date" placeholder="Date ofcompletion" v-model="jobcard.end_date"></CInput>
          </CCol>
          <CCol sm="4">
            <CInput label="Estimated Time In Hours" type="number" placeholder="Estimated Time In Hours" v-model="jobcard.estimated_time"></CInput>
          </CCol>
        </CRow>
        <CRow>
          <CCol sm="4">  
          <CSelect
              label="Zone" 
              :value.sync="jobcard.zone_id"
              :plain="true"
              :options="zones"
              @update:value="updateSelectFacility()"
            >
            </CSelect>
            </CCol>
            <CCol sm="4">  
            <CSelect
              label="Facility" 
              :value.sync="jobcard.facility_id"
              :plain="true"
              :options="facilities"
              @update:value="updateSelectBuilding()"
            >
            </CSelect>
            </CCol>
            <CCol sm="4">  
            <CSelect
              label="Building" 
              :value.sync="jobcard.building_id"
              :plain="true"
              :options="buildings"
              @update:value="updateSelectCompany()"
            >
            </CSelect>
            </CCol>
            </CRow>
          <CRow>
            <CCol sm="4">
            <CSelect
              label="Company" 
              :value.sync="jobcard.company_id"
              :plain="true"
              :options="companies"
              @update:value="updateSelectFloor()"
            >
            </CSelect>
            </CCol>
            <CCol sm="4">
            <CSelect
              label="Floor" 
              :value.sync="jobcard.floor_id"
              :plain="true"
              :options="floors"
            >
            </CSelect>
            </CCol>
            <CCol sm="4">
            <CSelect
              label="Service Type" 
              :value.sync="jobcard.service_type"
              :plain="true"
              :options="[{label:'--Select--',value:''},{label:'Service 1',value:'service1'}, {label:'Service 2',value:'service2'}]"
            >
            </CSelect>
            </CCol>
          </CRow>  
          
            <CRow>
              <CCol sm="4">
               <CInput label="Contact Person" type="text" placeholder="Contact Person" v-model="jobcard.contact_person"></CInput>
              </CCol>
              <CCol sm="4">
                <CInput label="Phone" type="text" placeholder="Phone" v-model="jobcard.phone"></CInput>
              </CCol>
              <CCol sm="4">
              <CSelect
              label="Priority" 
              :value.sync="jobcard.priority"
              :plain="true"
              :options="[{label:'--Select--',value:''},{label:'Priority1',value:'priority1'}, {label:'Priority2',value:'priority2'}]"
            >
            </CSelect>
              </CCol>
            </CRow>

            <CRow>
              <CCol sm="4">
                <CSelect
                label="Assigned To" 
                :value.sync="jobcard.assigned_to"
                :plain="true"
                :options="users"
              >
              </CSelect>
              </CCol>  
            <CCol sm="4">
            <CSelect
              label="Status" 
              :value.sync="jobcard.status"
              :plain="true"
              :options="[{label:'-Select-',value:''},{label:'Status 1',value:'status1'}, {label:'Status 2',value:'status2'}]"
            >
            </CSelect>
            </CCol>
            </CRow>
          
          
            
            <CTextarea label="Description" placeholder="Description of Job" v-model="jobcard.description"></CTextarea>
            <CButton color="primary" @click="store()">Create</CButton>
            <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreateJobcard',
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
        jobcard: {
          start_date: '',
          end_date: '',
          estimated_time: '',
          zone_id: 0,
          facility_id:0,
          building_id:0,
          company_id:0,
          floor_id:0,
          service_type:'',
          contact_person: '',
          phone:'',
          priority:'',
          assigned_to:0,
          status:'',
          description:''
          
        },
        message: '',
        dismissSecs: 20,
        dismissCountDown: 0,
        showDismissibleAlert: true,
        zones: [],
        facilities: [],
        floors: [],
        companies: [],
        buildings: [],
        users:[]
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    updateSelectFacility(){
        let self = this;
        axios.get(  this.$apiAdress + '/api/get-facilities?token=' + localStorage.getItem("api_token") + '&zone_id=' + self.jobcard.zone_id
        )
        .then(function (response) {
          self.facilities = [{label: 'Has no facility', value: 'none'}]
          if(response.data.facilities.length>0){
            let firstOption = [{label:'-Select-',value:''}];
            self.facilities = [...firstOption ,...response.data.facilities]
            
          }
          
         
        }).catch(function (error) {
          console.log(error);
          self.$router.push({ path: 'login' }); 
        });
    },
    updateSelectBuilding(){
        let self = this;
        axios.get(  this.$apiAdress + '/api/get-buildings?token=' + localStorage.getItem("api_token") + '&facility_id=' + self.jobcard.facility_id
        )
        .then(function (response) {
          self.buildings = [{label: 'Has no building', value: 'none'}]
          if(response.data.buildings.length>0){
            let firstOption = [{label:'-Select-',value:''}];
            self.buildings = [...firstOption ,...response.data.buildings]
            
          }
         
        }).catch(function (error) {
          console.log(error);
          self.$router.push({ path: 'login' }); 
        });
    },
    updateSelectCompany(){
        let self = this;
        axios.get(  this.$apiAdress + '/api/get-companies?token=' + localStorage.getItem("api_token") + '&building_id=' + self.jobcard.building_id
        )
        .then(function (response) {
          self.companies = [{label: 'Has no company', value: ''}]
          if(response.data.companies.length>0){
            let firstOption = [{label:'-Select-',value:''}];
            self.companies = [...firstOption ,...response.data.companies]
            
          }
          
         
        }).catch(function (error) {
          console.log(error);
          self.$router.push({ path: 'login' }); 
        });
    },
    updateSelectFloor(){
        let self = this;
        axios.get(  this.$apiAdress + '/api/get-floors?token=' + localStorage.getItem("api_token") + '&company_id=' + self.jobcard.company_id
        )
        .then(function (response) {
          self.floors = [{label: 'Has no floor', value: ''}]
          if(response.data.floors.length>0){
            let firstOption = [{label:'-Select-',value:''}];
            self.floors = [...firstOption ,...response.data.floors]
            
          }
          
         
        }).catch(function (error) {
          console.log(error);
          self.$router.push({ path: 'login' }); 
        });
    },
    store() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/jobcards?token=' + localStorage.getItem("api_token"),
          self.jobcard
        )
        .then(function (response) {
            self.jobcard = {
              start_date: '',
              zone_id: 0,
              
            };
            self.message = 'Successfully created jobcard.';
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
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    
  },
  mounted: function(){
    let self = this;
    
    axios.get(  this.$apiAdress + '/api/jobcards/create?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        let firstOption = [{label:'-Select-',value:''}];
        self.zones = [...firstOption ,...response.data.zones]
        self.users = [...firstOption ,...response.data.users]
        
    }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
    });
  }
}

</script>
