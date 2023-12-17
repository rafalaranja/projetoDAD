<script setup>
import { ref, watch, computed } from "vue";
import { useRouter, RouterLink, RouterView } from "vue-router";

const router = useRouter();

const props = defineProps({
  transaction: {
    type: Object,
    required: true,
  },
});

const currentTransaction = ref(props.transaction);

watch(
  () => props.transaction,
  (newTransaction) => {
    currentTransaction.value = newTransaction;
  }
);

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return `${date.getUTCDate().toString().padStart(2, "0")}-${(
    date.getUTCMonth() + 1
  )
    .toString()
    .padStart(2, "0")}-${date.getUTCFullYear()} ${date
      .getUTCHours()
      .toString()
      .padStart(2, "0")}:${date
        .getUTCMinutes()
        .toString()
        .padStart(2, "0")}:${date.getUTCSeconds().toString().padStart(2, "0")}`;
};
</script>
<template>
  <h3 class="mt-5 mb-3">Transaction #{{ currentTransaction.id }}</h3>
  <hr />
  <div class="card">
    <div class="card-header bg-dark text-white">
      <h6>Date: {{ formatDate(currentTransaction.datetime) }}</h6>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between mx-5 mt-3">
        <h5>VCard: {{ currentTransaction.vcard }}</h5>
        <h5 v-if="currentTransaction.pair_vcard">
          Pair VCard: {{ currentTransaction.pair_vcard }}
        </h5>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Payment Type: {{ currentTransaction.payment_type }}</h6>
        <h6>Payment Reference: {{ currentTransaction.payment_reference }}</h6>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-5">
        <h4>Value:</h4>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-4">
        <div class="card bg-dark p-3 text-white">
          <h2 v-if="currentTransaction.type == 'C'">
            {{ currentTransaction.value }}€
          </h2>
          <h2 v-else> - {{  currentTransaction.value }}€</h2>
        </div>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Old Balance: {{ currentTransaction.old_balance }}</h6>
        <h6>New Balance: {{ currentTransaction.new_balance }}</h6>
      </div>
      <div class="d-flex justify-content-center mx-auto mt-5 text-center" v-if="currentTransaction.description">
        <h6>Description :
          <h5>
            <div class="mt-3 card bg-dark text-white text-center p-3">{{ currentTransaction.description }}</div>
          </h5>
        </h6>
      </div>
    </div>
  </div>
  <router-link :class="{ active: $route.name === 'Transactions' }" :to="{ name: 'Transactions' }">
    <button type="button" class="btn btn-danger mt-3">Back</button>
  </router-link>
</template>
