<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  transaction: {
    type: Object,
    required: true
  }
})

const currentTransaction = ref(props.transaction)

watch(
  () => props.transaction,
  (newTransaction) => {
    currentTransaction.value = newTransaction
  }
)

const transactionTitle = computed(() => {
  if (!currentTransaction.value) {
    return ''
  }
  return 'Transaction #' + currentTransaction.value.id
})

</script>
<template>
  <h3 class="mt-5 mb-3">{{ transactionTitle }}</h3>
  <hr>
  <div class="card">
    <div class="card-header bg-dark text-white">
      <h6>Date: {{ currentTransaction.date }}</h6>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between mx-5 mt-3">
        <h5>VCard: {{ currentTransaction.vcard }}</h5>
        <h5 v-if='currentTransaction.pair_vcard'>Pair VCard: {{ currentTransaction.pair_vcard }}</h5>
      </div>
      <div class="d-flex justify-content-end mx-5 mt-4">
        <h6>Type: {{ currentTransaction.type }}</h6>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Payment Type: {{ currentTransaction.payment_type }}</h6>
        <h6>Payment Reference: {{ currentTransaction.payment_reference }}</h6>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-4">
        <h5>Value:{{ currentTransaction.value }}€</h5>
      </div>



      <div class="d-flex justify-content-between mx-5 mt-2">
        <h6></h6>
      </div>
    </div>
  </div>
</template>