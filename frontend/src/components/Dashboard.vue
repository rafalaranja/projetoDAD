<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router";
import { useUserStore } from "../stores/users";
import VcardDetail from "./vcards/VcardDetail.vue";

const userStore = useUserStore();

</script>

<template>
  <div v-if="userStore.userName != 'Anonymous'">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Dashboard</h1>
    </div>

    <vcard-detail></vcard-detail>
    <!--BUTTONS-->
    <div v-if="userStore.user.type != 'A'" class="d-flex justify-content-around shadow h-100 py-1">
      <button type="button" class="btn btn-success btn-lg btn-block p-4">
        <router-link class="nav-link w-100 me-3" :class="{ active: $route.name === 'SendMoney' }"
          :to="{ name: 'SendMoney' }">
          <i class="bi bi-cash-stack"></i>
          Send Money
        </router-link>
      </button>
      <button type="button" class="btn btn-success btn-lg btn-block p-4">
        <router-link class="nav-link w-100 me-3" :class="{ active: $route.name === 'AskMoney' }"
          :to="{ name: 'AskMoney' }">
          <i class="bi bi-chat-dots"></i>
          Ask For Money
        </router-link>
      </button>
      <button type="button" class="btn btn-success btn-lg btn-block p-4">
        <i class="bi bi-piggy-bank"></i>
        Save Money
      </button>
    </div>
    <div v-else>
      <h2 class="d-flex mx-auto justify-content-center">Welcome {{ userStore.userName }}</h2>
    </div>
  </div>
</template>

<style scoped>
.btn-success {
  background-color: rgba(80, 53, 150, 255) !important;
  border-color: rgba(80, 53, 150, 255) !important;
}
</style>
  