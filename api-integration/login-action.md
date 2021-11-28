# 🌐 Login Action

1. ทำการ เปิดไฟล์ index.vue ภายใต้ src/components/LoginPage

![](<../.gitbook/assets/image (165).png>)

จากนั้นทำการ copy code จุดด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div id="backgroud">
    <div id="login-box" class="shadow">
      <div class="title font-impact text-center">BSRU</div>
      <div class="sub-title text-center">Kanban Board Project Management</div>

      <div class="text-center" style="margin-top: 25px; font-size: 32px">
        Welcome Back!
      </div>
      <div style="padding: 50px">
        <div class="login-form">
          <label>Email</label>
          <input v-model="email" class="input-custom" type="text" />
          <label>Password</label>
          <input v-model="password" class="input-custom" type="password" />
        </div>
        <div style="margin-top: 40px">
          <button
            type="button"
            @click="sing_in"
            class="btn btn-sign-in btn-lg btn-block"
            style="width: 100%"
          >
            Sign in
          </button>
          <div
            class="text-center"
            style="margin-top: 10px; margin-bottom: 10px"
          >
            OR
          </div>
          <router-link to="/create-account">
            <button
              type="button"
              class="btn btn-create-account btn-lg btn-block"
              style="width: 100%"
            >
              Create an account
            </button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    sing_in() {
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/authentication/login.php",
          {
            email: this.email,
            password: this.password,
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            localStorage.setItem("token", res.data.data.token);
            localStorage.setItem(
              "user_info",
              JSON.stringify(res.data.data.user_info)
            );

            this.$router.push("/project-list")
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
 

<style lang="css" scoped>
.input-custom {
  /* position: relative; */
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
  outline: none;
  border: none;
  border-bottom: 2px solid white;
  background: rgba(255, 255, 255, 0);
  color: white;
}

#backgroud {
  background-color: #43435e;
  height: inherit;
  position: relative;
}

.text-center {
  text-align: center;
}

#login-box {
  min-width: 30vw;
  min-height: 50vh;
  background-color: #43435e;
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  border-radius: 50px;
  padding: 50px;
}

.shadow {
  box-shadow: 10px 4px 37px 0px rgba(0, 0, 0, 0.71);
  -webkit-box-shadow: 10px 4px 37px 0px rgba(0, 0, 0, 0.71);
  -moz-box-shadow: 10px 4px 37px 0px rgba(0, 0, 0, 0.71);
  color: white;
}

.title {
  font-size: 4em;
}

.sub-title {
  font-size: 2em;
}

.btn-sign-in {
  background-color: #7742a0;
  color: white;
}

.btn-create-account {
  background-color: #1c1c1c;
  color: white;
}
</style>in
```
{% endcode %}

2\. ทำการเปิดไฟล์ index.vue ภายใต้ src/components/MainLayout

![](<../.gitbook/assets/image (73).png>)

จากนั้นทำการ copy code ด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div class="full-height">
    <!-- nav bar -->
    <div class="topnav">
      <router-link to="/project-list">
        <div class="font-impact logo">BSRU Kanban</div>
      </router-link>

      <div>
        <div>
          <b-avatar
            style="margin-right: 12px; background-color: antiquewhite"
            size="lg"
            variant="primary"
            :text="user_info.full_name.substring(0, 2)"
          ></b-avatar>
          <b-dropdown id="fullname-btn" :text="user_info.full_name">
            <b-dropdown-item
              class="memu-item"
              @click="go_to('/account/setting')"
            >
              <b-icon icon="gear"></b-icon> Account Setting
            </b-dropdown-item>
            <b-dropdown-item class="memu-item">
              <b-icon icon="box-arrow-right"></b-icon> Logout
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </div>
    </div>
    <!-- nav bar -->
    <!-- content -->
    <div class="content">
      <router-view></router-view>
    </div>
    <!-- content -->
  </div>
</template>

<script>
export default {
  created(){
    this.user_info = JSON.parse(localStorage.getItem("user_info")) 
  },
  data() {
    return {
      user_info:{

      },
    };
  },
  methods: {
    go_to(path) {
      this.$router.push(path);
    },
  },
};
</script>

<style scoped>
.content {
  /* background-color: tomato; */
  height: 92.5%;
}
.logo {
  color: white;
  font-size: 30px;
}
.topnav {
  padding: 20px;
  display: flex;
  background-color: #43435e;
  justify-content: space-between;
}
</style>i
```
{% endcode %}
