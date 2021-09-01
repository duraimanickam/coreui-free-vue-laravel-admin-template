<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Facility
          </h3>
          
          <CAlert 
          color="success" 
          :show.sync="dismissCountDown"
          closeButton
          >
          {{ message }}
        </CAlert>

          <CInput label="Name" type="text" placeholder="Name" v-model="facility.name"></CInput>

          <CSelect
              label="Zone" 
              :value.sync="facility.zone_id"
              :plain="true"
              :options="zones"
            >
            </CSelect>

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
  name: 'CreateFacility',
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
        facility: {
          name: '',
          zone_id: 0
        },
        message: '',
        dismissSecs: 20,
        dismissCountDown: 0,
        showDismissibleAlert: true,
        zones: [],
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    store() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/facilities?token=' + localStorage.getItem("api_token"),
          self.facility
        )
        .then(function (response) {
            self.facility = {
              name: '',
              zone_id: 0,
              
            };
            self.message = 'Successfully created facility.';
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
    axios.get(  this.$apiAdress + '/api/facilities/create?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        
        let firstOption = [{label:'-Select-',value:''}];
        self.zones = [...firstOption ,...response.data.zonelist]
    }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
    });
  }
}

</script>
