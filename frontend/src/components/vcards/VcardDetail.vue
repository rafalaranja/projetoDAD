<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/users.js";

const router = useRouter();
const userStore = useUserStore();
const vcards = ref(null);

const loadVCards = async () => {
  try {
    const response = await axios.get("vcard/" + userStore.user.id + "/load");
    vcards.value = response.data.data;
    console.log(response.data.data);
  } catch (error) {
    console.log(error);
  }
};

onMounted(loadVCards);

defineExpose({ vcards });
</script>

<template>
  <div class="card text-center m-4" v-if="vcards">
    <div class="card-header bg-dark text-white">
      <h5>{{ vcards.phone_number }}</h5>
    </div>
    <div class="card-body">
      <h5 class="card-title">{{ vcards.name }}</h5>
      <h4 class="pt-2 text-success">BALANCE</h4>
      <h1 class="card-text pb-3 pt-1 mt-3">{{ vcards.balance }}€</h1>
    </div>
    <div class="card-footer text-muted">
      <h4 class="text-danger">MAX DEBIT</h4>
      <h5 class="text-muted">{{ vcards.max_debit }}€</h5>
    </div>
  </div>
</template>
