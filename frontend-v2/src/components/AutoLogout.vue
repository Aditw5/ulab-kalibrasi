<template>
    <div>
        <Dialog v-model:visible="isOpen" header="Sesi Login Berakhir" :style="{ width: '30rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" position="center" :modal="true" :draggable="false" :closable="false">
            <div class="flex" style="align-items: center;">
                <i class="iconify" data-icon="feather:alert-circle" aria-hidden="true" style="color: green;font-size: 4rem;margin-right: 10px;"></i>
                <p class="m-0">
                    {{ message }}
                </p>
            </div>
        </Dialog>
    </div>
</template>
  
<script setup lang="ts">
import { useUserSession } from '/@src/stores/userSession'
import { ref, computed, watch, reactive, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useStorage } from '@vueuse/core'
import Dialog from 'primevue/dialog';

const router = useRouter()
const isOpen = ref(false)
const message = ref('')
const inactivityTimeout = 30 * 60 * 1000; // 30 menit dalam milidetik
let timer;
const resetTimer = () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
        // Panggil fungsi logout atau tindakan lainnya
        // Gantilah dengan logika logout sesuai kebutuhan Anda
        showpopUpLogout()
    }, inactivityTimeout);
};

const showpopUpLogout = () => {
    document.removeEventListener('mousemove', function() {
        // Reset countdown saat mendeteksi gerakan mouse
        resetTimer()
    });
    isOpen.value = true
    let countdown = 5;
    message.value = `Anda akan Logout otomatis dalam ${countdown} detik...`;
    const countdownInterval = setInterval(() => {
        if (countdown > 0) {
            // Tampilkan countdown atau pesan sesuai kebutuhan
            countdown--;
            message.value = `Anda akan Logout otomatis dalam ${countdown} detik...`;
        } else {
            // Logout otomatis
            clearInterval(countdownInterval);
            // Gantilah dengan logika logout sesuai kebutuhan Anda
            logout();
        }
    }, 1000); // Perbarui setiap 1 detik
}

resetTimer();

function logout() {
    isOpen.value = false
    useStorage('user_session', '')
    useUserSession().logoutUser()
    router.push({
        name: 'auth-login',
    })
}
onMounted(() => {
    document.addEventListener('mousemove', function() {
        // Reset countdown saat mendeteksi gerakan mouse
        resetTimer()
    });
})
</script>