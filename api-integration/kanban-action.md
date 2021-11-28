# 🌐 Kanban Action

## 1. List of task

1.1 ทำการเปิดไฟล์ `KanbanBoard.vue ภายใต้ src/components/ProjectContent/components`

![](<../.gitbook/assets/image (5).png>)

จากนั้นทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="KanbanBoard.vue" %}
```javascript
<template>
  <div id="view-content">
    <!-- header -->
    <div class="header">
      <div class="board-label">{{ project.name }} Kanban Board</div>
      <div>
        <b-input-group size="sm" class="mb-2">
          <b-form-input type="search" placeholder="Search"></b-form-input>
          <b-input-group-prepend is-text>
            <b-icon icon="search"></b-icon>
          </b-input-group-prepend>
        </b-input-group>
      </div>
    </div>
    <!-- header -->

    <!-- content -->
    <div class="body-column">
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Todo ({{ tasks.todo.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('todo')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'todo')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.todo"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>In Progress ({{ tasks.in_progress.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('in_progress')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'in_progress')"
          class="column-body drop_zone"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.in_progress"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Done ({{ tasks.done.length }})</span>
          <b-button size="sm" class="create-task-icon"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'done')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.done"
            :key="task_item.id"
          />
          <div>
            <!-- drop zone -->
          </div>
        </div>
      </div>
    </div>
    <!-- content -->

    <!-- modal open task -->
    <div>
      <b-modal
        v-if="open_task_modal == true"
        v-model="open_task_modal"
        title="Edit Task"
        hide-footer
        size="xl"
      >
        <p class="my-4">
          <TaskContent :task_item="task_item_drag" />
        </p>
      </b-modal>
    </div>
    <!-- modal open task -->
  </div>
</template>

<script>
import Task from "@/components/ProjectContent/components/Task.vue";
import TaskContent from "@/components/ProjectContent/components/TaskContent.vue";
import axios from "axios";
export default {
  props: {
    project_id: String,
    project: Object,
  },
  components: {
    Task,
    TaskContent,
  },
  async created() {
    await this.get_task();
  },
  data() {
    return {
      open_task_modal: false,
      create_task_modal: false,
      tasks: {
        todo: [
         
        ],
        in_progress: [],
        done: [
         
        ],
      },
      task_item_drag: null,
    };
  },
  methods: {
    get_task() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/task_list.php?project_id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.tasks.todo = res.data.data.filter((x) => x.state == "todo");
            this.tasks.in_progress = res.data.data.filter(
              (x) => x.state == "in_progress"
            );
            this.tasks.done = res.data.data.filter((x) => x.state == "done");
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    create_task_model() {},
    open_task_model(task_item) {
      this.task_item_drag = task_item;
      this.open_task_modal = true;
    },
    dragstart(task_item) {
      this.task_item_drag = task_item;
    },
    drop_zone_enter(event) {
      event.target.style.borderStyle = "dotted";
      event.target.style.borderWidth = "1px";
    },
    drop_zone_leave(event) {
      event.target.style.borderStyle = "none";
    },
    drop(event, state) {
      event.target.style.borderStyle = "none";

      if (this.task_item_drag != null) {
        let previous_state = this.task_item_drag.state;

        // remove previous_state
        this.tasks[previous_state] = this.tasks[previous_state].filter(
          (x) => x.id != this.task_item_drag.id
        );
        // push to new state
        this.task_item_drag.state = state;
        this.tasks[state].push(this.task_item_drag);

        this.task_item_drag = null;
      }
    },
  },
};
</script>

<style scoped>
.column-body {
  margin-top: 20px;
  background-color: rgba(255, 255, 255, 0.576);
  height: calc(100% - 50px);
  border-radius: 10px;
  overflow: auto;
  padding: 5px;
}
.lane-column-header {
  position: relative;
}
.create-task-icon {
  float: right;
  color: #43435e;
  border: none;
  background-color: white;
}
#view-content {
  padding: 20px;
  height: calc(100% - 160px);

  overflow-y: auto;
}
.body-column {
  overflow: auto;
}
.lane-column {
  height: 650px;
  width: 300px;
  border-radius: 10px;

  display: inline-block;
  margin: 25px;
  padding: 10px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
.header {
  display: flex;
  justify-content: space-between;
}
.board-label {
  color: #43435e;
  font-size: 30px;
  font-weight: bold;
}
</style>
```
{% endcode %}

1.2 ทำการเปิดไฟล์ Task.vue ภายใต้ `src/components/ProjectContent/components`

![](<../.gitbook/assets/image (57).png>)

`และทำการ copy code ชุดด้านล่างไปแทนที่`

{% code title="Task.vue" %}
```javascript
<template>
  <div
    id="task"
    draggable
    
    @dragstart="$emit('dragstart')"
  >
    <div class="body-task" @click="$emit('click')">
      <div class="task-title">
        {{ task.name }}
      </div>
      <div class="task-description">
        {{ task.description }}
      </div>
    </div>
    <div class="footer-task">
      <div>
        <b-avatar
          class="avatar"
          v-b-tooltip.hover
          :title="task.user_assign.full_name"
          size="30"
          variant="info"
          :text="task.user_assign.full_name.substring(0,2)"
         
        ></b-avatar>
      </div>
      <div>
        <b-icon
          @click="show_comment"
          icon="chat-dots"
          class="comment-icon"
        ></b-icon>
      </div>
    </div>
    <div class="color-label" :style="`background-color:${task.color}`"></div>
  </div>
</template>

<script>
export default {
  props: {
    task: Object,
  },
  methods: {
    show_comment() {
      alert(this.task.id);
    },
  },
};
</script>

<style scoped>
#task {
  cursor: pointer;
  margin-top: 10px;
  position: relative;
  background-color: white;
  border-radius: 10px;
  min-height: 180px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
.comment-icon {
  font-size: 24px;
  color: #43435e;
  margin-right: 5px;
  margin-bottom: 5px;
  cursor: pointer;
}
.avatar {
  margin-left: 5px;
  margin-bottom: 5px;
  background-color:antiquewhite;
}
.task-title {
  color: #43435e;
  font-weight: bold;
  margin-bottom: 5px;
}
.body-task {
  padding: 10px;
  top: 0;
  height: calc(100% - 10px);
  width: 100%;
}
.task-description {
  font-size: 14px;
  color: #797988;
  height: 100px;
  overflow-y: auto;
  margin-bottom: 50px;
}
.footer-task {
  display: flex;
  justify-content: space-between;
  height: auto;
  width: 100%;
  position: absolute;
  bottom: 10px;
}
.color-label {
  height: 10px;
  position: absolute;
  bottom: 0;

  width: 100%;
  border-bottom-right-radius: 10px;
  border-bottom-left-radius: 10px;
}
</style>
```
{% endcode %}

## 2. Create Task

1.1 ทำการเปิดไฟล์ `KanbanBoard.vue ภายใต้ src/components/ProjectContent/components`

![](<../.gitbook/assets/image (5).png>)

จากนั้นทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="KanbanBoard.vue" %}
```javascript
<template>
  <div id="view-content">
    <!-- header -->
    <div class="header">
      <div class="board-label">{{ project.name }} Kanban Board</div>
      <div>
        <b-input-group size="sm" class="mb-2">
          <b-form-input type="search" placeholder="Search"></b-form-input>
          <b-input-group-prepend is-text>
            <b-icon icon="search"></b-icon>
          </b-input-group-prepend>
        </b-input-group>
      </div>
    </div>
    <!-- header -->

    <!-- content -->
    <div class="body-column">
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Todo ({{ tasks.todo.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('todo')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'todo')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.todo"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>In Progress ({{ tasks.in_progress.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('in_progress')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'in_progress')"
          class="column-body drop_zone"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.in_progress"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Done ({{ tasks.done.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('done')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'done')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.done"
            :key="task_item.id"
          />
          <div>
            <!-- drop zone -->
          </div>
        </div>
      </div>
    </div>
    <!-- content -->

    <!-- modal open task -->
    <div>
      <b-modal
        v-if="create_task_modal == true"
        v-model="create_task_modal"
        :title="`Create Task (${task_record.state})`"
        hide-footer
        size="xl"
      >
        <div class="my-4">
          <b-form>
            <b-form-group
              label="Task Name"
              label-for="input-1"
              style="margin-bottom: 10px"
            >
              <b-form-input
                id="input-1"
                v-model="task_record.name"
                type="text"
                placeholder="Enter you task name"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group label="Description" label-for="input-2">
              <b-form-textarea
                id="input-2"
                v-model="task_record.description"
                placeholder="Enter something..."
                rows="6"
                max-rows="6"
              ></b-form-textarea>
            </b-form-group>
            <b-row style="margin-top: 10px">
              <b-col>
                <b-form-group label="Assign to">
                  <b-form-select
                    v-model="task_record.assign_to"
                    class="select-custom"
                    :options="
                      members.map((member_item) => {
                        return {
                          value: member_item.user_id,
                          text: member_item.full_name,
                        };
                      })
                    "
                  ></b-form-select>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group label="Color">
                  <b-form-input
                    class="select-custom"
                    type="color"
                    v-model="task_record.color"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group label="Due Date">
                  <b-form-datepicker
                    id="example-datepicker"
                    class="mb-2"
                    v-model="task_record.due_date"
                  ></b-form-datepicker>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
          <div style="text-align: right">
            <b-button
              style="margin-right: 10px"
              @click="create_task_modal = false"
              >Cancel</b-button
            >
            <b-button class="btn-primary" @click="create_task">Create</b-button>
          </div>
        </div>
      </b-modal>

      <b-modal
        v-if="open_task_modal == true"
        v-model="open_task_modal"
        title="Edit Task"
        hide-footer
        size="xl"
      >
        <p class="my-4">
          <TaskContent
            :members="
              members.map((member_item) => {
                return {
                  value: member_item.user_id,
                  text: member_item.full_name,
                };
              })
            "
            :task_item="task_item_drag"
          />
        </p>
      </b-modal>
    </div>
    <!-- modal open task -->
  </div>
</template>

<script>
import Task from "@/components/ProjectContent/components/Task.vue";
import TaskContent from "@/components/ProjectContent/components/TaskContent.vue";
import axios from "axios";
import moment from "moment";
export default {
  props: {
    project_id: String,
    project: Object,
  },
  components: {
    Task,
    TaskContent,
  },
  async created() {
    await this.get_task();
    await this.get_member();
  },
  data() {
    return {
      task_record: {
        name: "",
        description: "",
        state: "todo",
        assign_to: null,
        color: "",
        due_date: moment().format("YYYY-MM-DD"),
      },
      open_task_modal: false,
      create_task_modal: false,
      members: [],
      tasks: {
        todo: [],
        in_progress: [],
        done: [],
      },
      task_item_drag: null,
    };
  },
  methods: {
    create_task() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/create_task.php?project_id=" +
            this.project_id,
          {
            ...this.task_record,
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            await this.get_task();
            this.create_task_modal = false;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    get_member() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/members/list_member.php?project_id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.members = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    get_task() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/task_list.php?project_id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.tasks.todo = res.data.data.filter((x) => x.state == "todo");
            this.tasks.in_progress = res.data.data.filter(
              (x) => x.state == "in_progress"
            );
            this.tasks.done = res.data.data.filter((x) => x.state == "done");
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    create_task_model(state) {
      this.task_record.state = state;
      this.create_task_modal = true;
    },
    open_task_model(task_item) {
      this.task_item_drag = task_item;
      this.open_task_modal = true;
    },
    dragstart(task_item) {
      this.task_item_drag = task_item;
    },
    drop_zone_enter(event) {
      event.target.style.borderStyle = "dotted";
      event.target.style.borderWidth = "1px";
    },
    drop_zone_leave(event) {
      event.target.style.borderStyle = "none";
    },
    drop(event, state) {
      event.target.style.borderStyle = "none";

      if (this.task_item_drag != null) {
        let previous_state = this.task_item_drag.state;

        // remove previous_state
        this.tasks[previous_state] = this.tasks[previous_state].filter(
          (x) => x.id != this.task_item_drag.id
        );
        // push to new state
        this.task_item_drag.state = state;
        this.tasks[state].push(this.task_item_drag);

        this.task_item_drag = null;
      }
    },
  },
};
</script>

<style scoped>
.column-body {
  margin-top: 20px;
  background-color: rgba(255, 255, 255, 0.576);
  height: calc(100% - 50px);
  border-radius: 10px;
  overflow: auto;
  padding: 5px;
}
.lane-column-header {
  position: relative;
}
.create-task-icon {
  float: right;
  color: #43435e;
  border: none;
  background-color: white;
}
#view-content {
  padding: 20px;
  height: calc(100% - 160px);

  overflow-y: auto;
}
.body-column {
  overflow: auto;
}
.lane-column {
  height: 650px;
  width: 300px;
  border-radius: 10px;

  display: inline-block;
  margin: 25px;
  padding: 10px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
.header {
  display: flex;
  justify-content: space-between;
}
.board-label {
  color: #43435e;
  font-size: 30px;
  font-weight: bold;
}
.select-custom {
  /* padding: 5px; */
  height: 36px;
  width: 100%;
}
</style>
```
{% endcode %}

## 3. Update task

3.1 ทำการเปิดไฟล์ `KanbanBoard.vue ภายใต้ src/components/ProjectContent/components`

![](<../.gitbook/assets/image (5).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="KanbanBoard.vue" %}
```javascript
<template>
  <div id="view-content">
    <!-- header -->
    <div class="header">
      <div class="board-label">{{ project.name }} Kanban Board</div>
      <div>
        <b-input-group size="sm" class="mb-2">
          <b-form-input
            type="search"
            placeholder="Search"
            @keyup="filter_task"
            v-model="text_filter"
          ></b-form-input>
          <b-input-group-prepend is-text>
            <b-icon icon="search"></b-icon>
          </b-input-group-prepend>
        </b-input-group>
      </div>
    </div>
    <!-- header -->

    <!-- content -->
    <div class="body-column">
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Todo ({{ tasks.todo.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('todo')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'todo')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.todo.filter((x) => {
              var re = new RegExp(text_filter, 'gi');
              return re.test(x.name);
            })"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>In Progress ({{ tasks.in_progress.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('in_progress')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'in_progress')"
          class="column-body drop_zone"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.in_progress.filter((x) => {
              var re = new RegExp(text_filter, 'gi');
              return re.test(x.name);
            })"
            :key="task_item.id"
          />
        </div>
      </div>
      <div class="lane-column">
        <div class="lane-column-header">
          <span>Done ({{ tasks.done.length }})</span>
          <b-button
            size="sm"
            class="create-task-icon"
            @click="create_task_model('done')"
            ><b-icon icon="plus"></b-icon
          ></b-button>
        </div>
        <div
          @dragenter.prevent="drop_zone_enter"
          @dragover.prevent
          @dragleave.prevent="drop_zone_leave"
          @drop.prevent="drop($event, 'done')"
          class="drop_zone column-body"
        >
          <Task
            @click="open_task_model(task_item)"
            @dragstart="dragstart(task_item)"
            :task="task_item"
            v-for="task_item in tasks.done.filter((x) => {
              var re = new RegExp(text_filter, 'gi');
              return re.test(x.name);
            })"
            :key="task_item.id"
          />
          <div>
            <!-- drop zone -->
          </div>
        </div>
      </div>
    </div>
    <!-- content -->

    <!-- modal open task -->
    <div>
      <b-modal
        v-if="create_task_modal == true"
        v-model="create_task_modal"
        :title="`Create Task (${task_record.state})`"
        hide-footer
        size="xl"
      >
        <div class="my-4">
          <b-form>
            <b-form-group
              label="Task Name"
              label-for="input-1"
              style="margin-bottom: 10px"
            >
              <b-form-input
                id="input-1"
                v-model="task_record.name"
                type="text"
                placeholder="Enter you task name"
                required
              ></b-form-input>
            </b-form-group>
            <b-form-group label="Description" label-for="input-2">
              <b-form-textarea
                id="input-2"
                v-model="task_record.description"
                placeholder="Enter something..."
                rows="6"
                max-rows="6"
              ></b-form-textarea>
            </b-form-group>
            <b-row style="margin-top: 10px">
              <b-col>
                <b-form-group label="Assign to">
                  <b-form-select
                    v-model="task_record.assign_to"
                    class="select-custom"
                    :options="
                      members.map((member_item) => {
                        return {
                          value: member_item.user_id,
                          text: member_item.full_name,
                        };
                      })
                    "
                  ></b-form-select>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group label="Color">
                  <b-form-input
                    class="select-custom"
                    type="color"
                    v-model="task_record.color"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group label="Due Date">
                  <b-form-datepicker
                    id="example-datepicker"
                    class="mb-2"
                    v-model="task_record.due_date"
                  ></b-form-datepicker>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
          <div style="text-align: right">
            <b-button
              style="margin-right: 10px"
              @click="create_task_modal = false"
              >Cancel</b-button
            >
            <b-button class="btn-primary" @click="create_task">Create</b-button>
          </div>
        </div>
      </b-modal>

      <b-modal
        v-if="open_task_modal == true"
        v-model="open_task_modal"
        title="Edit Task"
        hide-footer
        size="xl"
      >
        <p class="my-4">
          <TaskContent
            @submit="update_task"
            :members="
              members.map((member_item) => {
                return {
                  value: member_item.user_id,
                  text: member_item.full_name,
                };
              })
            "
            :task_item="task_item_drag"
          />
        </p>
      </b-modal>
    </div>
    <!-- modal open task -->
  </div>
</template>

<script>
import Task from "@/components/ProjectContent/components/Task.vue";
import TaskContent from "@/components/ProjectContent/components/TaskContent.vue";
import axios from "axios";
import moment from "moment";
export default {
  props: {
    project_id: String,
    project: Object,
  },
  components: {
    Task,
    TaskContent,
  },
  async created() {
    await this.get_task();
    await this.get_member();
  },
  data() {
    return {
      text_filter: "",
      task_record: {
        name: "",
        description: "",
        state: "todo",
        assign_to: null,
        color: "",
        due_date: moment().format("YYYY-MM-DD"),
      },
      open_task_modal: false,
      create_task_modal: false,
      members: [],
      tasks: {
        todo: [],
        in_progress: [],
        done: [],
      },
      task_item_drag: null,
    };
  },
  methods: {
    filter_task() {
      console.log(this.text_filter);
    },
    create_task() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/create_task.php?project_id=" +
            this.project_id,
          {
            ...this.task_record,
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            await this.get_task();
            this.create_task_modal = false;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    update_task(task_item) {
      axios
        .put(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/update_task.php",
          {
            ...task_item,
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            await this.get_task();
            this.open_task_modal = false;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    get_member() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/members/list_member.php?project_id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.members = res.data.data;
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    get_task() {
      axios
        .get(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/tasks/task_list.php?project_id=" +
            this.project_id,
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            this.tasks.todo = res.data.data.filter((x) => x.state == "todo");
            this.tasks.in_progress = res.data.data.filter(
              (x) => x.state == "in_progress"
            );
            this.tasks.done = res.data.data.filter((x) => x.state == "done");
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    create_task_model(state) {
      this.task_record.state = state;
      this.create_task_modal = true;
    },
    open_task_model(task_item) {
      this.task_item_drag = task_item;
      this.open_task_modal = true;
    },
    dragstart(task_item) {
      this.task_item_drag = task_item;
    },
    drop_zone_enter(event) {
      event.target.style.borderStyle = "dotted";
      event.target.style.borderWidth = "1px";
    },
    drop_zone_leave(event) {
      event.target.style.borderStyle = "none";
    },
    drop(event, state) {
      event.target.style.borderStyle = "none";

      if (this.task_item_drag != null) {
        this.task_item_drag.state = state;

        this.update_task(this.task_item_drag);
        this.task_item_drag = null;
      }
    },
  },
};
</script>

<style scoped>
.column-body {
  margin-top: 20px;
  background-color: rgba(255, 255, 255, 0.576);
  height: calc(100% - 50px);
  border-radius: 10px;
  overflow: auto;
  padding: 5px;
}
.lane-column-header {
  position: relative;
}
.create-task-icon {
  float: right;
  color: #43435e;
  border: none;
  background-color: white;
}
#view-content {
  padding: 20px;
  height: calc(100% - 160px);

  overflow-y: auto;
}
.body-column {
  overflow: auto;
}
.lane-column {
  height: 650px;
  width: 300px;
  border-radius: 10px;

  display: inline-block;
  margin: 25px;
  padding: 10px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
.header {
  display: flex;
  justify-content: space-between;
}
.board-label {
  color: #43435e;
  font-size: 30px;
  font-weight: bold;
}
.select-custom {
  /* padding: 5px; */
  height: 36px;
  width: 100%;
}
</style>
```
{% endcode %}

3.2 ทำการเปิดไฟล์  TaskContent.vue` ภายใต้ src/components/ProjectContent/components`

![](<../.gitbook/assets/image (88).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="TaskContent.vue" %}
```javascript
<template>
  <div>
    <b-form>
      <b-form-group
        label="Task Name"
        label-for="input-1"
        style="margin-bottom: 10px"
      >
        <b-form-input
          id="input-1"
          v-model="task.name"
          type="text"
          placeholder="Enter you task name"
          required
        ></b-form-input>
      </b-form-group>
      <b-form-group label="Description" label-for="input-2">
        <b-form-textarea
          id="input-2"
          v-model="task.description"
          placeholder="Enter something..."
          rows="6"
          max-rows="6"
        ></b-form-textarea>
      </b-form-group>
      <b-row style="margin-top: 10px">
        <b-col>
          <b-form-group label="Assign to">
            <b-form-select
              v-model="task.assign_to"
              class="select-custom"
              :options="members"
            ></b-form-select>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Color">
            <b-form-input
              class="select-custom"
              type="color"
              v-model="task.color"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Due Date">
            <b-form-datepicker
              id="example-datepicker"
              class="mb-2"
              v-model="task.due_date"
            ></b-form-datepicker>
          </b-form-group>
        </b-col>
      </b-row>
    </b-form>
    <div style="text-align: right">
      <b-button style="margin-right: 10px">Cancel</b-button>
      <b-button class="btn-primary" @click="submit_update_task"
        >Update</b-button
      >
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.task = JSON.parse(JSON.stringify(this.task_item));
  },
  props: {
    task_item: Object,
    members: Array,
  },
  data() {
    return {
      task: {
        id: 0,
        title: "Create Login Page",
        state: "todo",
        description:
          "Login Page UI : You have to make prototype for you Developer and let them Coding Mockup follow you prototype",
        color: "#AFE4AE",
        assign_to: null,
      },
    };
  },
  methods: {
    submit_update_task() {
      this.$emit("submit", this.task);
    },
  },
};
</script>

<style scoped>
.select-custom {
  /* padding: 5px; */
  height: 36px;
  width: 100%;
}
.btn-primary {
  background-color: #7741a0;
}
</style>
```
{% endcode %}

##
