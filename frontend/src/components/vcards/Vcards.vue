<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, onMounted } from 'vue'

const router = useRouter()

const loadVCards = async () => {

  try {
    const response = await axios.get('users/' + userId + '/tasks')
    tasks.value = response.data.data
  } catch (error) {
    console.log(error)
  }
}

const loadProjects = async () => {
  try {
    const response = await axios.get('projects')
    projects.value = response.data.data
  } catch (error) {
    console.log(error)
  }
}

const addVCard = () => {
  router.push({ name: 'NewVCard' })
}

const editVCard = (task) => {
  router.push({ name: 'VCard', params: { id: task.id } })
}


const deletedVCard = (deletedVCard) => {
  let idx = tasks.value.findIndex((t) => t.id === deletedVCard.id)
  if (idx >= 0) {
    tasks.value.splice(idx, 1)
  }
}

const props = defineProps({
  tasksTitle: {
    type: String,
    default: 'Vcards'
  },
  onlyCurrentVCards: {
    type: Boolean,
    default: false
  }
})

const tasks = ref([])
const projects = ref([])
const filterByProjectId = ref(-1)
const filterByCompleted = ref(-1)

const filteredVCards = computed(() => {
  return tasks.value.filter(t =>
    (props.onlyCurrentVCards && !t.completed)
    ||
    (!props.onlyCurrentVCards && (
      (filterByProjectId.value == -1
        || filterByProjectId.value == t.project_id
      ) &&
      (filterByCompleted.value == -1
        || filterByCompleted.value == 0 && !t.completed
        || filterByCompleted.value == 1 && t.completed
      ))))

})

const totalVCards = computed(() => {
  return tasks.value.reduce((c, t) =>
    (props.onlyCurrentVCards && !t.completed)
      ||
      (!props.onlyCurrentVCards && (
        (filterByProjectId.value == -1
          || filterByProjectId.value == t.project_id
        ) &&
        (filterByCompleted.value == -1
          || filterByCompleted.value == 0 && !t.completed
          || filterByCompleted.value == 1 && t.completed
        ))) ? c + 1 : c, 0)

})


onMounted(() => {
  loadProjects()
  loadVCards()
})
</script>

<template>
  <div class="d-flex justify-content-between">
    <div class="mx-2">
      <h3 class="mt-4">VCard</h3>
    </div>
  </div>
  <hr>
  <div class="card text-center m-4">
    <div class="card-header bg-dark text-white">
      <h5>933282291</h5>
    </div>
    <div class="card-body">
      <h5 class="card-title">{{}}</h5>
      <h3 class="pt-2">BALANCE</h3>
      <h5 class="card-text pb-3 pt-1">20.22€</h5>
    </div>
    <div class="card-footer text-muted">
      <h5>MAX DEBIT</h5>
      30 000€
    </div>
  </div>
</template>


<style scoped>
.filter-div {
  min-width: 12rem;
}

.total-filtro {
  margin-top: 0.35rem;
}

.btn-addtask {
  margin-top: 1.85rem;
}
</style>
