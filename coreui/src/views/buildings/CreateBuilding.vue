<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard no-header>
        <CCardBody>
          <h3>
            Create Building
          </h3>
          
          <CAlert 
          color="success" 
          :show.sync="dismissCountDown"
          closeButton
          >
          {{ message }}
        </CAlert>

          <CInput label="Name" type="text" placeholder="Name" v-model="building.name"></CInput>

          <CSelect
              label="Zone" 
              :value.sync="building.zone_id"
              :plain="true"
              :options="zones"
              @update:value="updateSelectFacility()"
            >
            </CSelect>

            <CSelect
              label="Facility" 
              :value.sync="building.facility_id"
              :plain="true"
              :options="facilities"
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
  name: 'CreateBuilding',
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
        building: {
          name: '',
          facility_id: 0,
          zone_id: 0
        },
        message: '',
        dismissSecs: 20,
        dismissCountDown: 0,
        showDismissibleAlert: true,
        zones: [],
        facilities: [],
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    updateSelectFacility(){
        let self = this;
        axios.get(  this.$apiAdress + '/api/get-facilities?token=' + localStorage.getItem("api_token") + '&zone_id=' + self.building.zone_id
        )
        .then(function (response) {
          self.facilities = [{label: 'Has no facility', value: 'none'}]
          if(response.data.facilities.length>0){
            let firstOption = [{label:'-Select-',value:''}];
            self.facilities = [...firstOption ,...response.data.facilities]
            
          }
          
         // for(let i = 0; i<response.data.length; i++){
          //  self.facilities.push(response.data[i])
         // }
        }).catch(function (error) {
          console.log(error);
          self.$router.push({ path: 'login' }); 
        });
    },
    store() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/buildings?token=' + localStorage.getItem("api_token"),
          self.building
        )
        .then(function (response) {
            self.facility = {
              name: '',
              zone_id: 0,
              
            };
            self.message = 'Successfully created building.';
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
    axios.get(  this.$apiAdress + '/api/buildings/create?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
      let firstOption = [{label:'-Select-',value:''}];
      self.zones = [...firstOption ,...response.data.zones]
      
    }).catch(function (error) {
        console.log(error);
       // self.$router.push({ path: '/login' });
    });
  }
}

</script>
