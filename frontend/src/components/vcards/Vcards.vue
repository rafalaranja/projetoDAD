<script setup>
import axios from "axios";
import { useRouter } from "vue-router";
import { ref, computed, onMounted } from "vue";

const router = useRouter();

const loadVCards = async () => {
  try {
    const response = await axios.get("users/" + userId + "/tasks");
    vcards.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
};

const loadTransactions = async () => {
  try {
    const response = await axios.get("transactions");
    transactions.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
};

const addVCard = () => {
  router.push({ name: "NewVCard" });
};

const editVCard = (task) => {
  router.push({ name: "VCard", params: { id: task.id } });
};

const deletedVCard = (deletedVCard) => {
  let idx = vcards.value.findIndex((t) => t.id === deletedVCard.id);
  if (idx >= 0) {
    vcards.value.splice(idx, 1);
  }
};

const props = defineProps({
  vcardsTitle: {
    type: String,
    default: "Vcards",
  },
  onlyCurrentVCards: {
    type: Boolean,
    default: false,
  },
});

const vcards = ref([]);
const transactions = ref([]);
const filterByTransactionId = ref(-1);
const filterByCompleted = ref(-1);

const filteredVCards = computed(() => {
  return vcards.value.filter(
    (t) =>
      (props.onlyCurrentVCards && !t.completed) ||
      (!props.onlyCurrentVCards &&
        (filterByTransactionId.value == -1 ||
          filterByTransactionId.value == t.transaction_id) &&
        (filterByCompleted.value == -1 ||
          (filterByCompleted.value == 0 && !t.completed) ||
          (filterByCompleted.value == 1 && t.completed)))
  );
});

const totalVCards = computed(() => {
  return vcards.value.reduce(
    (c, t) =>
      (props.onlyCurrentVCards && !t.completed) ||
        (!props.onlyCurrentVCards &&
          (filterByTransactionId.value == -1 ||
            filterByTransactionId.value == t.transaction_id) &&
          (filterByCompleted.value == -1 ||
            (filterByCompleted.value == 0 && !t.completed) ||
            (filterByCompleted.value == 1 && t.completed)))
        ? c + 1
        : c,
    0
  );
});

onMounted(() => {
  loadTransactions();
  loadVCards();
});
</script>

<template></template>

<style scoped>
.filter-div {
  min-width: 12rem;
}

.total-filtro {
  margin-top: 0.35rem;
}

.btn-addtask {
  margin-top: 1.85rem;
}
</style>
