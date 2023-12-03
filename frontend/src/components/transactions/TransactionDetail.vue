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
      <h6>Date: 29/09/2021</h6>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between mx-5 mt-3">
        <h5>VCard: 132312</h5>
        <h5>Pair VCard: 31212312</h5>
      </div>
      <div class="d-flex justify-content-end mx-5 mt-4">
        <h6>Type: C</h6>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Payment Type: MBWay</h6>
        <h6>Payment Reference: PT49941240320</h6>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-4">
        <h5>Value:</h5>
      </div>
      <div class="d-flex justify-content-center">
        <h2 class="text-center">10,00€</h2>
      </div>



      <div class="d-flex justify-content-between mx-5 mt-2">
        <h6></h6>
      </div>
    </div>
  </div>
</template>
