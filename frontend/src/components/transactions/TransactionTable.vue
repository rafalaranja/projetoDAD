<script setup>
import { defineProps, defineEmits } from "vue";
import { useUserStore } from "../../stores/users.js";
import { computed } from "vue";

const userStore = useUserStore();

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
  },
  showVcard: {
    type: Boolean,
    default: false,
  },
});

if (userStore.user.type == "A") {
  props.showVcard = true;
  console.log("showVcard: ", props.showVcard);
  console.log("userStore.user.type: ", userStore.user.type);
}

const emit = defineEmits(["view"]);

const viewTransaction = (transaction) => {
  emit("view", transaction);
  console.log(transaction);
};

const shouldShowVcard = computed(() => {
  return userStore.user.type == "A" || props.showVcard;
});
</script>

<template>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th v-if="shouldShowVcard">VCard</th>
        <th>Date</th>
        <th v-if="showDates">Value</th>
        <th v-if="showDates">Payment Type</th>
        <th v-if="showDates">Payment Reference</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="transaction in transactions" :key="transaction.id">
        <td>{{ transaction.id }}</td>
        <td v-if="shouldShowVcard">{{ transaction.vcard }}</td>
        <td>{{ new Date(transaction.date).toLocaleDateString('en-GB') }}</td>
        <td>{{ transaction.value }}</td>
        <td>{{ transaction.payment_type }}</td>
        <td>{{ transaction.payment_reference }}</td>
        <td>
          <button class="btn btn-outline-dark p-0 mx-auto d-flex" @click="viewTransaction(transaction)"
            v-if="showEditButton">
            <i class="bi bi-eye mx-auto px-2"></i>
          </button>
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
