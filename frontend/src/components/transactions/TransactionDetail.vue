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
</template>
