<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router";
import { useUserStore } from "../stores/users";
import{inject,ref } from "vue";
import VcardDetail from "./vcards/VcardDetail.vue";

const userStore = useUserStore();
const socket = inject("socket");
const asks = ref([]);
socket.on("askResponse", function (ask) {
  try {
    // Exibir um toast com a informação recebida do WebSocket
    toast.info(`recebeu um pedido de: ${ask.vcard}\nValor: ${ask.value}`);
    asks.value.push(ask);
  } catch (error) {
    console.error("Erro ao processar resposta do WebSocket:", error);
  }
});

</script>

<template>
  <div v-if="userStore.userName != 'Anonymous'">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-1 border-bottom">
      <h1 class="h2">Dashboard</h1>
    </div>

    <vcard-detail></vcard-detail>
    <!--BUTTONS-->
    <div v-if="userStore.user.type != 'A'" class="d-flex justify-content-around py-1">
      <button type="button" class="btn btn-success btn-lg btn-block p-4 shadow-lg">
        <router-link class="nav-link w-100 me-3" :class="{ active: $route.name === 'SendMoney' }"
          :to="{ name: 'SendMoney' }">
          <i class="bi bi-cash-stack"></i>
          Send Money
        </router-link>
      </button>
      <button type="button" class="btn btn-success btn-lg btn-block p-4 shadow-lg">
        <router-link class="nav-link w-100 me-3" :class="{ active: $route.name === 'AskMoney' }"
          :to="{ name: 'AskMoney' }">
          <i class="bi bi-chat-dots"></i>
          Ask For Money
        </router-link>
      </button>
      <button type="button" class="btn btn-success btn-lg btn-block p-4 shadow-lg">
        <i class="bi bi-piggy-bank"></i>
        Save Money
      </button>
    </div>
    <div v-else>
      <h2 class="d-flex mx-auto justify-content-center mt-5">Welcome {{ userStore.userName }}</h2>
    </div>
  </div>
</template>

<style scoped>
.btn-success {
  background-color: rgba(80, 53, 150, 255) !important;
  border-color: rgba(80, 53, 150, 255) !important;
}
</style>
  