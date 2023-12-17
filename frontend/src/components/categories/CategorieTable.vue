<script setup>
import { defineProps, defineEmits } from "vue";
import { useUserStore } from "../../stores/users.js";
import { computed } from "vue";

const userStore = useUserStore();

const props = defineProps({
  categories: {
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
});

const emit = defineEmits(["view"]);

const viewCategorie = (categorie) => {
  emit("view", categorie);
  console.log(categorie);
};
</script>

<template>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>VCard</th>
        <th>Type</th>
        <th>Name</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="categorie in categories" :key="categorie.id">
        <td>{{ categorie.id }}</td>
        <td>{{ categorie.vcard }}</td>
        <td>{{ categorie.type }}</td>
        <td>{{ categorie.name }}</td>
        <td>
          <button class="btn btn-outline-dark p-0 mx-auto d-flex" @click="viewCategorie(categorie)" v-if="showEditButton">
            <i class="bi bi-pencil mx-auto px-2"></i>
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
