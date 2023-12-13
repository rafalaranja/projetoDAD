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

const loadUsers = async () => {
  try {
    const response = await axios.get(`users?page=${currentPage.value}`);
    console.log("loadUsers response", response); // Adicione esta linha
    users.value = response.data.data;
    totalPages.value = response.data.last_page;
    console.log("Users data", users.value); // Adicione esta linha
  } catch (error) {
    console.log(error);
  }
};
const nextPage = () => {
  console.log("Next page clicked"); // Adicione esta linha
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    loadUsers();
  }
};

const prevPage = () => {
  console.log("Previous page clicked"); // Adicione esta linha
  if (currentPage.value > 1) {
    currentPage.value--;
    loadUsers();
  }
};
const editUser = (user) => {
  router.push({ name: "User", params: { id: user.id } });
};

onMounted(() => {
  loadUsers();
});
</script>

<template>
  <user-table :users="users" :showId="false" @edit="editUser"></user-table>
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
