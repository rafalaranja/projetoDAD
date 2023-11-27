<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, onMounted } from 'vue'

const router = useRouter()

const loadTasks = async () => {

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

const addTask = () => {
  router.push({ name: 'NewTask' })
}

const editTask = (task) => {
  router.push({ name: 'Task', params: { id: task.id } })
}


const deletedTask = (deletedTask) => {
  let idx = tasks.value.findIndex((t) => t.id === deletedTask.id)
  if (idx >= 0) {
    tasks.value.splice(idx, 1)
  }
}

const props = defineProps({
  tasksTitle: {
    type: String,
    default: 'Vcards'
  },
  onlyCurrentTasks: {
    type: Boolean,
    default: false
  }
})

const tasks = ref([])
const projects = ref([])
const filterByProjectId = ref(-1)
const filterByCompleted = ref(-1)

const filteredTasks = computed(() => {
  return tasks.value.filter(t =>
    (props.onlyCurrentTasks && !t.completed)
    ||
    (!props.onlyCurrentTasks && (
      (filterByProjectId.value == -1
        || filterByProjectId.value == t.project_id
      ) &&
      (filterByCompleted.value == -1
        || filterByCompleted.value == 0 && !t.completed
        || filterByCompleted.value == 1 && t.completed
      ))))

})

const totalTasks = computed(() => {
  return tasks.value.reduce((c, t) =>
    (props.onlyCurrentTasks && !t.completed)
      ||
      (!props.onlyCurrentTasks && (
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
  loadTasks()
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
