<script setup>
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, computed, onMounted } from "vue";
import UserTable from "./UserTable.vue";

const router = useRouter();

const users = ref([]);
const currentPage = ref(1); // Adicione esta linha
const totalPages = ref(0); // Adicione esta linha
const searchTerm = ref('');

const totalUsers = computed(() => {
  return users.value.length;
});

const loadUsers = async () => {
  try {
    const response = await axios.get(`users?page=${currentPage.value}&search=${searchTerm.value}`);
    users.value = response.data.data;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.log(error);
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    loadUsers();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    loadUsers();
  }
};
const editUser = (user) => {
  router.push({ name: "User", params: { id: user.id } });
};

const searchUsers = () => {
  currentPage.value = 1;
  loadUsers();
};

const clearSearch = () => {
  searchTerm.value = '';
  loadUsers();
};

onMounted(() => {
  loadUsers();
});
</script>

<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-1 border-bottom">
    <h1 class="h3">Users</h1>
  </div>
  <div class="input-group">
    <div class="form-outline">
      <input type="search" id="form1" class="form-control" placeholder="Search User" v-model="searchTerm"
        @click="clearSearch" />
    </div>
    <button type="button" class="btn btn-success btn-sm pb-0" @click="searchUsers">
      <i class="bi bi-search d-flex justify-content-center align-items-center"></i>
    </button>
  </div>
  <user-table :users="users" :showId="false" @edit="editUser"></user-table>
  <div class="d-flex justify-content-center align-items-center my-3">
    <button class="btn btn-success p-2 mx-2" @click="prevPage">
      <i class="bi bi-arrow-left"></i> Previous
    </button>
    <span class="mx-3 text-secondary">Page {{ currentPage }} of {{ totalPages }}</span>
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
