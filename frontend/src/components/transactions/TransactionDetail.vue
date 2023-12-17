<script setup>
import { ref, watch, onMounted } from "vue";
import { defineProps } from 'vue';
import axios from "axios";

const props = defineProps({
  transaction: {
    type: Object,
    required: true,
  },
});

const categories = ref([]);

const loadCategories = async () => {
  try {
    if (props.transaction && props.transaction.vcard) {
      const response = await axios.get(`categories/${props.transaction.vcard}`);
      console.log(response.data);
      categories.value = response.data.data;
    } else {
      console.error('Invalid transaction or vcard is undefined.');
    }
  } catch (error) {
    console.error(error);
  }
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return `${date.getUTCDate().toString().padStart(2, "0")}-${(date.getUTCMonth() + 1).toString().padStart(2, "0")}-${date.getUTCFullYear()} ${date
    .getUTCHours()
    .toString()
    .padStart(2, "0")}:${date
    .getUTCMinutes()
    .toString()
    .padStart(2, "0")}:${date.getUTCSeconds().toString().padStart(2, "0")}`;
};

const getCategoryName = (categoryId) => {
  console.log("categoryId", categoryId);

  // Use .find para encontrar o objeto de categoria com o id correspondente
  const category = categories.value.find((cat) => cat.id === categoryId);

  console.log("category", category);

  return category ? category.name : "Unknown Category";
};

onMounted(async () => {
  await loadCategories();
});

watch(
  () => props.transaction,
  async (newTransaction) => {
    // Use ref diretamente para evitar problemas de reatividade
    categories.value = [];

    // Atualize a transação atual
    props.transaction = newTransaction;

    // Carregue as categorias
    await loadCategories();
  }
);
</script>

<template>
  <h3 class="mt-5 mb-3" v-if="props.transaction && props.transaction.id">Transaction #{{ props.transaction.id }}</h3>
  <hr />
  <div class="card">
    <div class="card-header bg-dark text-white">
      <h6>Date: {{ formatDate(props.transaction.datetime) }}</h6>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between mx-5 mt-3">
        <h5>VCard: {{ props.transaction.vcard }}</h5>
        <h5 v-if="props.transaction.category_id!=null">
          Category: {{ getCategoryName(props.transaction.category_id) }}
        </h5>
        <h5 v-if="props.transaction.pair_vcard">
          Pair VCard: {{ props.transaction.pair_vcard }}
        </h5>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Payment Type: {{ props.transaction.payment_type }}</h6>
        <h6>Payment Reference: {{ props.transaction.payment_reference }}</h6>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-5">
        <h4>Value:</h4>
      </div>
      <div class="d-flex justify-content-center mx-5 mt-4">
        <div class="card bg-dark p-3 text-white">
          <h2 v-if="props.transaction.type == 'C'">
            {{ props.transaction.value }}€
          </h2>
          <h2 v-else> - {{ props.transaction.value }}€</h2>
        </div>
      </div>
      <div class="d-flex justify-content-between mx-5 mt-4">
        <h6>Old Balance: {{ props.transaction.old_balance }}</h6>
        <h6>New Balance: {{ props.transaction.new_balance }}</h6>
      </div>
      <div class="d-flex justify-content-center mx-auto mt-5 text-center" v-if="props.transaction.description">
        <h6>Description :
          <h5>
            <div class="mt-3 card bg-dark text-white text-center p-3">{{ props.transaction.description }}</div>
          </h5>
        </h6>
      </div>
    </div>
  </div>
</template>
