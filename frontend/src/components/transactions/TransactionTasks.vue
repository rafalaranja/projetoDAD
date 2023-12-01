<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, watch, computed } from 'vue'

const router = useRouter()

const props = defineProps({
  id: {
    type: Number,
    default: null
  }
})

const emptyTransaction = () => {
  return {
    id: null,
    name: '',
    status_name: '',
    tasks: []
  }
}

const loadTransaction = async (id) => {
  if (!id || (id < 0)) {
    transaction.value = emptyTransaction.value()
  } else {
    try {
      const response = await axios.get('transactions/' + id + '/tasks')
      transaction.value = response.data.data
    } catch (error) {
      console.log(error)
    }
  }
}

const editTransaction = () => {
  router.push({ name: 'Transaction', params: { id: props.id } })
}

const addTask = () => {
  router.push({ name: 'NewTaskOfTransaction', params: { id: props.id } })
}

const editTask = (task) => {
  router.push({ name: 'Task', params: { id: task.id } })
}

const deletedTask = (deletedTask) => {
  let idx = transaction.value.tasks.findIndex((t) => t.id === deletedTask.id)
  if (idx >= 0) {
    transaction.value.tasks.splice(idx, 1)
  }
}

const transaction = ref(emptyTransaction())

const filterByCompleted = ref(0)

watch(
  () => props.id,
  (newValue) => {
    loadTransaction(newValue)
  },
  { immediate: true }
)

const filteredTasks = computed(() => {
  return transaction.value.tasks.filter(t =>
    filterByCompleted.value == -1
    || filterByCompleted.value == 0 && !t.completed
    || filterByCompleted.value == 1 && t.completed
  )
})

const totalTasks = computed(() => {
  return transaction.value.tasks.reduce((c, t) =>
    filterByCompleted.value == -1
      || filterByCompleted.value == 0 && !t.completed
      || filterByCompleted.value == 1 && t.completed
      ? c + 1 : c, 0)
})

</script>

<template>
  <div class="mx-2">
    <h3 class="mt-4">Transaction # {{ transaction.id }} : {{ transaction.name }}</h3>
  </div>
  <hr>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h5 class="mt-4">Transaction status: {{ transaction.status_name }}</h5>
    </div>
    <div class="mx-2 total-filtro">
      <h5 class="mt-4">Total tasks: {{ totalTasks }}</h5>
    </div>
  </div>
  <div class="mb-4 d-flex flex-wrap justify-content-between">
    <div class="mx-2 mt-2 flex-grow-1">
      <label for="selectCompleted" class="form-label">Filter by completed:</label>
      <select class="form-select" id="selectCompleted" v-model="filterByCompleted">
        <option value="-1">Any</option>
        <option value="0">Pending Tasks</option>
        <option value="1">Completed Tasks</option>
      </select>
    </div>
    <div class="mx-2 mt-2">
      <button type="button" class="btn btn-secondary px-4 btn-top" @click="editTransaction"><i
          class="bi bi-xs bi-pencil"></i>&nbsp; Edit Transaction</button>
    </div>
    <div class="mx-2 mt-2">
      <button type="button" class="btn btn-success px-4 btn-top" @click="addTask"><i
          class="bi bi-xs bi-plus-circle"></i>&nbsp; Add Task</button>
    </div>
  </div>

  <task-table :tasks="filteredTasks" :showId="true" :showOwner="true" :showTransaction="false" @edit="editTask"
    @deleted="deletedTask"></task-table>
</template>


<style scoped>
.btn-top {
  margin-top: 1.85rem;
}
</style>
