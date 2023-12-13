<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/users.js";

const router = useRouter();
const userStore = useUserStore();
const vcards = ref(null); // Inicialize tasks como null

const loadVCards = async () => {
  try {
    const response = await axios.get("vcard/" + userStore.user.id + "/load");
    vcards.value = response.data.data; // Atribua response.data.data a tasks.value
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
    <!-- Verifique se tasks não é null -->
    <div class="card-header bg-dark text-white">
      <h5>{{ vcards.phone_number }}</h5>
    </div>
    <div class="card-body">
      <h5 class="card-title">{{ vcards.name }}</h5>
      <!-- Acesse as propriedades de tasks -->
      <h3 class="pt-2">BALANCE</h3>
      <h5 class="card-text pb-3 pt-1">{{ vcards.balance }}</h5>
    </div>
    <div class="card-footer text-muted">
      <h5>MAX DEBIT</h5>
      {{ vcards.max_debit }}€
    </div>
  </div>
</template>
