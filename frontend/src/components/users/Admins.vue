<script setup>
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, computed, onMounted } from "vue";
import UserTable from "./UserTable.vue";

const router = useRouter();

const users = ref([]);
const currentPage = ref(1); // Adicione esta linha
const totalPages = ref(0); // Adicione esta linha

const totalUsers = computed(() => {
  return users.value.length;
});

const loadAdmins = async () => {
  try {
    const response = await axios.get(`admins?page=${currentPage.value}`);
    admins.value = response.data.data;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.log(error);
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    loadAdmins();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    loadAdmins();
  }
};


onMounted(() => {
  loadAdmins();
});
</script>

<template>
  <user-table :users="admins" :showId="false"></user-table>
  <div class="d-flex justify-content-center align-items-center my-3">
    <button class="btn btn-success p-2 mx-2" @click="prevPage">
      <i class="bi bi-arrow-left"></i> Previous
    </button>
    <span class="mx-3 text-secondary"
      >Page {{ currentPage }} of {{ totalPages }}</span
    >
    <button class="btn btn-success p-2 mx-2" @click="nextPage">
      Next <i class="bi bi-arrow-right"></i>
    </button>
  </div>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}

.total-filtro {
  margin-top: 2.3rem;
}
</style>
