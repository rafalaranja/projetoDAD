<script>
import axios from 'axios';
import CategorieDetail from './CategoriesDetail.vue';

export default {
    components: {
        CategorieDetail
    },
    data() {
        return {
            categories: [],
            selectedCategorie: null
        };
    },
    async created() {
        const response = await axios.get('/categories');
        this.categories = response.data;
    },
    methods: {
        editCategorie(categorie) {
            console.log('editCategorie', categorie);
            this.selectedCategorie = categorie;
        },
        async deleteCategorie(categorie) {
            console.log('deleteCategorie', categorie);
            await axios.delete(`/categories/${categorie.id}`);
            this.categories = this.categories.filter(c => c.id !== categorie.id);
        },
        updateCategorie(updatedCategorie) {
            console.log('updateCategorie', updatedCategorie);
            const index = this.categories.findIndex(c => c.id === updatedCategorie.id);
            this.categories.splice(index, 1, updatedCategorie);
            this.selectedCategorie = null;
        }
    }
};
</script>


<template>
    <div>
        <h1>Categories</h1>
        <div v-for="categorie in categories" :key="categorie.id">
            <p>{{ categorie.name }}</p>
            <button @click="editCategorie(categorie)">Edit</button>
            <button @click="deleteCategorie(categorie)">Delete</button>
        </div>
        <CategorieDetail v-if="selectedCategorie" :categorie="selectedCategorie" @update="updateCategorie" />
    </div>
</template>