import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from './assets/icons/icons.js'
import store from './store'

//Vue.prototype.$apiAdress = 'http://localhost:8000'
Vue.prototype.$apiAdress = 'http://carewelapi.arhal.co.in'
Vue.config.performance = true
Vue.use(CoreuiVue)

    new Vue({
      el: '#app',
      router,
      store,  
      icons,
      template: '<App/>',
      components: {
        App
      },
    })

