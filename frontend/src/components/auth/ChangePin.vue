<script setup>
import { useToast } from "vue-toastification";
import { useRouter } from "vue-router";
import { useUserStore } from "../../stores/users.js";
import { ref } from "vue";
import axios from "axios";

const toast = useToast();
const router = useRouter();
const userStore = useUserStore();

const currentPassword = ref("");
const pin = ref("");
const pinConfirmation = ref("");

const pins = ref({
  current_password: "",
  pin: "",
  pin_confirmation: "",
});

const errors = ref(null);

const emit = defineEmits(["changedPin"]);

const changePin = async () => {
  try {
    await userStore.changePin(pins.value);
    toast.success("PIN has been changed.");
    emit("changedPin");
    router.back();
  } catch (error) {
    if (error.response && error.response.status == 422) {
      errors.value = error.response.data.errors;
      toast.error("PIN has not been changed due to validation errors!");
    } else {
      toast.error("PIN has not been changed due to unknown server error!");
    }
  }
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="changePin">
    <h3 class="mt-5 mb-3">Change Pin</h3>
    <hr />
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputCurrentPassword" class="form-label"
          >Current Password</label
        >
        <input
          type="password"
          class="form-control"
          id="inputCurrentPassword"
          required
          v-model="pins.current_password"
        />
        <field-error-message
          :errors="errors"
          fieldName="current_password"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPassword" class="form-label">New Pin</label>
        <input
          type="password"
          class="form-control"
          id="inputPassword"
          required
          v-model="pins.pin"
        />
        <field-error-message
          :errors="errors"
          fieldName="pin"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3">
      <div class="mb-3">
        <label for="inputPasswordConfirm" class="form-label"
          >Pin Confirmation</label
        >
        <input
          type="password"
          class="form-control"
          id="inputPasswordConfirm"
          required
          v-model="pins.pin_confirmation"
        />
        <field-error-message
          :errors="errors"
          fieldName="pinConfirmation"
        ></field-error-message>
      </div>
    </div>
    <div class="mb-3 d-flex justify-content-center">
      <button type="button" class="btn btn-primary px-5" @click="changePin">
        Change Pin
      </button>
    </div>
  </form>
</template>
