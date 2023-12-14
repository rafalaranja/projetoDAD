<script setup>
import { Pagination } from 'vue-pagination-2'
import axios from 'axios'
import { useToast } from "vue-toastification"
import { useRouter } from 'vue-router'
import { ref, computed, onMounted } from 'vue'
import transactionTable from "./TransactionTable.vue"
import { useUserStore } from "../../stores/users"


const toast = useToast()
const router = useRouter()

const userStore = useUserStore();
const transactions = ref([])
const users = ref([])
const filterByResponsibleId = ref(null)
const filterByStatus = ref('W')
const transactionToDelete = ref(null)
const deleteConfirmationDialog = ref(null)
const currentPage = ref(1);
const pageSize = 10;



const loadTransactions = async (page = 1) => {
  try {
    const response = await axios.get(`/transactions?page=${page}`);
    transactions.value = response.data.data;
    currentPage.value = response.data.current_page;
    totalPages.value = response.data.last_page;

    console.log(response.data.data);
    console.log(response.data.current_page);
  } catch (error) {
    console.error('Error loading transactions:', error);
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


const viewTransaction = (transaction) => {
  router.push({ name: 'Transaction', params: { id: transaction.id } })
}



const deleteTransactionConfirmed = async () => {
  try {
    const response = await axios.delete('transactions/' + transactionToDelete.value.id)
    let deletedTransaction = response.data.data
    let idx = transactions.value.findIndex((t) => t.id === deletedTransaction.id)
    if (idx >= 0) {
      transactions.value.splice(idx, 1)
    }
    toast.info(`Transaction ${transactionToDeleteDescription.value} was deleted`)
  } catch (error) {
    console.log(error)
    toast.error(`It was not possible to delete Transaction ${transactionToDeleteDescription.value}!`)
  }
}

const transactionToDeleteDescription = computed(() => transactionToDelete.value
  ? `#${transactionToDelete.value.id} (${transactionToDelete.value.name})`
  : "")


const paginatedItems = computed(() => {
  if (Array.isArray(transactions.value)) {
    return transactions.value;
  } else {
    return [];
  }
});
const totalPages = ref(1);

onMounted(() => {
  loadUsers()
  loadTransactions()
  console.log('Initial currentPage:', currentPage.value);
})


</script>

<template>
  <confirmation-dialog ref="deleteConfirmationDialog" confirmationBtn="Delete transaction"
    :msg="`Do you really want to delete the transaction ${transactionToDeleteDescription}?`"
    @confirmed="deleteTransactionConfirmed">
  </confirmation-dialog>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">Transactions</h3>
    </div>
  </div>

  <transaction-table :transactions="paginatedItems" :showId="false" :showDates="true"
    @view="viewTransaction"></transaction-table>

  <div class="pagination-controls">
    <button class="btn btn-success" @click="loadTransactions(currentPage - 1)"
      :disabled="currentPage === 1">Previous</button>
    <span>Page {{ currentPage }} of {{ totalPages }}</span>
    <button class="btn btn-success" @click="loadTransactions(currentPage + 1)"
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
