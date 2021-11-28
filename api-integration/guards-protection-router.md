# 🔏 Guards Protection Router

{% embed url="https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards" %}

1. ทำการเปิดไฟล์ main.js ภายใต้ src/

![](<../.gitbook/assets/image (117).png>)

จากนั้นทำการ copy code ด้านล่างนี้ไปแทนที่

{% code title="main.js" %}
```javascript
import Vue from 'vue'
import App from './App.vue'

// 1. import bootstrap
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
// 5. import vue-router
import VueRouter from 'vue-router'
// 2. Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// import LoginPage component
import LoginPage from "@/components/LoginPage/index.vue"
// import CreateAccountPage component
import CreateAccountPage from "@/components/CreateAccountPage/index.vue"
// import MainLayout component
import MainLayout from "@/components/MainLayout/index.vue"
// import ProjectList component
import ProjectList from "@/components/ProjectList/index.vue"
// import ProjectContent component
import ProjectContent from "@/components/ProjectContent/index.vue"
// import AccountSetting component
import AccountSetting from "@/components/AccountSetting/index.vue"
Vue.config.productionTip = false

// defind you route
const routes = [
  { path: '/', component: LoginPage, name: "login" },
  { path: '/create-account', component: CreateAccountPage, name: "create-account" },
  {
    path: '/main', component: MainLayout,
    children: [
      {
        path: "/project-list",
        name: "project-list",
        component: ProjectList
      },
      {
        path: "/project/content/:project_id",
        name: "project-content",
        component: ProjectContent
      },
      {
        path: "/account/setting",
        component: AccountSetting
      }
    ]
  }
]

// router object
const router = new VueRouter({
  routes // short for `routes: routes`
})

// Guards route
router.beforeEach((to, from, next) => {
  var token = localStorage.getItem("token") 
  var isAuthenticated = (token != undefined) && (token != "")
  if ((to.name !== 'login' && to.name !== 'create-account') && !isAuthenticated) next({ name: 'login' })
  else next()
})


// 3. Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// 4.Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)
// 6. Make vue-router available
Vue.use(VueRouter)

new Vue({
  // register router to vue instant
  router,
  render: h => h(App),
}).$mount('#app')ja
```
{% endcode %}
