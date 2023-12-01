<script setup>
import axios from 'axios'
import { useToast } from "vue-toastification"
import { ref, watch, computed } from "vue"

const toast = useToast()

const props = defineProps({
  vcards: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showCompleted: {
    type: Boolean,
    default: true,
  },
  showOwner: {
    type: Boolean,
    default: true,
  },
  showTransaction: {
    type: Boolean,
    default: true,
  },
  showCompletedButton: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
  showDeleteButton: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(["completeToggled", "edit", "deleted"])

const editingVcards = ref(props.vcards)
const vcardToDelete = ref(null)
const deleteConfirmationDialog = ref(null)

watch(
  () => props.vcards,
  (newVcards) => {
    editingVcards.value = newVcards
  }
)

// Alternative to previous watch
// watchEffect(() => {
//   editingVcards.value = props.vcards
// })

const toogleClick = async (vcard) => {
  try {
    const response = await axios.patch('vcards/' + vcard.id + '/completed', { completed: !vcard.completed })
    vcard.completed = response.data.data.completed
    emit("completeToggled", vcard)
  } catch (error) {
    console.log(error)
  }
}
const editClick = (vcard) => {
  emit("edit", vcard)
}

const deleteClick = (vcard) => {
  vcardToDelete.value = vcard
  deleteConfirmationDialog.value.show()
}

const deleteVcardConfirmed = async () => {
  try {
    const response = await axios.delete('vcards/' + vcardToDelete.value.id)
    let deletedVcard = response.data.data
    toast.info(`Vcard ${vcardToDeleteDescription.value} was deleted`)
    emit("deleted", deletedVcard)
  } catch (error) {
    console.log(error)
    toast.error(`It was not possible to delete Vcard ${vcardToDeleteDescription.value}!`)
  }
}

const vcardToDeleteDescription = computed(() => vcardToDelete.value
  ? `#${vcardToDelete.value.id} (${vcardToDelete.value.description})`
  : "")

</script>

<template>
  <confirmation-dialog ref="deleteConfirmationDialog" confirmationBtn="Delete vcard"
    :msg="`Do you really want to delete the vcard ${vcardToDeleteDescription}?`" @confirmed="deleteVcardConfirmed">
  </confirmation-dialog>

  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">#</th>
        <th class="text-center" v-if="showCompleted">Completed</th>
        <th>Description</th>
        <th v-if="showOwner">Owner</th>
        <th v-if="showTransaction">Transaction</th>
        <th v-if="showCompletedButton || showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="vcard in editingVcards" :key="vcard.id">
        <td v-if="showId">{{ vcard.id }}</td>
        <td class="text-center" v-if="showCompleted">
          {{ vcard.completed ? "yes" : "-" }}
        </td>
        <td>
          <span :class="{ completed: vcard.completed }">{{ vcard.description }}</span>
        </td>
        <td v-if="showOwner">{{ vcard.owner_name }}</td>
        <td v-if="showTransaction">{{ vcard.transaction_name }}</td>
        <td class="text-end" v-if="showCompletedButton || showEditButton || showDeleteButton">
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="toogleClick(vcard)" v-if="showCompletedButton">
              <i class="bi bi-xs" :class="{
                'bi-square': !vcard.completed,
                'bi-check2-square': vcard.completed,
              }"></i>
            </button>

            <button class="btn btn-xs btn-light" @click="editClick(vcard)" v-if="showEditButton">
              <i class="bi bi-xs bi-pencil"></i>
            </button>

            <button class="btn btn-xs btn-light" @click="deleteClick(vcard)" v-if="showDeleteButton">
              <i class="bi bi-xs bi-x-square-fill"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.completed {
  text-decoration: line-through;
}

button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>
