<script setup>
import axios from "axios";
import { useToast } from "vue-toastification";
import { useRouter, onBeforeRouteLeave } from "vue-router";
import { ref, watch, computed, onMounted } from "vue";

import VcardDetail from "./VcardDetail.vue";

const toast = useToast();
const router = useRouter();

const newVcard = () => {
  return {
    id: null,
    owner_id: 1, // Change it later
    transaction_id: null,
    completed: false,
    description: "",
    notes: "",
    total_hours: null,
  };
};
const vcard = ref(newVcard());
const transactions = ref([]);
const errors = ref(null);
const confirmationLeaveDialog = ref(null);
// String with the JSON representation after loading the transaction (new or edit)
let originalValueStr = "";

const loadVcard = async (id) => {
  originalValueStr = "";
  errors.value = null;
  if (!id || id < 0) {
    vcard.value = newVcard();
    originalValueStr = JSON.stringify(vcard.value);
  } else {
    try {
      const response = await axios.get("vcards/" + id);
      vcard.value = response.data.data;
      originalValueStr = JSON.stringify(vcard.value);
    } catch (error) {
      console.log(error);
    }
  }
};

const save = async () => {
  errors.value = null;
  if (operation.value == "insert") {
    try {
      const response = await axios.post("vcards", vcard.value);
      vcard.value = response.data.data;
      originalValueStr = JSON.stringify(vcard.value);
      toast.success("Vcard #" + vcard.value.id + " was created successfully.");
      router.back();
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors;
        toast.error("Vcard was not created due to validation errors!");
      } else {
        toast.error("Vcard was not created due to unknown server error!");
      }
    }
  } else {
    try {
      const response = await axios.put("vcards/" + props.id, vcard.value);
      vcard.value = response.data.data;
      originalValueStr = JSON.stringify(vcard.value);
      toast.success("Vcard #" + vcard.value.id + " was updated successfully.");
      router.back();
    } catch (error) {
      if (error.response.status == 422) {
        errors.value = error.response.data.errors;
        toast.error(
          "Vcard #" + props.id + " was not updated due to validation errors!"
        );
      } else {
        toast.error(
          "Vcard #" + props.id + " was not updated due to unknown server error!"
        );
      }
    }
  }
};

const cancel = () => {
  originalValueStr = JSON.stringify(vcard.value);
  router.back();
};

const props = defineProps({
  id: {
    type: Number,
    default: null,
  },
  fixedTransaction: {
    type: Number,
    default: null,
  },
});

const operation = computed(() =>
  !props.id || props.id < 0 ? "insert" : "update"
);

// beforeRouteUpdate was not fired correctly
// Used this watcher instead to update the ID
watch(
  () => props.id,
  (newValue) => {
    loadVcard(newValue);
  },
  { immediate: true }
);

let nextCallBack = null;
const leaveConfirmed = () => {
  if (nextCallBack) {
    nextCallBack();
  }
};

onBeforeRouteLeave((to, from, next) => {
  nextCallBack = null;
  let newValueStr = JSON.stringify(vcard.value);
  if (originalValueStr != newValueStr) {
    // Some value has changed - only leave after confirmation
    nextCallBack = next;
    confirmationLeaveDialog.value.show();
  } else {
    // No value has changed, so we can leave the component without confirming
    next();
  }
});

onMounted(async () => {
  transactions.value = [];
  try {
    const response = await axios.get("transactions");
    transactions.value = response.data.data;
  } catch (error) {
    console.log(error);
  }
});
</script>

<template>
  <confirmation-dialog
    ref="confirmationLeaveDialog"
    confirmationBtn="Discard changes and leave"
    msg="Do you really want to leave? You have unsaved changes!"
    @confirmed="leaveConfirmed"
  >
  </confirmation-dialog>

  <vcard-detail
    :operationType="operation"
    :vcard="vcard"
    :transactions="transactions"
    :fixedTransaction="fixedTransaction"
    :errors="errors"
    @save="save"
    @cancel="cancel"
  ></vcard-detail>
</template>
