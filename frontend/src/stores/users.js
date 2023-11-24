import axios from 'axios'
import { ref, computed, inject } from 'vue'
import { defineStore } from 'pinia'
import {useToast} from "vue-toastification"
import avatarNoneUrl from '@/assets/avatar-none.png'
export const useUserStore = defineStore('user', () => {
    const serverBaseUrl = inject('serverBaseUrl')

    const user = ref(null)
    const userName = computed(() => user.value?.name ?? 'Anonymous')
    const socket = inject("socket")
    const toast  = useToast()

    const userPhotoUrl = computed(() =>
        user.value?.photo_url
            ? serverBaseUrl + '/storage/fotos/' + user.value.photo_url
            : avatarNoneUrl)
    const userId = computed(() => user.value?.id ?? -1)
    async function loadUser() {
        try {
            const response = await axios.get('users/me')
            user.value = response.data.data
        } catch (error) {
            clearUser()
            throw error
        }
    }
    function clearUser() {
        delete axios.defaults.headers.common.Authorization
        user.value = null
    }
    
    async function login(credentials) {
        try {
            const response = await axios.post('login', credentials)
            axios.defaults.headers.common.Authorization = "Bearer " + response.data.access_token

            sessionStorage.setItem("token" , response.data.access_token)
            
            socket.emit('loggedIn', user.value)
            await loadUser()
            return true
        }
        catch (error) {
            clearUser()
            return false
        }
    }
    socket.on('loggedIn', function (user) {
        socket.join(user.id)
        if (user.type == 'A') {
            socket.join('administrator')
        }    
        console.log('User logged in:', user);
    })
    
    async function logout() {
        try {
            await axios.post('logout')
            clearUser()
            socket.emit('loggedOut', user.value)
            return true
        } catch (error) {
            return false
        }
    }
    
    return { user,userId, userName, userPhotoUrl, loadUser, clearUser, login, logout ,}
})