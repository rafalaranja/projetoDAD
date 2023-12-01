<script setup>
  import { ref, watch, computed } from 'vue'

  const props = defineProps({
    vcard: {
      type: Object,
      required: true
    },
    operationType: {
      type: String,
      default: 'insert'  // insert / update
    },
    transactions: {
      type: Array,
      required: true
    },
    fixedTransaction: {
      type: Number,
      default: null
    },
    errors: {
      type: Object,
      required: false,
    },
  })

  const emit = defineEmits(['save', 'cancel'])

  const editingVcard = ref(props.vcard)

  watch(
    () => props.vcard,
    (newVcard) => {
      editingVcard.value = newVcard
    }
  )

  watch(
    () => props.fixedTransaction, 
    (newFixedTransaction) => {
      if (newFixedTransaction) {
        editingVcard.value.transaction_id = newFixedTransaction
      }
    },
    { immediate: true }
  )

  const vcardTitle = computed( () => {
    if (!editingVcard.value) {
        return ''
      }
      return props.operationType == 'insert' ? 'New Vcard' : 'Vcard #' + editingVcard.value.id
  })

  const save = () => {
    emit('save', editingVcard.value)
  }

  const cancel = () => {
    emit('cancel', editingVcard.value)
  }
</script>

<template>
  <form
    class="row g-3 needs-validation"
    novalidate
    @submit.prevent="save"
  >
    <h3 class="mt-5 mb-3">{{ vcardTitle }}</h3>
    <hr>

    <div class="d-flex flex-wrap justify-content-between">
      <div class="mb-3 checkCompleted">
        <div class="form-check">
          <input
            class="form-check-input"
            :class="{ 'is-invalid': errors ? errors['completed'] : false }"
            type="checkbox"
            v-model="editingVcard.completed"
            id="inputCompleted"
          >
          <label
            class="form-check-label"
            for="inputCompleted"
          >
            Vcard is Completed
          </label>
          <field-error-message :errors="errors" fieldName="completed"></field-error-message>
        </div>
      </div>
      <div
        class="row mb-3 total_hours"
        v-show="editingVcard.completed"
      >
        <label
          for="inputHours"
          class="col-sm-2 col-form-label"
        >Hours</label>
        <div class="col-sm-10">
          <input
            type="number"
            class="form-control"
            :class="{ 'is-invalid': errors ? errors['total_hours'] : false }"
            id="inputHours"
            placeholder="Total hours to complete the vcard"
            v-model="editingVcard.total_hours"
          >
          <field-error-message :errors="errors" fieldName="total_hours"></field-error-message>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label
        for="inputDescription"
        class="form-label"
      >Description</label>
      <input
        type="text"
        class="form-control"
        :class="{'is-invalid': errors ? errors['description']: false}"
        id="inputDescription"
        placeholder="Vcard Description"
        required
        v-model="editingVcard.description"
      >
      <field-error-message :errors="errors" fieldName="description"></field-error-message>
    </div>
    <div class="mb-3">
      <label
        for="inputTransaction"
        class="form-label"
      >Transaction</label>
      <select
        class="form-select"
        :class="{ 'is-invalid': errors ? errors['transaction_id'] : false }"
        id="inputTransaction"
        :disabled="fixedTransaction"
        v-model="editingVcard.transaction_id"
      >
        <option :value="null">-- No Transaction --</option>
        <option
          v-for="prj in transactions"
          :key="prj.id"
          :value="prj.id"
        >{{prj.name}}</option>
      </select>
      <field-error-message :errors="errors" fieldName="transaction_id"></field-error-message>
    </div>
    <div class="mb-3">
      <label
        for="inputNotes"
        class="form-label"
      >Notes</label>
      <textarea
        class="form-control"
        :class="{ 'is-invalid': errors ? errors['notes'] : false }"
        id="inputNotes"
        rows="4"
        v-model="editingVcard.notes"
      ></textarea>
      <field-error-message :errors="errors" fieldName="notes"></field-error-message>
    </div>

    <div class="mb-3 d-flex justify-content-end">
      <button
        type="button"
        class="btn btn-primary px-5"
        @click="save"
      >Save</button>
      <button
        type="button"
        class="btn btn-light px-5"
        @click="cancel"
      >Cancel</button>
    </div>
  </form>
</template>


<style scoped>
.total_hours {
  width: 26rem;
}
.checkCompleted {
  min-height: 2.375rem;
}
</style>
