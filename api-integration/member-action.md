# 🌐 Member Action

## 1. List Member by Project id

1.1 ทำการ เปิดไฟล์ `Member.vue` ภายใต้ `src/components/ProjectContent/components`

![](<../.gitbook/assets/image (10).png>)

ทำการ copy code ด้านล่างไปแทนที่

{% code title="Member.vue" %}
```javascript
<template>
  <div id="view-content">
    <div class="header">
      <div class="board-label">
        {{ project.name }} > Member ({{ members.length }})
      </div>
      <div>
        <b-button class="btn-primary" @click="open_invite_member_modal = true"
          >Invite member</b-button
        >
      </div>
    </div>
    ``
    <div>
      <b-row class="box">
        <b-col
          style="text-align: center"
          sm="12"
          md="3"
          v-for="(member_item, index) in members"
          :key="index"
        >
          <center>
            <div class="member-item">
              <b-avatar
                :text="member_item.full_name.substring(0, 2)"
                size="9rem"
                style="background-color: antiquewhite"
              ></b-avatar>
              <div class="fullname">
                {{ member_item.full_name }}
              </div>
              <div class="position">
                {{ member_item.position }}
              </div>
            </div>
          </center>
        </b-col>
      </b-row>
    </div>
    <!-- modal open invite_member -->

    <div>
      <b-modal
        v-if="open_invite_member_modal == true"
        v-model="open_invite_member_modal"
        title="Invite Members to you project"
        hide-footer
        size="md"
      >
        <div class="my-4">
          <b-form-group
            label="Email Address"
            label-for="input-1"
            style="margin-bottom: 10px"
            v-for="(invite_member_item, index) in invite_member"
            :key="index"
          >
            <b-form-input
              id="input-1"
              v-model="invite_member_item.email"
              type="text"
              placeholder="Enter you eamil address"
              required
            ></b-form-input>
          </b-form-group>

          <span @click="add_new" class="add-new">Add New</span>
          <div style="text-align: right">
            <b-button style="margin-right: 10px">Cancel</b-button>
            <b-button class="btn-primary">Update</b-button>
          </div>
        </div>
      </b-modal>
    </div>
    <!-- modal open invite_member -->
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    project_id: String,
    project: Object,
  },
  async created() {
    await this.get_member();
  },
  methods: {
    add_new() {
      this.invite_member.push({
        email: "",
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
  },
  data() {
    return {
      invite_member: [
        {
          email: "",
        },
        {
          email: "",
        },
      ],
      open_invite_member_modal: false,
      members: [],
    };
  },
};
</script>

<style scoped>
.box {
  padding-left: 200px;
  padding-right: 200px;
}
.member-item {
  border-radius: 10px;
  margin: 10px;
  min-height: 300px;
  width: 250px;
  text-align: center;
  padding: 20px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
#view-content {
  padding: 20px;
  height: calc(100% - 160px);

  overflow-y: auto;
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
.btn-primary {
  background-color: #7741a0;
}
.fullname {
  margin-top: 50px;
  color: #43435e;
  font-size: 23px;
  font-weight: bold;
}
.position {
  color: #80808d;
  font-size: 15px;
}

.add-new {
  cursor: pointer;
  color: #4fa0ff;
  font-size: 17px;
}
</style>ja
```
{% endcode %}

## 2. Invite Member to Project

2.1 ทำการ เปิดไฟล์ `Member.vue` ภายใต้ `src/components/ProjectContent/components`

![](<../.gitbook/assets/image (10).png>)

แล้วทำการ copy code ด้านล่างไปแทนที่

{% code title="Member.vue" %}
```javascript
<template>
  <div id="view-content">
    <div class="header">
      <div class="board-label">
        {{ project.name }} > Member ({{ members.length }})
      </div>
      <div>
        <b-button class="btn-primary" @click="open_invite_member_modal = true"
          >Invite member</b-button
        >
      </div>
    </div>
    ``
    <div>
      <b-row class="box">
        <b-col
          style="text-align: center"
          sm="12"
          md="3"
          v-for="(member_item, index) in members"
          :key="index"
        >
          <center>
            <div class="member-item">
              <b-avatar
                :text="member_item.full_name.substring(0, 2)"
                size="9rem"
                style="background-color: antiquewhite"
              ></b-avatar>
              <div class="fullname">
                {{ member_item.full_name }}
              </div>
              <div class="position">
                {{ member_item.position }}
              </div>
            </div>
          </center>
        </b-col>
      </b-row>
    </div>
    <!-- modal open invite_member -->

    <div>
      <b-modal
        v-if="open_invite_member_modal == true"
        v-model="open_invite_member_modal"
        title="Invite Members to you project"
        hide-footer
        size="md"
      >
        <div class="my-4">
          <b-form-group
            label="Email Address"
            label-for="input-1"
            style="margin-bottom: 10px"
            v-for="(invite_member_item, index) in invite_member"
            :key="index"
          >
            <b-form-input
              id="input-1"
              v-model="invite_member_item.email"
              type="text"
              placeholder="Enter you eamil address"
              required
            ></b-form-input>
          </b-form-group>

          <span @click="add_new" class="add-new">Add New</span>
          <div style="text-align: right">
            <b-button style="margin-right: 10px">Cancel</b-button>
            <b-button class="btn-primary" @click="invite_member_action"
              >Update</b-button
            >
          </div>
        </div>
      </b-modal>
    </div>
    <!-- modal open invite_member -->
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: {
    project_id: String,
    project: Object,
  },
  async created() {
    await this.get_member();
  },
  methods: {
    invite_member_action() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/projects/invite_user.php",
          {
            id: this.project_id,
            email_array: this.invite_member
              .map((x) => x.email)
              .filter((x) => x != ""),
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            await this.get_member();
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },
    add_new() {
      this.invite_member.push({
        email: "",
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
  },
  data() {
    return {
      invite_member: [
        {
          email: "",
        },
      ],
      open_invite_member_modal: false,
      members: [],
    };
  },
};
</script>

<style scoped>
.box {
  padding-left: 200px;
  padding-right: 200px;
}
.member-item {
  border-radius: 10px;
  margin: 10px;
  min-height: 300px;
  width: 250px;
  text-align: center;
  padding: 20px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}
#view-content {
  padding: 20px;
  height: calc(100% - 160px);

  overflow-y: auto;
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
.btn-primary {
  background-color: #7741a0;
}
.fullname {
  margin-top: 50px;
  color: #43435e;
  font-size: 23px;
  font-weight: bold;
}
.position {
  color: #80808d;
  font-size: 15px;
}

.add-new {
  cursor: pointer;
  color: #4fa0ff;
  font-size: 17px;
}
</style>
```
{% endcode %}
