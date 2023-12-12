<script setup>
import { useRouter, RouterLink, RouterView } from "vue-router";

import VcardDetail from "./vcards/VcardDetail.vue";
</script>

<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>

  <vcard-detail></vcard-detail>


  <!--CARDS-->
  <div class="card mb-3">
    <button type="button" class="btn btn-success btn-lg btn-block p-4">
      <router-link class="nav-link w-100 me-3" :class="{ active: $route.name === 'SendMoney' }"
        :to="{ name: 'SendMoney' }">
        <i class="bi bi-cash-stack"></i>
        Send Money
      </router-link>
    </button>
  </div>
  <div class="card mb-3">
    <button type="button" class="btn btn-success btn-lg btn-block p-4">
      <i class="bi bi-chat-dots"></i>
      Ask For Money
    </button>
  </div>
  <div class="card mb-3">
    <button type="button" class="btn btn-success btn-lg btn-block p-4">
      <i class="bi bi-receipt-cutoff"></i>
      Pay Bill
    </button>
  </div>
</template>
  