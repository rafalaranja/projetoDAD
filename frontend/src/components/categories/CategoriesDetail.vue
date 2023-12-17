<script>
import axios from 'axios';

export default {
  props: ['categorie'],
  methods: {
    async save() {
      const response = await axios.put(`http://backend.test/api/categories/${this.categorie.id}`, this.categorie);
      this.$emit('update', response.data);
    }
  }
};
</script>

<template>
  <div>
    <h2>Edit Categorie</h2>
    <input v-model="categorie.name" />
    <button @click="save">Save</button>
  </div>
</template>