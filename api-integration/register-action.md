# 🌐 Register Action

1. ทำการ เปิดไฟล์ index.vue ภายใต้ src/components/CreateAccountPage

![](<../.gitbook/assets/image (76).png>)

และทำการ copy code ชุดด้านล่างนี้ไปแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div id="backgroud">
    <router-link to="/">
      <button class="btn btn-primary" type="submit">
        <b-icon icon="chevron-left"></b-icon>
      </button>
    </router-link>
    <div id="login-box" class="shadow">
      <div class="title font-impact text-center">Create Account</div>
      <div class="sub-title text-center">Create a new one account</div>
      <div style="padding: 50px">
        <div class="login-form">
          <label>Name</label>
          <input v-model="full_name" class="input-custom" type="text" />
          <label>Email</label>
          <input v-model="email" class="input-custom" type="text" />
          <label>Password</label>
          <input v-model="password" class="input-custom" type="password" />
        </div>
        <div style="margin-top: 40px">
          <button
            type="button"
            @click="create_account"
            class="btn btn-create-account btn-lg btn-block"
            style="width: 100%"
          >
            Create an account
          </button>
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
      full_name: "",
      phone: "",
      position: "",
      bio: "",
    };
  },
  methods: {
    create_account() {
      //TODO: 2. have to do connect to API server for create account here
      axios
        .post(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/authentication/register.php",
          {
            email: this.email,
            password: this.password,
            full_name: this.full_name,
            phone: this.phone,
            position: this.position,
            bio: this.bio,
          }
        )
        .then((res) => {
          if (res.data.status == true) {
            alert(res.data.message);
            this.$router.push("/");
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

.btn-create-account {
  background-color: #7742a0;
  color: white;
}
</style>
```
{% endcode %}
