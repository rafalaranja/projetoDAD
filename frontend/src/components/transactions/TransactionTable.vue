<script setup>
const props = defineProps({
  transactions: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showResponsible: {
    type: Boolean,
    default: true,
  },
  showDates: {
    type: Boolean,
    default: false,
  },
  showTotalHours: {
    type: Boolean,
    default: true,
  },
  showBillInformation: {
    type: Boolean,
    default: false,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
  showDeleteButton: {
    type: Boolean,
    default: true,
  }
})

const emit = defineEmits(['edit', 'delete'])

const editClick = (transaction) => {
  emit('edit', transaction)
}

const deleteClick = (transaction) => {
  emit('delete', transaction)
}
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId">ID</th>
        <th>VCard</th>
        <th>Date</th>
        <th v-if="showResponsible">Type</th>
        <th v-if="showDates">Value</th>
        <th v-if="showDates">Payment Type</th>
        <th v-if="showDates">Payment Reference</th>
        <th v-if="showDates">Description</th>


        <th v-if="showBillInformation">Billed</th>
        <th v-if="showBillInformation">Total Price</th>
        <th v-if="showEditButton || showDeleteButton"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="transaction in transactions" :key="transaction.id">
        <td v-if="showId">{{ transaction.id }}</td>
        <td>{{ transaction.name }}</td>
        <td>{{ transaction.status_name }}</td>
        <td v-if="showResponsible">{{ transaction.responsible_name }}</td>
        <td v-if="showDates">{{ transaction.preview_start_date }}</td>
        <td v-if="showDates">{{ transaction.preview_end_date }}</td>
        <td v-if="showDates">{{ transaction.real_start_date }}</td>
        <td v-if="showDates">{{ transaction.real_end_date }}</td>


        <td v-if="showBillInformation">{{ transaction.billed }}</td>
        <td v-if="showBillInformation">{{ transaction.total_price }}</td>
        <td class="text-end" v-if="showEditButton || showDeleteButton">
          <div class="d-flex justify-content-end">
            <button class="btn btn-xs btn-light" @click="editClick(transaction)" v-if="showEditButton"><i
                class="bi bi-xs bi-pencil"></i>
            </button>

            <button class="btn btn-xs btn-light" @click="deleteClick(transaction)" v-if="showDeleteButton"><i
                class="bi bi-xs bi-x-square-fill"></i>
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
button {
  margin-left: 3px;
  margin-right: 3px;
}
</style>
