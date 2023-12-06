<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router";
import { ref, onMounted, inject } from "vue";
import axios from "axios";
import { useUserStore } from "./stores/users";
import { useToast } from "vue-toastification";
const router = useRouter();
const toast = useToast();

const userStore = useUserStore();

const socket = inject("socket");

const logout = async () => {
  socket.emit("logout", userStore.user);
  if (await userStore.logout()) {
    //toast.success("User has logged out of the application.");
    router.push({ name: "home" });
  } else {
    toast.error("There was a problem logging out of the application!");
  }
};
// socket.on("loggedIn", function (user) {
//   socket.join(user.id);
//   if (user.type == "A") {
//     socket.join("administrator");
//   }
//   console.log("User logged in:", user);
// });
// socket.on("loggedIn", function (name) {
//   toast.info("User " + name + " has entered the application.");
// });
const workInProgressProjects = ref([]);
onMounted(async () => {
  try {
    const token = sessionStorage.getItem("token");
    if (token) {
      axios.defaults.headers.common.Authorization = "Bearer " + token;
      userStore.loadUser();
    }

    const userId = 1;
    const response = await axios.get("users");
    workInProgressProjects.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
});
</script>

<template>
  <nav
    class="navbar navbar-expand-md navbar-dark bg-success sticky-top flex-md-nowrap p-0 shadow"
  >
    <div class="container-fluid">
      <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">
        <img
          src="@/assets/logo.svg"
          alt=""
          width="30"
          height="24"
          class="d-inline-block align-text-top"
        />
        Vcard
      </a>
      <button
        id="buttonSidebarExpandId"
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a
              class="nav-link"
              href="#"
              v-if="userStore.userName == 'Anonymous'"
              ><i class="bi bi-person-check-fill"></i>
              Register
            </a>
          </li>
          <li class="nav-item" v-if="userStore.userName == 'Anonymous'">
            <router-link
              class="nav-link"
              :class="{ active: $route.name === 'Login' }"
              :to="{ name: 'Login' }"
            >
              <i class="bi bi-box-arrow-in-right"></i>
              Login
            </router-link>
          </li>
          <div v-if="userStore.userName != 'Anonymous'">
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdownMenuLink"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <img
                  :src="userStore.userPhotoUrl"
                  class="rounded-circle z-depth-0 avatar-img"
                  alt="avatar image"
                />
                <span class="avatar-text">{{ userStore.userName }}</span>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink"
              >
                <li>
                  <router-link
                    class="dropdown-item"
                    :class="{
                      active:
                        $route.name == 'User' &&
                        $route.params.id == userStore.userId,
                    }"
                    :to="{ name: 'User', params: { id: userStore.userId } }"
                    @click="clickMenuOption"
                  >
                    <i class="bi bi-person-square"></i>
                    Profile
                  </router-link>
                </li>
                <li>
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'ChangePassword' }"
                    :to="{ name: 'ChangePassword' }"
                  >
                    <i class="bi bi-key-fill"></i>
                    Change password
                  </router-link>
                </li>
                <li>
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'ChangePin' }"
                    :to="{ name: 'ChangePin' }"
                  >
                    <i class="bi bi-key-fill"></i>
                    Change Pin
                  </router-link>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" @click.prevent="logout">
                    <i class="bi bi-arrow-right"></i>Logout
                  </a>
                </li>
              </ul>
            </li>
          </div>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
      >
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item" v-if="userStore.userName != 'Anonymous'">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Dashboard' }"
                :to="{ name: 'Dashboard' }"
              >
                <i class="bi bi-house"></i>
                Dashboard
              </router-link>
            </li>

            <li class="nav-item" v-if="userStore.userName != 'Anonymous'">
              <router-link
                class="nav-link w-100 me-3"
                :class="{ active: $route.name === 'Vcard' }"
                :to="{ name: 'Vcard' }"
              >
                <i class="bi bi-credit-card"></i>
                Vcard
              </router-link>
            </li>
            <li class="nav-item" v-else>
              <router-link
                class="nav-link w-100 me-3"
                :class="{ active: $route.name === 'NewTask' }"
                :to="{ name: 'NewTask' }"
                aria-label="Add a new task"
              >
                <i class="bi bi-plus-circle"></i>
                Create Vcard
              </router-link>
            </li>
            <li class="nav-item" v-if="userStore.userName != 'Anonymous'">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Users' }"
                :to="{ name: 'Users' }"
              >
                <i class="bi bi-people"></i>
                Users
              </router-link>
            </li>

            <li class="nav-item" v-if="userStore.userName != 'Anonymous'">
              <router-link
                class="nav-link"
                :class="{ active: $route.name === 'Transactions' }"
                :to="{ name: 'Transactions' }"
              >
                <i class="bi bi-bar-chart-line"></i>
                Transactions
              </router-link>
            </li>
          </ul>

          <div class="d-block d-md-none">
            <h6
              class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted"
            >
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#"
                  ><i class="bi bi-person-check-fill"></i>
                  Register
                </a>
              </li>
              <li class="nav-item">
                <router-link
                  class="nav-link"
                  :class="{ active: $route.name === 'Login' }"
                  :to="{ name: 'Login' }"
                >
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </router-link>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdownMenuLink"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    :src="userStore.userPhotoUrl"
                    class="rounded-circle z-depth-0 avatar-img"
                    alt="avatar image"
                  />
                  <span class="avatar-text">{{ userStore.userName }}</span>
                </a>
                <ul
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink2"
                >
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{
                        active: $route.name == 'User' && $route.params.id == 1,
                      }"
                      :to="{ name: 'User', params: { id: 1 } }"
                    >
                      <i class="bi bi-person-square"></i>
                      Profile
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{ active: $route.name === 'ChangePassword' }"
                      :to="{ name: 'ChangePassword' }"
                    >
                      <i class="bi bi-key-fill"></i>
                      Change password
                    </router-link>
                  </li>
                  <li>
                    <router-link
                      class="dropdown-item"
                      :class="{ active: $route.name === 'ChangePassword' }"
                      :to="{ name: 'ChangePassword' }"
                    >
                      <i class="bi bi-key-fill"></i>
                      Change Pin
                    </router-link>
                  </li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li>
                    <a
                      router-link
                      class="nav-link"
                      :class="{ active: $route.name === 'Logout' }"
                      :to="{ name: 'Logout' }"
                    >
                      <i class="bi bi-arrow-right"></i>Lo
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<style>
@import "./assets/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
}

.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
}

.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}
</style>
