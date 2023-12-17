<script>
import axios from "axios";
const emit = defineEmits(["edit"]);
export default {
  data() {
    return {
      categories: [],
      currentPage: 1,
      totalPages: 1,
    };
  },
  methods: {
    fetchCategories() {
      axios.get(`/categories?page=${this.currentPage}`).then((response) => {
        this.categories = response.data.data;
        this.currentPage = response.data.current_page;
        this.totalPages = response.data.last_page;
      });
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchCategories();
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchCategories();
      }
    },
  },
  created() {
    this.fetchCategories();
  },
  editClick(category){
    emit("edit", category);
  },
};
</script>

<template>
  <table class="table">
    <thead>
      <tr>
          <th class="align-middle">Name</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="category in categories" :key="category.id">
          <td class="align-middle">{{ category }}</td>
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
