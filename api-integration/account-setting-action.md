# 🌐 Account Setting Action

## 1. Account Setting

1.1 ทำการเปิดไฟล์ index.vue ภายใต้ src/components/AccountSetting

![](<../.gitbook/assets/image (161) (1).png>)

จากนั้นทำการ copy code ด้านล่างมาแทนที่

```javascript
<template>
  <div id="backgroud">
    <div id="box-center">
      <div id="left-side">
        <div class="photo-profile">
          <b-avatar
            style="background-color: antiquewhite"
            size="200px"
            :text="record_user.full_name.substring(0, 2)"
          ></b-avatar>
        </div>
        <div id="display-name">
          <div style="color: #43435e; padding: 25px">
            {{ record_user.full_name }}
            <div style="color: #80808d; font-size: 14px">
              {{ record_user.position }}
            </div>
          </div>
        </div>
        <div>
          <div
            :class="{ ['menu-btn']: true, active: menu_active == 'account' }"
            @click="menu_active = 'account'"
          >
            <div>
              <b-icon icon="person-circle" style="margin-right: 5px"></b-icon>
              Account
            </div>
          </div>
          <div
            :class="{ ['menu-btn']: true, active: menu_active == 'password' }"
            @click="menu_active = 'password'"
          >
            <div>
              <b-icon icon="key" style="margin-right: 5px"></b-icon> Password
            </div>
          </div>
        </div>
      </div>
      <div id="right-side">
        <div v-if="menu_active == 'account'">
          <div class="title">Account Setting</div>
          <div class="box-content">
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Full Name:"
                  label-for="full_name"
                >
                  <b-form-input
                    id="full_name"
                    type="email"
                    placeholder="Enter Fullname"
                    required
                    v-model="record_user.full_name"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Email:"
                  label-for="email"
                >
                  <b-form-input
                    id="email"
                    type="email"
                    placeholder="Enter email"
                    v-model="record_user.email"
                    required
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Phone Number"
                  label-for="phone"
                >
                  <b-form-input
                    id="phone"
                    type="text"
                    placeholder="Enter Phone Number"
                    required
                    v-model="record_user.phone"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Position"
                  label-for="position"
                >
                  <b-form-input
                    id="position"
                    type="text"
                    placeholder="Enter positionr"
                    required
                    v-model="record_user.position"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Bio"
                  label-for="bio"
                >
                  <b-form-textarea
                    id="bio"
                    v-model="record_user.bio"
                    placeholder="Enter something..."
                    rows="4"
                    max-rows="10"
                  ></b-form-textarea>
                </b-form-group>
              </b-col>
            </b-row>
          </div>
          <div style="margin-top: 20px">
            <b-button class="btn-custom btn-primary" @click="update_profile"
              >Update</b-button
            >
            <b-button class="btn-custom btn-default">Cancel</b-button>
          </div>
        </div>
        <div v-if="menu_active == 'password'">
          <div class="title">Password Setting</div>
          <div class="box-content">
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="New Password"
                  label-for="new_password"
                >
                  <b-form-input
                    id="new_password"
                    type="password"
                    placeholder="Enter New Password"
                    required
                    v-model="new_password"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Confirm Password"
                  label-for="confirm_password"
                >
                  <b-form-input
                    id="confirm_password"
                    type="password"
                    placeholder="Enter Confirm Password"
                    required
                    v-model="confirm_password"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
          </div>
          <div style="margin-top: 20px">
            <b-button class="btn-custom btn-primary">Update</b-button>
            <b-button class="btn-custom btn-default">Cancel</b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  created() {
    this.preview_img = this.record_user.image_profile;
    this.record_user = JSON.parse(localStorage.getItem("user_info"));
  },
  data() {
    return {
      preview_img: "",
      new_password: "",
      confirm_password: "",
      menu_active: "account", // account,password
      record_user: {
        full_name: "",
        email: "",
        phone: "",
        position: "",
        bio: "",
      },
    };
  },
  methods: {
    update_profile() {
      axios
        .put(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/profile/update_profile.php",
          {
            ...this.record_user,
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            // save data to localStorage
            localStorage.setItem("user_info", JSON.stringify(this.record_user));
            alert(res.data.message);
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

<style scoped>
.upload-image {
  cursor: pointer;
}
#upload-image {
  display: none;
}
.btn-custom {
  margin-right: 10px;
}
.btn-primary {
  background-color: #7741a0;
}
.btn-default {
  background-color: #efeeef;
  color: #43435e;
}
.form-group-custom {
  margin-top: 20px;
}
.title {
  color: #43435e;
  font-weight: bold;
  font-size: 20px;
}
.menu-btn {
  cursor: pointer;
  height: 50px;
  display: flex;
  align-items: center;
  padding-left: 20px;
  color: #43435e;
  font-weight: bold;
}

.menu-btn.active {
  background-color: #43435e;
  color: white;
}

#display-name {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
}
.photo-profile {
  text-align: center;
  padding-top: 50px;
}
#backgroud {
  height: inherit;
  position: relative;
  background-color: #e7e7e7;
}

#box-center {
  display: flex;
  min-width: 75vw;
  min-height: 60vh;
  background-color: white;
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  border-radius: 10px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}

#left-side {
  flex: 1;
  -webkit-box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
  -moz-box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
  box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
}

#right-side {
  padding: 20px;
  flex: 3;
}
</style>
```

## 2. Change Password

2.1 ทำการเปิดไฟล์ index.vue ภายใต้ src/components/AccountSetting

![](<../.gitbook/assets/image (161) (1).png>)

จากนั้นทำการ copy code ด้านล่างมาแทนที่

{% code title="index.vue" %}
```javascript
<template>
  <div id="backgroud">
    <div id="box-center">
      <div id="left-side">
        <div class="photo-profile">
          <b-avatar
            style="background-color: antiquewhite"
            size="200px"
            :text="record_user.full_name.substring(0, 2)"
          ></b-avatar>
        </div>
        <div id="display-name">
          <div style="color: #43435e; padding: 25px">
            {{ record_user.full_name }}
            <div style="color: #80808d; font-size: 14px">
              {{ record_user.position }}
            </div>
          </div>
        </div>
        <div>
          <div
            :class="{ ['menu-btn']: true, active: menu_active == 'account' }"
            @click="menu_active = 'account'"
          >
            <div>
              <b-icon icon="person-circle" style="margin-right: 5px"></b-icon>
              Account
            </div>
          </div>
          <div
            :class="{ ['menu-btn']: true, active: menu_active == 'password' }"
            @click="menu_active = 'password'"
          >
            <div>
              <b-icon icon="key" style="margin-right: 5px"></b-icon> Password
            </div>
          </div>
        </div>
      </div>
      <div id="right-side">
        <div v-if="menu_active == 'account'">
          <div class="title">Account Setting</div>
          <div class="box-content">
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Full Name:"
                  label-for="full_name"
                >
                  <b-form-input
                    id="full_name"
                    type="email"
                    placeholder="Enter Fullname"
                    required
                    v-model="record_user.full_name"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Email:"
                  label-for="email"
                >
                  <b-form-input
                    id="email"
                    type="email"
                    placeholder="Enter email"
                    v-model="record_user.email"
                    required
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Phone Number"
                  label-for="phone"
                >
                  <b-form-input
                    id="phone"
                    type="text"
                    placeholder="Enter Phone Number"
                    required
                    v-model="record_user.phone"
                  ></b-form-input>
                </b-form-group>
              </b-col>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Position"
                  label-for="position"
                >
                  <b-form-input
                    id="position"
                    type="text"
                    placeholder="Enter positionr"
                    required
                    v-model="record_user.position"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Bio"
                  label-for="bio"
                >
                  <b-form-textarea
                    id="bio"
                    v-model="record_user.bio"
                    placeholder="Enter something..."
                    rows="4"
                    max-rows="10"
                  ></b-form-textarea>
                </b-form-group>
              </b-col>
            </b-row>
          </div>
          <div style="margin-top: 20px">
            <b-button class="btn-custom btn-primary" @click="update_profile"
              >Update</b-button
            >
            <b-button class="btn-custom btn-default">Cancel</b-button>
          </div>
        </div>
        <div v-if="menu_active == 'password'">
          <div class="title">Password Setting</div>
          <div class="box-content">
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="New Password"
                  label-for="new_password"
                >
                  <b-form-input
                    id="new_password"
                    type="password"
                    placeholder="Enter New Password"
                    required
                    v-model="new_password"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-form-group
                  class="form-group-custom"
                  label="Confirm Password"
                  label-for="confirm_password"
                >
                  <b-form-input
                    id="confirm_password"
                    type="password"
                    placeholder="Enter Confirm Password"
                    required
                    v-model="confirm_password"
                  ></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
          </div>
          <div style="margin-top: 20px">
            <b-button class="btn-custom btn-primary" @click="change_password"
              >Update</b-button
            >
            <b-button class="btn-custom btn-default">Cancel</b-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  created() {
    this.preview_img = this.record_user.image_profile;
    this.record_user = JSON.parse(localStorage.getItem("user_info"));
  },
  data() {
    return {
      preview_img: "",
      new_password: "",
      confirm_password: "",
      menu_active: "account", // account,password
      record_user: {
        full_name: "",
        email: "",
        phone: "",
        position: "",
        bio: "",
      },
    };
  },
  methods: {
    change_password() {
      if (this.new_password != "" && this.confirm_password != "") {
        if (this.new_password == this.confirm_password) {
          axios
            .put(
              process.env.VUE_APP_API +
                "/api_kanban_board/service/profile/change_password.php",
              {
                new_password: this.new_password,
              },
              {
                headers: {
                  authorization: localStorage.getItem("token"),
                },
              }
            )
            .then(async (res) => {
              if (res.data.status == true) {
                alert(res.data.message);
              } else {
                alert(res.data.message);
              }
            })
            .catch((error) => {
              alert(error.message);
            });
        } else {
          alert("Password it incorrect");
        }
      } else {
        alert("Password field Cannot be empty");
      }
    },
    update_profile() {
      axios
        .put(
          process.env.VUE_APP_API +
            "/api_kanban_board/service/profile/update_profile.php",
          {
            ...this.record_user,
          },
          {
            headers: {
              authorization: localStorage.getItem("token"),
            },
          }
        )
        .then(async (res) => {
          if (res.data.status == true) {
            // save data to localStorage
            localStorage.setItem("user_info", JSON.stringify(this.record_user));
            alert(res.data.message);
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

<style scoped>
.upload-image {
  cursor: pointer;
}
#upload-image {
  display: none;
}
.btn-custom {
  margin-right: 10px;
}
.btn-primary {
  background-color: #7741a0;
}
.btn-default {
  background-color: #efeeef;
  color: #43435e;
}
.form-group-custom {
  margin-top: 20px;
}
.title {
  color: #43435e;
  font-weight: bold;
  font-size: 20px;
}
.menu-btn {
  cursor: pointer;
  height: 50px;
  display: flex;
  align-items: center;
  padding-left: 20px;
  color: #43435e;
  font-weight: bold;
}

.menu-btn.active {
  background-color: #43435e;
  color: white;
}

#display-name {
  text-align: center;
  font-size: 20px;
  font-weight: bold;
}
.photo-profile {
  text-align: center;
  padding-top: 50px;
}
#backgroud {
  height: inherit;
  position: relative;
  background-color: #e7e7e7;
}

#box-center {
  display: flex;
  min-width: 75vw;
  min-height: 60vh;
  background-color: white;
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  border-radius: 10px;
  -webkit-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  -moz-box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
  box-shadow: 0px 0px 5px 0px rgba(97, 97, 97, 1);
}

#left-side {
  flex: 1;
  -webkit-box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
  -moz-box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
  box-shadow: 2px 0px 4px 0px rgb(212, 212, 212);
}

#right-side {
  padding: 20px;
  flex: 3;
}
</style>
```
{% endcode %}
