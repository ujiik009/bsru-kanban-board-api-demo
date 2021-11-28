# 🌐 5. Create Project Content Page

#### 1. ทำการสร้าง Folder ที่ชื่อว่า `ProjectContent` ไว้ภายใต้ `src/components`

![](<../.gitbook/assets/image (142).png>)

แล้วจากนั้นทำการสร้าง ไฟล์ `index.vue` ภายใต้ `src/components/ProjectContent`

![](<../.gitbook/assets/image (48).png>)

ทำการ Register component กับ `vue-router` ให้ทำการเปิด ไฟล์ `main.js` ภายใต้ `src/` และทำการ copy code ชุดนี้ไปแทนที่

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
Vue.config.productionTip = false

// defind you route
const routes = [
  { path: '/', component: LoginPage },
  { path: '/create-account', component: CreateAccountPage },
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
      }
    ]
  }
]

// router object
const router = new VueRouter({
  routes // short for `routes: routes`
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
}).$mount('#app')
```
{% endcode %}

จากนั้นให้ทำการ เปิดไฟล์ `index.vue` ภายใต้ `src/components/ProjectContent แล้วทำการ copy code ด้านล้างนี้ไปแทนที่`

{% code title="index.vue" %}
```javascript
<template>
  <div class="content-page">
    <div class="content-body">
      <b-tabs content-class="mt-3">
        <b-tab title="Kanban Board" active>
            <div>
                Kanban Content
            </div>     
        </b-tab>
        <b-tab title="Members">
            <div>
                Member Content
            </div>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style>
.content-page {
  height: 100%;
  overflow-y: auto;
  background-color: #e7e7e7;
  padding-left: 20px;
  padding-right: 20px;
  /* background-color: lightcoral; */
}
.content-body {
  margin: 20px;
  background-color: white;
  height: inherit;
  border-radius: 5px;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
}
</style>
```
{% endcode %}

จากนั้นให้ ใช้คำสั่ง `npm run serve` ที่ terminal&#x20;

แล้วเปิด web browser URL :  [http://localhost:8080/#/project/content/1](http://localhost:8080/#/project/content/1) จะได้ผลลัพธ์ดังนี้

![](<../.gitbook/assets/image (93).png>)

Github : [https://github.com/ujiik009/bsru-kanban-board/tree/d404c739a3168e52997ac17dde8270ddf07c41b6](https://github.com/ujiik009/bsru-kanban-board/tree/d404c739a3168e52997ac17dde8270ddf07c41b6)
