<script setup>
import avatarNoneUrl from "@/assets/avatar-none.png";
import { useUserStore } from "../../stores/users.js";

const serverBaseUrl = inject("serverBaseUrl");

const props = defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showUsername: {
    type: Boolean,
    default: true,
  },
  showAdmin: {
    type: Boolean,
    default: true,
  },
  showPhoto: {
    type: Boolean,
    default: true,
  },
  showEditButton: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["show"]);

const editClick = (categorie) => {
  emit("edit", categorie);
};
</script>

<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-1 border-bottom">
    <h1 class="h3">categories</h1>
  </div>
  <table class="table table-hover table-sm mt-3 m-1 border">
    <thead class="table-dark">
      <tr>
        <th class="align-middle">Name</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="categorie in categories" :key="categorie.id">
        <td class="align-middle">{{ categorie.name }}</td>
        <td class="text-end align-middle" >
          <div class="d-flex justify-content-end" >
            <button class="btn btn-xs btn-light" @click="editClick(categorie)" v-if="showEditButton">
              <i class="bi bi-xs bi-pencil"></i>
            </button>
          </div>
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

.img_photo {
  width: 3.2rem;
  height: 3.2rem;
}
</style>
