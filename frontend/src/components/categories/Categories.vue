<script setup>
import { Pagination } from 'vue-pagination-2'
import axios from 'axios'
import { useToast } from "vue-toastification"
import { useRouter } from 'vue-router'
import { ref, computed, onMounted } from 'vue'
import categorieTable from "./CategorieTable.vue"
import { useUserStore } from "../../stores/users"


const toast = useToast()
const router = useRouter()

const userStore = useUserStore();
const categories = ref([])
const users = ref([])
const filterByResponsibleId = ref(null)
const filterByStatus = ref('W')
const categorieToDelete = ref(null)
const deleteConfirmationDialog = ref(null)
const currentPage = ref(1);
const pageSize = 10;

const sortField = ref('id');
const sortOrder = ref('asc');


const loadCategories = async (page = 1) => {
  try {
    const response = await axios.get(`/categories?page=${page}`, {
      params: {
        sortField: sortField.value,
        sortOrder: sortOrder.value
      }
    });
    categories.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;
  } catch (error) {
    console.error(error);
  }
};

const loadUsers = async (page = 1) => {
  try {
    const response = await axios.get('users')
    users.value = response.data.data
  } catch (error) {
    console.log(error)
  }
}


const viewCategorie = (categorie) => {
  router.push({ name: 'Categorie', params: { id: categorie.id } })

}



const deleteCategorieConfirmed = async () => {
  try {
    const response = await axios.delete('categories/' + categorieToDelete.value.id)
    let deletedCategorie = response.data.data
    let idx = categories.value.findIndex((t) => t.id === deletedCategorie.id)
    if (idx >= 0) {
      categories.value.splice(idx, 1)
    }
    toast.info(`Categorie ${categorieToDeleteDescription.value} was deleted`)
  } catch (error) {
    console.log(error)
    toast.error(`It was not possible to delete Categorie ${categorieToDeleteDescription.value}!`)
  }
}

const categorieToDeleteDescription = computed(() => categorieToDelete.value
  ? `#${categorieToDelete.value.id} (${categorieToDelete.value.name})`
  : "")


const paginatedItems = computed(() => {
  if (Array.isArray(categories.value)) {
    return categories.value;
  } else {
    return [];
  }
});
const totalPages = ref(1);

onMounted(() => {
  loadUsers()
  loadCategories()
  console.log('Initial currentPage:', currentPage.value);
})

const changeSort = (field) => {
  if (sortField.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortOrder.value = 'asc';
  }

  loadCategories(currentPage.value);
};


</script>

<template>
  <confirmation-dialog ref="deleteConfirmationDialog" confirmationBtn="Delete categorie"
    :msg="`Do you really want to delete the categorie ${categorieToDeleteDescription}?`"
    @confirmed="deleteCategorieConfirmed">
  </confirmation-dialog>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-2">Categories</h3>
    </div>
  </div>
  <div class="d-flex justify-content-start mt-2">
    <div class="d-flex me-3 mb-2">
      <label class="input-group-text me-1" for="inputGroupSelect01">Filter By</label>
      <select v-model="sortField" @change="loadCategories">
        <option value="id">ID</option>
        <option value="vcard">VCard</option>
      </select>
    </div>
    <div class="d-flex ms-3 mb-2">
      <label class="input-group-text me-1" for="inputGroupSelect01">Order By</label>
      <select v-model="sortOrder" @change="loadCategories">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </div>
  </div>

  <categorie-table :categories="paginatedItems" :showId="false" :showDates="true" @view="viewCategorie"
    @changeSort="changeSort"></categorie-table>

  <div class="pagination-controls mb-1">
    <button class="btn btn-success" @click="loadCategories(currentPage - 1)"
      :disabled="currentPage === 1">Previous</button>
    <span>Page {{ currentPage }} of {{ totalPages }}</span>
    <button class="btn btn-success" @click="loadCategories(currentPage + 1)"
      :disabled="currentPage === totalPages">Next</button>
  </div>
</template>

<style scoped>
.filter-div {
  min-width: 12rem;
}

.total-filtro {
  margin-top: 0.35rem;
}

.btn-addprj {
  margin-top: 1.85rem;
}

.pagination-controls {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
