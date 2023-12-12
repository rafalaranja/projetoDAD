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



const loadTransactions = async () => {
  try {
    const response = await axios.get('transactions')
    transactions.value = response.data.data
    //console.log('Total transactions: ', transactions.value.length);

  } catch (error) {
    console.log(error)
  }
}

const loadUsers = async () => {
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

const filteredTransactions = computed(() => {
  if (transactions.value.filter(p => (userStore.userId == p.vcard)).length == 0) {
    return transactions.value;
  } else {
    return transactions.value.filter(p =>
      (userStore.userId == p.vcard)
    );
  }
})

const totalTransactions = computed(() => {
  return filteredTransactions.value.length
})
const paginatedItems = computed(() => {
  if (Array.isArray(filteredTransactions.value)) {
    const start = (currentPage.value - 1) * pageSize;
    const end = start + pageSize;// Verifique se transactions.value é um array antes de chamar slice

    return filteredTransactions.value.slice(start, end);
  } else {
    // Trate o caso em que transactions.value não é um array, por exemplo, definindo um valor padrão
    return [];
  }
});
const totalPages = computed(() => Math.ceil(filteredTransactions.value.length / pageSize));

const updatePage = (page) => {
  currentPage.value = page;
};

const goToPreviousPage = () => {
  updatePage(currentPage.value - 1);
};

const goToNextPage = () => {
  updatePage(currentPage.value + 1);
};

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
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total: {{ totalTransactions }}</h5>
    </div>
  </div>

  <transaction-table :transactions="paginatedItems" :showId="false" :showDates="true"
    @view="viewTransaction"></transaction-table>

  <div class="pagination-controls">
    <button type="button" class="btn btn-success" @click="goToPreviousPage"
      :disabled="currentPage === 1">Previous</button>
    <span>Page {{ currentPage }} of {{ totalPages }}</span>
    <button type="button" class="btn btn-success" @click="goToNextPage"
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
