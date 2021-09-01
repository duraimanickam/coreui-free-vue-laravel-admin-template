<template>
  <CRow>
    <CCol col="12" lg="6">
      <CCard>
        <CCardBody>
          <h3>
            Edit Building id:  {{ $route.params.id }}
          </h3>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
            <CInput label="Name" type="text" placeholder="Name" v-model="building.name"/>
            <CSelect
              label="Facility" 
              :value.sync="building.facility_id"
              :plain="true"
              :options="facilities"
            >
            </CSelect>
          <CButton color="primary" @click="update()">Save</CButton>
          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'EditBuilding',
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
          id: null,
          name: '',
          facility_id: 0,
        },
        message: '',
        dismissSecs: 7,
        dismissCountDown: 0,
        facilities: [],
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    update() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/buildings/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"),
        {
            _method: 'PUT',
            name:  self.building.name
        })
        .then(function (response) {
            self.message = 'Successfully updated building.';
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
              self.$router.push({ path: '/login' }); 
            }
        });
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/buildings/' + self.$route.params.id + '/edit?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.building = response.data.building;
        self.facilities = response.data.facilities;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
    });
  }
}

/*
      items: (id) => {
        const user = usersData.find( user => user.id.toString() === id)
        const userDetails = user ? Object.entries(user) : [['id', 'Not found']]
        return userDetails.map(([key, value]) => {return {key: key, value: value}})
      },
*/

</script>
