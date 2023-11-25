<script setup>

import { useUserStore } from "../stores/users";
const userStore = useUserStore();

</script>



<template>
  <div class="h-100 d-flex align-items-center justify-content-center mt-5">
    <h1>Welcome {{ userStore.userName }}</h1>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
