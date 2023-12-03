<!--<script setup>
import { inject } from "vue";
import avatarNoneUrl from '@/assets/avatar-none.png'

const serverBaseUrl = inject("serverBaseUrl");

const props = defineProps({
  users: {
    type: Array,
    default: () => [],
  },
  showId: {
    type: Boolean,
    default: true,
  },
  showEmail: {
    type: Boolean,
    default: true,
  },
  showAdmin: {
    type: Boolean,
    default: true,
  },
  showGender: {
    type: Boolean,
    default: false,
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
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">About</h1>
    </div>
    <div>
      <h4><img src="@/assets/logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">Vcard</h4>
      <h5>Introducing VCard - Redefining Online Transactions</h5>

<p>Welcome to VCard, the groundbreaking solution set to revolutionize the way you conduct online transactions. If
    you're in search of convenience, security, and efficiency in your financial operations, VCard is the answer.
    Developed with state-of-the-art security technology, VCard offers a seamless online payment experience for users
    worldwide.</p>

<h6>Security First:</h6>
<p>With VCard, the security of your financial data is our top priority. We employ cutting-edge encryption to ensure
    that all your transactions are shielded from cyber threats. Feel at ease when making online purchases,
    transfers, and payments, knowing that VCard is tirelessly working to keep your information secure.</p>

<h6>Simple and Intuitive:</h6>
<p>Simplicity is at the core of VCard. Our intuitive interface is designed to ensure that even users less familiar
    with online transactions can navigate effortlessly. With just a few clicks, you can send or receive money, pay
    bills, and make purchases, all without complications.</p>

<h6>Globally Connected:</h6>
<p>No matter where you are in the world, VCard provides global support. Conduct transactions with ease, regardless
    of the currency or location of the recipient. Our currency conversion system is fast and efficient, allowing you
    to take advantage of VCard anywhere on the planet.</p>

<h6>Exceptional Customer Support:</h6>
<p>At VCard, customer satisfaction is paramount. Our dedicated customer support is available 24/7 to assist with any
    questions or issues you may have. We are committed to delivering the best possible experience to our users.</p>

<h6>Conclusion:</h6>
<p>In summary, VCard is more than just an online transaction platform—it's the key to simplifying and enhancing
    your financial life. Join millions of satisfied users who trust VCard for their daily transactions. Download now
    and discover how easy and secure it is to manage your money online with VCard. Transform your transactions,
    transform your life.
</p>
    </div>
    
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
-->

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
  <table class="table">
    <thead>
      <tr>
        <th v-if="showId" class="align-middle">#</th>
        <th v-if="showPhoto" class="align-middle">Photo</th>
        <th class="align-middle">Name</th>
        <th v-if="showUsername" class="align-middle">Phone Number</th>
        <th v-if="showAdmin" class="align-middle">Admin</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in users" :key="user.id">
        <td v-if="showId" class="align-middle">{{ user.id }}</td>
        <td v-if="showPhoto" class="align-middle">
          <img :src="photoFullUrl(user)" class="rounded-circle img_photo" />
        </td>
        <td class="align-middle">{{ user.name }}</td>
        <td v-if="showUsername" class="align-middle">{{ user.username }}</td>
        <td v-if="showAdmin" class="align-middle">
          {{ user.user_type == "A" ? "Sim" : "Não" }}
        </td>
        <td class="text-end align-middle" v-if="showEditButton">
          <div class="d-flex justify-content-end" v-if="canViewUserDetail(user.id)">
            <button class="btn btn-xs btn-light" @click="editClick(user)" v-if="showEditButton">
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
