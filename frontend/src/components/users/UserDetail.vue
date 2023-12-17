<script setup>
import { ref, watch, computed, inject } from "vue";
import avatarNoneUrl from "@/assets/avatar-none.png";
import { useUserStore } from "../../stores/users.js";

const serverBaseUrl = inject("serverBaseUrl");
const userStore = useUserStore();

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: false,
  },
});

const emit = defineEmits(["save", "cancel"]);

const editingImageAsBase64 = ref("");

const editingUser = ref(props.user);
const inputPhotoFile = ref(null);
const deletePhotoOnTheServer = ref(false);

watch(
  () => props.user,
  (newUser) => {
    editingUser.value = newUser;
  },
  { immediate: true }
);
const changePhotoFile = () => {
  try {
    const file = inputPhotoFile.value.files[0];
    if (!file) {
      editingImageAsBase64.value = null;
    } else {
      const reader = new FileReader();
      reader.addEventListener(
        "load",
        () => {
          // convert image file to base64 string
          editingImageAsBase64.value = reader.result;
          deletePhotoOnTheServer.value = false;
        },
        false
      );
      if (file) {
        reader.readAsDataURL(file);
      }
    }
  } catch (error) {
    editingImageAsBase64.value = null;
  }
};

const resetToOriginalPhoto = () => {
  deletePhotoOnTheServer.value = false;
  inputPhotoFile.value.value = "";
  changePhotoFile();
};

const cleanPhoto = () => {
  deletePhotoOnTheServer.value = true;
};

const photoFullUrl = computed(() => {
  return editingUser.value.photo_url
    ? serverBaseUrl + "/storage/fotos/" + editingUser.value.photo_url
    : avatarNoneUrl;
});

const save = () => {
  try {
    const userToSave = editingUser.value;
    userToSave.deletePhotoOnServer = deletePhotoOnTheServer.value;
    userToSave.base64ImagePhoto = editingImageAsBase64.value;

    emit("save", userToSave);
  } catch (error) {
    console.error("Error in save function:", error);
  }
};
const cancel = () => {
  emit("cancel", editingUser.value);
};
</script>

<template>
  <form class="row g-3 needs-validation" novalidate @submit.prevent="save">
    <h3 class="mt-5 mb-3">Edit {{ editingUser.name }} Profile</h3>
    <hr />


    <!-- IN CASE THE USER IS NOT EDITING HIMSELF-->
    <div v-if="userStore.user.type == 'A' && userStore.user.id != editingUser.id">

      <!-- IN CASE THE EDITED USER IS ADMIN-->
      <div v-if="editingUser.type == 'A'">
        <div class="d-flex flex-wrap justify-content-between">
          <div class="w-75 pe-4">
            <div class="mb-3" v-if="editingUser.type != 'A'">
              <label for="" class="form-label">VCard</label>
              <input type="text" class="form-control" v-model="editingUser.id" disabled />
              <field-error-message :errors="errors" fieldName="name"></field-error-message>
            </div>

            <div class="mb-3">
              <label for="inputName" class="form-label">Name</label>
              <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['name'] : false }"
                id="inputName" placeholder="User Name" required v-model="editingUser.name" />
              <field-error-message :errors="errors" fieldName="name"></field-error-message>
            </div>

            <div class="mb-3 px-1">
              <label for="inputEmail" class="form-label">Email</label>
              <input type="email" class="form-control" :class="{ 'is-invalid': errors ? errors['email'] : false }"
                id="inputEmail" placeholder="Email" required v-model="editingUser.email" />
              <field-error-message :errors="errors" fieldName="email"></field-error-message>
            </div>

            <div class="d-flex ms-1 mt-4 flex-wrap justify-content-between">
              <div class="mb-3 me-3 flex-grow-1">
                <div class="form-check" v-if="editingUser.type === 'A' || userStore.type == 'A'">
                  <input class="form-check-input" :class="{ 'is-invalid': errors ? errors['type'] : false }"
                    type="checkbox" true-value="A" false-value="M" v-model="editingUser.type" id="inputType" disabled />
                  <label class="form-check-label" for="inputType">
                    User is Administrator
                  </label>
                </div>
                <field-error-message :errors="errors" fieldName="type"></field-error-message>
              </div>
            </div>
            <div class="mb-3 px-1 mt-4">
              <label class="form-check-label" for="inputType">
                Blocked
              </label>
              <input type="checkbox" class="ms-3 form-check-input p-2 d-inline-flex" id="inputBlocked"
                v-model="editingUser.blocked" />
              <field-error-message :errors="errors" fieldName="blocked"></field-error-message>
            </div>
          </div>
          <div class="w-25">
            <div class="d-flex flex-column">
              <label class="form-label">Photo</label>
              <div class="form-control text-center">
                <img :src="photoFullUrl" class="w-100" />
              </div>
              <div class="mt-3 d-flex justify-content-between flex-wrap">
                <label for="inputPhoto" class="btn btn-dark flex-grow-1 mx-1">Load</label>
                <button class="btn btn-secondary flex-grow-1 mx-1" @click.prevent="resetToOriginalPhoto"
                  v-if="editingUser.photo_url">
                  Reset
                </button>
                <button class="btn btn-danger flex-grow-1 mx-1" @click.prevent="cleanPhoto"
                  v-show="editingUser.photo_url || editingImageAsBase64">
                  Delete
                </button>
              </div>
              <div>
                <field-error-message :errors="errors" fieldName="base64ImagePhoto"></field-error-message>
              </div>
            </div>
            <input type="file" class="form-control visually-hidden" id="inputPhoto" accept="image/*" ref="inputPhotoFile"
              @change="changePhotoFile" />
          </div>
        </div>
        <div class="mb-3 d-flex justify-content-end">
          <button type="button" class="btn btn-success px-5" @click="save">
            Save
          </button>
          <button type="button" class="btn btn-light px-5" @click="cancel">
            Cancel
          </button>
        </div>
      </div>

      <!-- IN CASE THE EDITED USER IS NOT ADMIN-->
      <div v-if="editingUser.type != 'A'">
        <div class="d-flex flex-wrap justify-content-between">
          <div class="w-75 pe-4">
            <div class="mb-3" v-if="editingUser.type != 'A'">
              <label for="" class="form-label">VCard</label>
              <input type="text" class="form-control" v-model="editingUser.id" disabled />
              <field-error-message :errors="errors" fieldName="name"></field-error-message>
            </div>

            <div class="mb-3">
              <label for="inputName" class="form-label">Name</label>
              <input type="text" class="form-control" disabled id="inputName" required v-model="editingUser.name" />
              <field-error-message :errors="errors" fieldName="name"></field-error-message>
            </div>

            <div class="mb-3 px-1">
              <label for="inputEmail" class="form-label">Email</label>
              <input type="email" class="form-control" disabled id="inputEmail" required v-model="editingUser.email" />
              <field-error-message :errors="errors" fieldName="email"></field-error-message>
            </div>
            <!--
            <div class="mb-3 px-1">
              <label for="input" class="form-label">Balance</label>
              <input type="email" class="form-control" disabled id="inputEmail" required v-model="editingUser.balance" />
              <field-error-message :errors="errors" fieldName="email"></field-error-message>
            </div>
            <div class="mb-3 px-1">
              <label for="input" class="form-label">Max Debit</label>
              <input type="email" class="form-control" id="inputEmail" required v-model="editingUser.debit" />
              <field-error-message :errors="errors" fieldName="email"></field-error-message>
            </div>
            -->
            <div class="mb-3 px-1 mt-4">
              <label for="inputBlocked" class="form-label">Blocked</label>
              <input type="checkbox" class="ms-3 form-check-input p-2 d-inline-flex" id="inputBlocked"
                v-model="editingUser.blocked" />
              <field-error-message :errors="errors" fieldName="blocked"></field-error-message>
            </div>
          </div>
          <div class="w-25">
            <div class="d-flex flex-column">
              <label class="form-label">Photo</label>
              <div class="form-control text-center">
                <img :src="photoFullUrl" class="w-100" />
              </div>
              <div>
                <field-error-message :errors="errors" fieldName="base64ImagePhoto"></field-error-message>
              </div>
            </div>
            <div class="mb-3 d-flex justify-content-end mt-3">
              <button type="button" class="btn btn-success px-5" @click="save">
                Save
              </button>
              <button type="button" class="btn btn-light px-5" @click="cancel">
                Cancel
              </button>
            </div>
            <input type="file" class="form-control visually-hidden" id="inputPhoto" accept="image/*" ref="inputPhotoFile"
              @change="changePhotoFile" />
          </div>
        </div>
      </div>
    </div>

    <!-- IN CASE THE USER IS EDITING HIMSELF-->
    <div v-else>
      <div class="d-flex flex-wrap justify-content-between">
        <div class="w-75 pe-4">
          <div class="mb-3" v-if="editingUser.type != 'A'">
            <label for="" class="form-label">VCard</label>
            <input type="text" class="form-control" v-model="editingUser.id" disabled />
            <field-error-message :errors="errors" fieldName="name"></field-error-message>
          </div>

          <div class="mb-3">
            <label for="inputName" class="form-label">Name</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': errors ? errors['name'] : false }"
              id="inputName" placeholder="User Name" required v-model="editingUser.name" />
            <field-error-message :errors="errors" fieldName="name"></field-error-message>
          </div>

          <div class="mb-3 px-1">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" :class="{ 'is-invalid': errors ? errors['email'] : false }"
              id="inputEmail" placeholder="Email" required v-model="editingUser.email" />
            <field-error-message :errors="errors" fieldName="email"></field-error-message>
          </div>

          <div class="d-flex ms-1 mt-4 flex-wrap justify-content-between">
            <div class="mb-3 me-3 flex-grow-1">
              <div class="form-check" v-if="editingUser.type === 'A' || userStore.type == 'A'">
                <input class="form-check-input" :class="{ 'is-invalid': errors ? errors['type'] : false }" type="checkbox"
                  true-value="A" false-value="M" v-model="editingUser.type" id="inputType" disabled />
                <label class="form-check-label" for="inputType">
                  User is Administrator
                </label>
              </div>
              <field-error-message :errors="errors" fieldName="type"></field-error-message>
            </div>
          </div>
        </div>
        <div class="w-25">
          <div class="d-flex flex-column">
            <label class="form-label">Photo</label>
            <div class="form-control text-center">
              <img :src="photoFullUrl" class="w-100" />
            </div>
            <div class="mt-3 d-flex justify-content-between flex-wrap">
              <label for="inputPhoto" class="btn btn-dark flex-grow-1 mx-1">Load</label>
              <button class="btn btn-secondary flex-grow-1 mx-1" @click.prevent="resetToOriginalPhoto"
                v-if="editingUser.photo_url">
                Reset
              </button>
              <button class="btn btn-danger flex-grow-1 mx-1" @click.prevent="cleanPhoto"
                v-show="editingUser.photo_url || editingImageAsBase64">
                Delete
              </button>
            </div>
            <div>
              <field-error-message :errors="errors" fieldName="base64ImagePhoto"></field-error-message>
            </div>
          </div>
          <input type="file" class="form-control visually-hidden" id="inputPhoto" accept="image/*" ref="inputPhotoFile"
            @change="changePhotoFile" />
        </div>
      </div>
      <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-success px-5" @click="save">
          Save
        </button>
        <button type="button" class="btn btn-light px-5" @click="cancel">
          Cancel
        </button>
      </div>
    </div>
  </form>
</template>

<style scoped>
.total_hours {
  width: 26rem;
}

/* Change the checkbox color to green */
input[type="checkbox"].form-check-input:checked {
  background-color: rgba(80, 53, 150, 255) !important;
  border-color: rgba(80, 53, 150, 255) !important;
}
</style>
