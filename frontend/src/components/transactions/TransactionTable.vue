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
        <td>{{ transaction.vcard }}</td>
        <td>{{ transaction.date }}</td>
        <td>{{ transaction.type }}</td>
        <td>{{ transaction.value }}</td>
        <td>{{ transaction.payment_type }}</td>
        <td>{{ transaction.payment_reference }}</td>
        <td>{{ transaction.description }}</td>
        
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
