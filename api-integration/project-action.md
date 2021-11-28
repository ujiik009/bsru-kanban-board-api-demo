# 🌐 Project Action

## 1. Get Project List

1.1 ทำการเปิดไฟล์ index.vue ภายใต้ src/components/ProjectList

![](<../.gitbook/assets/image (148).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div class="content-page">
    <div class="container-box">
      <div class="content-left">
        <div id="project-box">
          <div id="project-box-header" class="font-impact">
            <div>
              Project
              <b-button variant="primary" @click="open_modal_project_create"
                >Create Project</b-button
              >
            </div>

            <div>{{ date_now }}</div>
          </div>
          <div id="summary-box">
            <div>
              <div
                style="
                  display: flex;
                  justify-content: space-between;
                  width: 450px;
                  background-color: #e8e8e8;
                  padding: 10px 20px 10px 20px;
                  border-radius: 20px;
                "
              >
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ in_progress }}
                  </div>
                  <div style="font-weight: bold">In Progress</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ upcomeing }}
                  </div>
                  <div style="font-weight: bold">Upcomeing</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ total_task }}
                  </div>
                  <div style="font-weight: bold">Total Task</div>
                </div>
              </div>
            </div>
            <div>
              <b-button
                @click="view_page_active = 'card'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'card',
                }"
                ><b-icon icon="grid-3x3-gap-fill"></b-icon
              ></b-button>
              <b-button
                @click="view_page_active = 'list'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'list',
                }"
                ><b-icon icon="list-ul"></b-icon
              ></b-button>
            </div>
          </div>
          <div id="view-content">
            <ProjectViewCard
              :projects="project"
              v-if="view_page_active == 'card'"
            />
            <ProjectViewList
              :projects="project"
              v-if="view_page_active == 'list'"
            />
          </div>
        </div>
      </div>
      <div class="content-right">
        <div id="my-task-today">
          <div style="color: #ffffff; font-size: 24px" class="font-impact">
            <b-icon icon="card-checklist"></b-icon>
            <span style="margin-left: 10px">My Task Today</span>
          </div>
          <div class="my-task-today-zone">
            <div class="task" v-for="(task, index) in tasks" :key="index">
              <div style="width: 50px">
                <div class="round" style="margin-top: 15px; margin-left: 10px">
                  <input
                    type="checkbox"
                    :id="`${'checkbox'}${index}`"
                    :checked="task.status"
                    @change="task.status = !task.status"
                  />
                  <label :for="`${'checkbox'}${index}`"></label>
                </div>
              </div>
              <div>
                <div class="overme">{{ task.name }}</div>
                <div>
                  <b-badge class="badge-label">{{ task.label }}</b-badge>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal project create -->
    <div>
      <b-modal
        v-model="modal_project_create"
        title="Create Project"
        hide-footer
        size="md"
      >
        <div class="my-4">
          <b-row>
            <b-col>
              <b-form-group
                label="Project Name"
                label-for="project-name"
                style="margin-bottom: 10px"
              >
                <b-form-input
                  id="project-name"
                  v-model="record_project.name"
                  type="text"
                  placeholder="Enter you project name"
                  required
                ></b-form-input> </b-form-group
            ></b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Description" label-for="description">
                <b-form-textarea
                  id="description"
                  v-model="record_project.description"
                  placeholder="Enter something..."
                  rows="6"
                  max-rows="6"
                ></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Start Date">
                <b-form-datepicker
                  id="start_date"
                  class="mb-2"
                  v-model="record_project.start_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="End Date">
                <b-form-datepicker
                  id="end_date"
                  class="mb-2"
                  v-model="record_project.end_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
          </b-row>
          <div style="text-align: right; margin-top: 10px">
            <b-button
              style="margin-right: 10px"
              @click="modal_project_create = false"
              >Cancel</b-button
            >
            <b-button class="btn-primary" @click="create_project"
              >Create</b-button
            >
          </div>
        </div>
      </b-modal>
    </div>
    <!-- modal project create -->
  </div>
</template>

<script>
import moment from "moment";
import ProjectViewList from "@/components/ProjectList/components/ProjectViewList.vue";
import ProjectViewCard from "@/components/ProjectList/components/ProjectViewCard.vue";
import axios from "axios";
export default {
  components: {
    ProjectViewList,
    ProjectViewCard,
  },
  async created() {
    await this.get_list_project();
  },
  data() {
    return {
      record_project: {
        name: "BSRU App",
        description: "Project make BSRU app with java Day 2",
        start_date: "2020-01-01",
        end_date: "2021-12-31",
      },
      modal_project_create: false,
      view_page_active: "card", // list,card
      date_now: moment().format("DD MMMM YYYY"),
      in_progress: 20,
      upcomeing: 26,
      total_task: 46,
      tasks: [
        {
          name: "Create Login Page",
          label: "Design",
          status: false,
        },
        {
          name: "Create Register Page",
          label: "Design",
          status: true,
        },
      ],
      project: [
        
      ],
    };
  },
  methods: {
    get_list_project() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/list_project.php",
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          this.in_progress = 0;
          this.upcomeing = 0;
          this.total_task = 0;
          if (res.data.status == true) {
            res.data.data = res.data.data.map((project) => {
              var task_done = project.tasks.filter((x) => x.state == "done");

              var task_todo = project.tasks.filter((x) => x.state == "todo");

              var task_in_progress = project.tasks.filter(
                (x) => x.state == "in_progress"
              );

              this.upcomeing += task_todo.length;
              this.in_progress += task_in_progress.length;
              this.total_task += project.tasks.length;
              project.task_count = project.tasks.length;
              project.percent = (task_done.length / project.tasks.length) * 100;
              project.duration = project.start_date;
              return project;
            });

            this.project = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error);
        });
    },
    checked_task(event) {
      console.log(event);
      alert(555);
    },
    open_modal_project_create() {
      this.modal_project_create = true;
    },
    create_project() {
      alert("project created");
    },
  },
};
</script>

<style>
.btn-primary {
  background-color: #7741a0;
}
#view-content {
  height: calc(100% - 160px);
  overflow-y: auto;
}
.btn-view {
  background-color: white;
  color: #43435e;
}
.active-btn {
  background-color: #43435e !important;
  color: white !important;
}
.btn-view:active,
.btn-view:focus,
.btn-view:hover {
  background-color: #43435e !important;
  color: white !important;
}

#summary-box {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}
.badge-label {
  background-color: #dbf6fd;
  color: #00acd9;
}
.overme {
  width: 300px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.task {
  margin-top: 10px;
  min-height: 60px;
  background-color: white;
  border-radius: 5px;
  display: flex;
}
.my-task-today-zone {
  margin-top: 15px;
  height: 90%;
  overflow-y: auto;
}
/* width */
.my-task-today-zone::-webkit-scrollbar {
  width: 5px;
}

/* Track */
.my-task-today-zone::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
.my-task-today-zone::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
.my-task-today-zone::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* width */
#view-content::-webkit-scrollbar {
  width: 5px;
}

/* Track */
#view-content::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
#view-content::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
#view-content::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.content-page {
  height: 100%;
  overflow-y: auto;
  background-color: #e7e7e7;
  padding-left: 20px;
  padding-right: 20px;
}
.container-box {
  display: flex;
  height: inherit;
}
.content-left {
  padding: 10px;
  flex: 3;
  height: inherit;
}
.content-right {
  flex: 1;
  padding: 10px;
  height: inherit;
}
#project-box {
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  height: 100%;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
}

#my-task-today {
  border-radius: 10px;
  padding: 10px;
  background-color: #43435e;
  height: 100%;
}

#project-box-header {
  font-size: 32px;
  color: #43435e;
  display: flex;
  justify-content: space-between;
}

.round {
  position: relative;
}

.round label {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  height: 28px;
  left: 0;
  position: absolute;
  top: 0;
  width: 28px;
}

.round label:after {
  border: 2px solid #fff;
  border-top: none;
  border-right: none;
  content: "";
  height: 6px;
  left: 7px;
  opacity: 0;
  position: absolute;
  top: 8px;
  transform: rotate(-45deg);
  width: 12px;
}

.round input[type="checkbox"] {
  visibility: hidden;
}

.round input[type="checkbox"]:checked + label {
  background-color: #66bb6a;
  border-color: #66bb6a;
}

.round input[type="checkbox"]:checked + label:after {
  opacity: 1;
}
</style>
```
{% endcode %}

## 2. Create Project

2.1 ทำการเปิดไฟล์ index.vue ภายใต้ src/components/ProjectList

![](<../.gitbook/assets/image (22).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div class="content-page">
    <div class="container-box">
      <div class="content-left">
        <div id="project-box">
          <div id="project-box-header" class="font-impact">
            <div>
              Project
              <b-button variant="primary" @click="open_modal_project_create"
                >Create Project</b-button
              >
            </div>

            <div>{{ date_now }}</div>
          </div>
          <div id="summary-box">
            <div>
              <div
                style="
                  display: flex;
                  justify-content: space-between;
                  width: 450px;
                  background-color: #e8e8e8;
                  padding: 10px 20px 10px 20px;
                  border-radius: 20px;
                "
              >
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ in_progress }}
                  </div>
                  <div style="font-weight: bold">In Progress</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ upcomeing }}
                  </div>
                  <div style="font-weight: bold">Upcomeing</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ total_task }}
                  </div>
                  <div style="font-weight: bold">Total Task</div>
                </div>
              </div>
            </div>
            <div>
              <b-button
                @click="view_page_active = 'card'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'card',
                }"
                ><b-icon icon="grid-3x3-gap-fill"></b-icon
              ></b-button>
              <b-button
                @click="view_page_active = 'list'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'list',
                }"
                ><b-icon icon="list-ul"></b-icon
              ></b-button>
            </div>
          </div>
          <div id="view-content">
            <ProjectViewCard
              :projects="project"
              v-if="view_page_active == 'card'"
            />
            <ProjectViewList
              :projects="project"
              v-if="view_page_active == 'list'"
            />
          </div>
        </div>
      </div>
      <div class="content-right">
        <div id="my-task-today">
          <div style="color: #ffffff; font-size: 24px" class="font-impact">
            <b-icon icon="card-checklist"></b-icon>
            <span style="margin-left: 10px">My Task Today</span>
          </div>
          <div class="my-task-today-zone">
            <div class="task" v-for="(task, index) in tasks" :key="index">
              <div style="width: 50px">
                <div class="round" style="margin-top: 15px; margin-left: 10px">
                  <input
                    type="checkbox"
                    :id="`${'checkbox'}${index}`"
                    :checked="task.status"
                    @change="task.status = !task.status"
                  />
                  <label :for="`${'checkbox'}${index}`"></label>
                </div>
              </div>
              <div>
                <div class="overme">{{ task.name }}</div>
                <div>
                  <b-badge class="badge-label">{{ task.label }}</b-badge>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal project create -->
    <div>
      <b-modal
        v-model="modal_project_create"
        title="Create Project"
        hide-footer
        size="md"
      >
        <div class="my-4">
          <b-row>
            <b-col>
              <b-form-group
                label="Project Name"
                label-for="project-name"
                style="margin-bottom: 10px"
              >
                <b-form-input
                  id="project-name"
                  v-model="record_project.name"
                  type="text"
                  placeholder="Enter you project name"
                  required
                ></b-form-input> </b-form-group
            ></b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Description" label-for="description">
                <b-form-textarea
                  id="description"
                  v-model="record_project.description"
                  placeholder="Enter something..."
                  rows="6"
                  max-rows="6"
                ></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Start Date">
                <b-form-datepicker
                  id="start_date"
                  class="mb-2"
                  v-model="record_project.start_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="End Date">
                <b-form-datepicker
                  id="end_date"
                  class="mb-2"
                  v-model="record_project.end_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
          </b-row>
          <div style="text-align: right; margin-top: 10px">
            <b-button
              style="margin-right: 10px"
              @click="modal_project_create = false"
              >Cancel</b-button
            >
            <b-button class="btn-primary" @click="create_project"
              >Create</b-button
            >
          </div>
        </div>
      </b-modal>
    </div>
    <!-- modal project create -->
  </div>
</template>

<script>
import moment from "moment";
import ProjectViewList from "@/components/ProjectList/components/ProjectViewList.vue";
import ProjectViewCard from "@/components/ProjectList/components/ProjectViewCard.vue";
import axios from "axios";
export default {
  components: {
    ProjectViewList,
    ProjectViewCard,
  },
  async created() {
    await this.get_list_project();
  },
  data() {
    return {
      record_project: {
        name: "",
        description: "",
        start_date: moment().format("YYYY-MM-DD"),
        end_date: moment().format("YYYY-MM-DD"),
      },
      modal_project_create: false,
      view_page_active: "card", // list,card
      date_now: moment().format("DD MMMM YYYY"),
      in_progress: 20,
      upcomeing: 26,
      total_task: 46,
      tasks: [
        {
          name: "Create Login Page",
          label: "Design",
          status: false,
        },
        {
          name: "Create Register Page",
          label: "Design",
          status: true,
        },
      ],
      project: [],
    };
  },
  methods: {
    get_list_project() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/list_project.php",
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          this.in_progress = 0;
          this.upcomeing = 0;
          this.total_task = 0;
          if (res.data.status == true) {
            res.data.data = res.data.data.map((project) => {
              var task_done = project.tasks.filter((x) => x.state == "done");

              var task_todo = project.tasks.filter((x) => x.state == "todo");

              var task_in_progress = project.tasks.filter(
                (x) => x.state == "in_progress"
              );

              this.upcomeing += task_todo.length;
              this.in_progress += task_in_progress.length;
              this.total_task += project.tasks.length;
              project.task_count = project.tasks.length;
              project.percent = (task_done.length / project.tasks.length) * 100;

              if (isNaN(project.percent)) {
                project.percent = 0;
              }

              project.duration = project.start_date;
              return project;
            });

            this.project = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error);
        });
    },
    checked_task(event) {
      console.log(event);
      alert(555);
    },
    open_modal_project_create() {
      this.modal_project_create = true;
    },
    create_project() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/create_project.php",
          { ...this.record_project },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            alert(res.data.message);

            // fetch new list project
            this.get_list_project();

            // clear record
            this.record_project = {
              name: "",
              description: "",
              start_date: moment().format("YYYY-MM-DD"),
              end_date: moment().format("YYYY-MM-DD"),
            };
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
  },
};
</script>

<style>
.btn-primary {
  background-color: #7741a0;
}
#view-content {
  height: calc(100% - 160px);
  overflow-y: auto;
}
.btn-view {
  background-color: white;
  color: #43435e;
}
.active-btn {
  background-color: #43435e !important;
  color: white !important;
}
.btn-view:active,
.btn-view:focus,
.btn-view:hover {
  background-color: #43435e !important;
  color: white !important;
}

#summary-box {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}
.badge-label {
  background-color: #dbf6fd;
  color: #00acd9;
}
.overme {
  width: 300px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.task {
  margin-top: 10px;
  min-height: 60px;
  background-color: white;
  border-radius: 5px;
  display: flex;
}
.my-task-today-zone {
  margin-top: 15px;
  height: 90%;
  overflow-y: auto;
}
/* width */
.my-task-today-zone::-webkit-scrollbar {
  width: 5px;
}

/* Track */
.my-task-today-zone::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
.my-task-today-zone::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
.my-task-today-zone::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* width */
#view-content::-webkit-scrollbar {
  width: 5px;
}

/* Track */
#view-content::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
#view-content::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
#view-content::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.content-page {
  height: 100%;
  overflow-y: auto;
  background-color: #e7e7e7;
  padding-left: 20px;
  padding-right: 20px;
}
.container-box {
  display: flex;
  height: inherit;
}
.content-left {
  padding: 10px;
  flex: 3;
  height: inherit;
}
.content-right {
  flex: 1;
  padding: 10px;
  height: inherit;
}
#project-box {
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  height: 100%;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
}

#my-task-today {
  border-radius: 10px;
  padding: 10px;
  background-color: #43435e;
  height: 100%;
}

#project-box-header {
  font-size: 32px;
  color: #43435e;
  display: flex;
  justify-content: space-between;
}

.round {
  position: relative;
}

.round label {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  height: 28px;
  left: 0;
  position: absolute;
  top: 0;
  width: 28px;
}

.round label:after {
  border: 2px solid #fff;
  border-top: none;
  border-right: none;
  content: "";
  height: 6px;
  left: 7px;
  opacity: 0;
  position: absolute;
  top: 8px;
  transform: rotate(-45deg);
  width: 12px;
}

.round input[type="checkbox"] {
  visibility: hidden;
}

.round input[type="checkbox"]:checked + label {
  background-color: #66bb6a;
  border-color: #66bb6a;
}

.round input[type="checkbox"]:checked + label:after {
  opacity: 1;
}
</style>
```
{% endcode %}

## 3. Get Task To day

3.1 ทำการเปิดไฟล์ index.vue ภายใต้ src/components/ProjectList

![](<../.gitbook/assets/image (22).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div class="content-page">
    <div class="container-box">
      <div class="content-left">
        <div id="project-box">
          <div id="project-box-header" class="font-impact">
            <div>
              Project
              <b-button variant="primary" @click="open_modal_project_create"
                >Create Project</b-button
              >
            </div>

            <div>{{ date_now }}</div>
          </div>
          <div id="summary-box">
            <div>
              <div
                style="
                  display: flex;
                  justify-content: space-between;
                  width: 450px;
                  background-color: #e8e8e8;
                  padding: 10px 20px 10px 20px;
                  border-radius: 20px;
                "
              >
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ in_progress }}
                  </div>
                  <div style="font-weight: bold">In Progress</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ upcomeing }}
                  </div>
                  <div style="font-weight: bold">Upcomeing</div>
                </div>
                <div>
                  <div style="font-size: 32px; font-weight: bold">
                    {{ total_task }}
                  </div>
                  <div style="font-weight: bold">Total Task</div>
                </div>
              </div>
            </div>
            <div>
              <b-button
                @click="view_page_active = 'card'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'card',
                }"
                ><b-icon icon="grid-3x3-gap-fill"></b-icon
              ></b-button>
              <b-button
                @click="view_page_active = 'list'"
                v-bind:class="{
                  ['btn-view']: true,
                  ['active-btn']: view_page_active == 'list',
                }"
                ><b-icon icon="list-ul"></b-icon
              ></b-button>
            </div>
          </div>
          <div id="view-content">
            <ProjectViewCard
              :projects="project"
              v-if="view_page_active == 'card'"
            />
            <ProjectViewList
              :projects="project"
              v-if="view_page_active == 'list'"
            />
          </div>
        </div>
      </div>
      <div class="content-right">
        <div id="my-task-today">
          <div style="color: #ffffff; font-size: 24px" class="font-impact">
            <b-icon icon="card-checklist"></b-icon>
            <span style="margin-left: 10px">My Task Today</span>
          </div>
          <div class="my-task-today-zone">
            <div class="task" v-for="(task, index) in tasks" :key="index">
              <div style="width: 50px">
                <div class="round" style="margin-top: 15px; margin-left: 10px">
                  <input
                    type="checkbox"
                    :id="`${'checkbox'}${index}`"
                    :checked="task.status"
                    @change="task.status = !task.status"
                  />
                  <label :for="`${'checkbox'}${index}`"></label>
                </div>
              </div>
              <div>
                <div class="overme">{{ task.name }}</div>
                <div>
                  <b-badge class="badge-label">Due Date : {{ task.due_date }}</b-badge>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- modal project create -->
    <div>
      <b-modal
        v-model="modal_project_create"
        title="Create Project"
        hide-footer
        size="md"
      >
        <div class="my-4">
          <b-row>
            <b-col>
              <b-form-group
                label="Project Name"
                label-for="project-name"
                style="margin-bottom: 10px"
              >
                <b-form-input
                  id="project-name"
                  v-model="record_project.name"
                  type="text"
                  placeholder="Enter you project name"
                  required
                ></b-form-input> </b-form-group
            ></b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Description" label-for="description">
                <b-form-textarea
                  id="description"
                  v-model="record_project.description"
                  placeholder="Enter something..."
                  rows="6"
                  max-rows="6"
                ></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-form-group label="Start Date">
                <b-form-datepicker
                  id="start_date"
                  class="mb-2"
                  v-model="record_project.start_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
            <b-col>
              <b-form-group label="End Date">
                <b-form-datepicker
                  id="end_date"
                  class="mb-2"
                  v-model="record_project.end_date"
                ></b-form-datepicker>
              </b-form-group>
            </b-col>
          </b-row>
          <div style="text-align: right; margin-top: 10px">
            <b-button
              style="margin-right: 10px"
              @click="modal_project_create = false"
              >Cancel</b-button
            >
            <b-button class="btn-primary" @click="create_project"
              >Create</b-button
            >
          </div>
        </div>
      </b-modal>
    </div>
    <!-- modal project create -->
  </div>
</template>

<script>
import moment from "moment";
import ProjectViewList from "@/components/ProjectList/components/ProjectViewList.vue";
import ProjectViewCard from "@/components/ProjectList/components/ProjectViewCard.vue";
import axios from "axios";
export default {
  components: {
    ProjectViewList,
    ProjectViewCard,
  },
  async created() {
    await this.get_list_project();
    await this.get_task_in_progress()
  },
  data() {
    return {
      record_project: {
        name: "",
        description: "",
        start_date: moment().format("YYYY-MM-DD"),
        end_date: moment().format("YYYY-MM-DD"),
      },
      modal_project_create: false,
      view_page_active: "card", // list,card
      date_now: moment().format("DD MMMM YYYY"),
      in_progress: 0,
      upcomeing: 0,
      total_task: 0,
      tasks: [
        {
          name: "Create Login Page",
          label: "Design",
          status: false,
        },
        {
          name: "Create Register Page",
          label: "Design",
          status: true,
        },
      ],
      project: [],
    };
  },
  methods: {
    get_list_project() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/list_project.php",
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          this.in_progress = 0;
          this.upcomeing = 0;
          this.total_task = 0;
          if (res.data.status == true) {
            res.data.data = res.data.data.map((project) => {
              var task_done = project.tasks.filter((x) => x.state == "done");

              var task_todo = project.tasks.filter((x) => x.state == "todo");

              var task_in_progress = project.tasks.filter(
                (x) => x.state == "in_progress"
              );

              this.upcomeing += task_todo.length;
              this.in_progress += task_in_progress.length;
              this.total_task += project.tasks.length;
              project.task_count = project.tasks.length;
              project.percent = (task_done.length / project.tasks.length) * 100;

              if (isNaN(project.percent)) {
                project.percent = 0;
              }

              project.duration = project.start_date;
              return project;
            });

            this.project = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error);
        });
    },
    checked_task(event) {
      console.log(event);
      alert(555);
    },
    open_modal_project_create() {
      this.modal_project_create = true;
    },
    create_project() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/create_project.php",
          { ...this.record_project },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            alert(res.data.message);

            // fetch new list project
            this.get_list_project();

            // clear record
            this.record_project = {
              name: "",
              description: "",
              start_date: moment().format("YYYY-MM-DD"),
              end_date: moment().format("YYYY-MM-DD"),
            };
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    get_task_in_progress() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/task_in_progress.php",
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            res.data.data = res.data.data.map((task) => {
              task.status = false;
              return task;
            });

            this.tasks = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
  },
};
</script>

<style>
.btn-primary {
  background-color: #7741a0;
}
#view-content {
  height: calc(100% - 160px);
  overflow-y: auto;
}
.btn-view {
  background-color: white;
  color: #43435e;
}
.active-btn {
  background-color: #43435e !important;
  color: white !important;
}
.btn-view:active,
.btn-view:focus,
.btn-view:hover {
  background-color: #43435e !important;
  color: white !important;
}

#summary-box {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}
.badge-label {
  background-color: #dbf6fd;
  color: #00acd9;
}
.overme {
  width: 300px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.task {
  margin-top: 10px;
  min-height: 60px;
  background-color: white;
  border-radius: 5px;
  display: flex;
}
.my-task-today-zone {
  margin-top: 15px;
  height: 90%;
  overflow-y: auto;
}
/* width */
.my-task-today-zone::-webkit-scrollbar {
  width: 5px;
}

/* Track */
.my-task-today-zone::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
.my-task-today-zone::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
.my-task-today-zone::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* width */
#view-content::-webkit-scrollbar {
  width: 5px;
}

/* Track */
#view-content::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
#view-content::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
#view-content::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.content-page {
  height: 100%;
  overflow-y: auto;
  background-color: #e7e7e7;
  padding-left: 20px;
  padding-right: 20px;
}
.container-box {
  display: flex;
  height: inherit;
}
.content-left {
  padding: 10px;
  flex: 3;
  height: inherit;
}
.content-right {
  flex: 1;
  padding: 10px;
  height: inherit;
}
#project-box {
  padding: 20px;
  background-color: white;
  border-radius: 10px;
  height: 100%;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
}

#my-task-today {
  border-radius: 10px;
  padding: 10px;
  background-color: #43435e;
  height: 100%;
}

#project-box-header {
  font-size: 32px;
  color: #43435e;
  display: flex;
  justify-content: space-between;
}

.round {
  position: relative;
}

.round label {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 50%;
  cursor: pointer;
  height: 28px;
  left: 0;
  position: absolute;
  top: 0;
  width: 28px;
}

.round label:after {
  border: 2px solid #fff;
  border-top: none;
  border-right: none;
  content: "";
  height: 6px;
  left: 7px;
  opacity: 0;
  position: absolute;
  top: 8px;
  transform: rotate(-45deg);
  width: 12px;
}

.round input[type="checkbox"] {
  visibility: hidden;
}

.round input[type="checkbox"]:checked + label {
  background-color: #66bb6a;
  border-color: #66bb6a;
}

.round input[type="checkbox"]:checked + label:after {
  opacity: 1;
}
</style>
```
{% endcode %}

## 4. Get Project By id

4.1 ทำการเปิดไฟล์์ index.vue ภายใต้ src/components/ProjectContent

![](<../.gitbook/assets/image (6).png>)

และทำการ copy code ด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div class="content-page">
    <div class="content-body">
      <b-tabs content-class="mt-3">
        <b-tab title="Kanban Board" active>
          <div>
            <KanbanBoard :project="project" :project_id="project_id" />
          </div>
        </b-tab>
        <b-tab title="Members">
          <div>
            <Member :project="project" :project_id="project_id" />
          </div>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</template>

<script>
import KanbanBoard from "@/components/ProjectContent/components/KanbanBoard.vue";
import Member from "@/components/ProjectContent/components/Member.vue";
import axios from "axios";
export default {
  components: {
    KanbanBoard,
    Member,
  },
  async created() {
    await this.get_project_by_id();
  },
  data() {
    return {
      project_id: this.$route.params.project_id,
      project: {
        id: "",
        name: "",
        description: "",
        start_date: "",
        end_date: "",
        created_at: "",
        creator: "",
      },
    };
  },
  methods: {
    get_project_by_id() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/get_project.php?id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.project = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
  },
};
</script>

<style>
.content-page {
  height: 100%;
  overflow-y: auto;
  background-color: #e7e7e7;
  padding-left: 20px;
  padding-right: 20px;
}
.content-body {
  margin: 20px;
  background-color: white;
  height: inherit;
  border-radius: 5px;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -webkit-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.2);
  overflow-y: auto;
}
</style>
```
{% endcode %}
