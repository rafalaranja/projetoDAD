<script setup>
import { inject } from "vue";
import avatarNoneUrl from "@/assets/avatar-none.png";
import { useUserStore } from "../../stores/users.js";

const serverBaseUrl = inject("serverBaseUrl");

const userStore = useUserStore();

const canViewUserDetail = (userId) => {
  if (!userStore.user) {
    return false;
  }
  return userStore.user.user_type == "A" || userStore.user.id == userId;
};



const props = defineProps({
  users: {
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

const emit = defineEmits(["edit"]);

const photoFullUrl = (user) => {
  return user.photo_url
    ? serverBaseUrl + "/storage/fotos/" + user.photo_url
    : avatarNoneUrl;
};

const editClick = (user) => {
  emit("edit", user);
};
</script>

<template>
  <table class="table table-hover table-sm mt-3 m-1 border">
    <thead class="table-dark">
      <tr>
        <th v-if="showId" class="align-middle">#</th>
        <th v-if="showPhoto" class="align-middle">Photo</th>
        <th class="align-middle">Name</th>
        <th v-if="showUsername" class="align-middle">Phone Number</th>
        <th v-if="showAdmin" class="align-middle">Admin</th>
        <th class="align-middle">Blocked</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td v-if="showId" class="align-middle">{{ user.id }}</td>
        <td v-if="showPhoto" class="align-middle">
          <img :src="photoFullUrl(user)" class="rounded-circle img_photo" />
        </td>
        <td class="align-middle ">{{ user.name }}</td>
        <td v-if="showUsername" class="align-middle">{{ user.username }}</td>
        <td v-if="showAdmin" class="align-middle">
          {{ user.user_type == "A" ? "Yes" : "No" }}
        </td>
        <td class="align-middle">{{ user.blocked == "1" ? "Yes" : "No" }}</td>
        <td class="text-end align-middle">
          <div class="d-flex justify-content-start">
            <button class="btn btn-xs btn-light" @click="editClick(user)">
              <i class="bi bi-xs bi-pencil edi"></i>
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
