<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
  vcard: {
    type: Object,
    required: true
  }
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
      <h5 class="card-text pb-3 pt-1">20€</h5>
    </div>
    <div class="card-footer text-muted">
      <h5>MAX DEBIT</h5>
      30 000€
    </div>
  </div>
</template>


<style scoped>
.total_hours {
  width: 26rem;
}

.checkCompleted {
  min-height: 2.375rem;
}
</style>