<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useUserStore } from "../../stores/users.js";

const userStore = useUserStore();

const categories = ref([]);
const currentPage = ref(1);
const totalPages = ref(0);

const loadCategories = async () => {
  try {
    const response = await axios.get(`categories?page=${currentPage.value}`);
    categories.value = response.data.data;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.log(error);
  }
};

const nextPage = () => {
  currentPage.value++;
  loadCategories();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    loadCategories();
  }
};

onMounted(async () => {
  try {
    await loadCategories();
  } catch (error) {
    console.log(error);
  }
});
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th class="align-middle">Id</th>
        <th class="align-middle">VCard</th>
        <th class="align-middle">Type</th>
        <th class="align-middle">Name</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="category in categories" :key="category.id">
        <td class="align-middle">{{ category.id }}</td>
        <td class="align-middle">{{ category.vcard }}</td>
        <td class="align-middle">
          {{
            category.type == "D"
              ? "Debito"
              : category.type == "C"
              ? "Credito"
              : category.type
          }}
        </td>
        <td class="align-middle">{{ category.name }}</td>
        <td class="text-end align-middle">
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="editClick(category)">
              <i class="bi bi-xs bi-pencil"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
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
button {
  margin-left: 3px;
  margin-right: 3px;
}

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
