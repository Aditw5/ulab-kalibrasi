<script setup lang="ts">
import { defineComponent, onMounted, reactive, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'

import { useDarkmode } from '/@src/stores/darkmode'
import { useUserSession } from '/@src/stores/userSession'
import { useApi } from '/@src/composable/useApi'
import { useNotyf } from '/@src/composable/useNotyf'
import { useToaster } from '/@src/composable/toaster'
import { useToast } from 'primevue/usetoast'
import sleep from '/@src/utils/sleep'
import axios from 'axios'
import { boolean } from 'zod'
import { useStorage } from '@vueuse/core'
import Password from 'primevue/password'
import Dialog from 'primevue/dialog';
// import * as faceapi from "face-api.js";
export type UserData = Record<string, any> | null
type StepId = 'login' | 'forgot-password'
const step = ref<StepId>('login')
const isLoading = ref(false)
const darkmode = useDarkmode()
const router = useRouter()
const route = useRoute()
const notif = useToaster()
const api = useApi()
const toast = useToast()
let apiS
const redirect = route.query.redirect as string
const namaUser = ref('')
const kataSandi = ref('')
const port = window.location.port;
const isDev = port ? true : false
let error = ref('')
let isError = ref(false)
let referenceDescriptor = ref()
let videoRecog = ref(false)
let currentStep = ref(0)
const userSession = useUserSession()
const listMenu = useStorage('list_menu', [])
listMenu.value = []

// onMounted(() => {
// const script = document.createElement('script');
// script.src = 'https://www.google.com/recaptcha/api.js';
// script.async = true;
// script.defer = true;
// document.body.appendChild(script);
// })
useHead({
  title: 'Auth Login - ' + import.meta.env.VITE_PROJECT,
})
const handleLogin = () => {
  isError.value = false
  error.value = ''
  if (namaUser.value == '') {
    error.value = 'Username Required'
    isError.value = true
    return
  }
  if (kataSandi.value == '') {
    error.value = 'Password Required'
    isError.value = true
    return
  }
  // const captchaResponse = (window as any).grecaptcha.getResponse();
  // if (!captchaResponse) {
  //   error.value = 'Please complete the CAPTCHA';
  //   isError.value = true;
  //   return;
  // }
  if (!isLoading.value) {
    isLoading.value = true
    notif.dismissAll()
    apiS = axios.create({
      baseURL: import.meta.env.VITE_API_BASE_URL,
    })
    apiS
      .post('auth/login', {
        namaUser: namaUser.value,
        kataSandi: kataSandi.value,
        // captchaResponse,
      })
      .then(({ data }) => {
        isLoading.value = false

        if (data.metaData.code == 200) {

          let res = data.response
          toast.add({ severity: 'success', summary: 'Info', detail: `Welcome back, ${res.data.pegawai.namaLengkap}`, life: 3000, group: 'br' })
          // notif.success('Welcome back, ' + res.data.pegawai.namaLengkap)
          userSession.setUser(res.data)
          userSession.setToken(res.token)
          userSession.setUserData(res.data)


          if (redirect) {
            router.push(redirect)
          } else {
            router.push({
              name: res.data.kelompokUser.menu != null ? res.data.kelompokUser.menu : 'app',
            })
          }


        } else {
          notif.error(data.metaData.message)
        }
      })
      .catch((err) => {
        isLoading.value = false
        let message =
          typeof err.response !== 'undefined' ? err.response.data.metaData.message : err.message
        notif.error(message)
      })
  }
}
const changeUser = (e: any) => {
  if (e != '') {
    error.value = ''
    isError.value = false
  }
}

const video = ref(null);
const isRecognizing = ref(false);
const message = ref("Arahkan wajah ke kamera");

onMounted(async () => {
  // await loadModels();
  // startCamera();
});

const loadModels = async () => {
  await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
  await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
  await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
  await faceapi.nets.faceExpressionNet.loadFromUri('/models');
}


const startCamera = async () => {
  const stream = await navigator.mediaDevices.getUserMedia({ video: true });
  if (video.value) video.value.srcObject = stream;
};

const loadReferenceImage = async (imageSrc) => {
  const img = await faceapi.fetchImage("/sample.jpg");
  const detections = await faceapi
    .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
    .withFaceLandmarks()
    .withFaceDescriptor();

  return detections ? detections.descriptor : null;
};

const captureFace = async () => {
  videoRecog.value = true;
  await startCamera(); // Tunggu kamera aktif

  if (!video.value) {
    console.log("Video element tidak ditemukan!");
    return;
  }

  isRecognizing.value = true;
  console.log("Start camera.....");

  // Muat gambar referensi terlebih dahulu
  referenceDescriptor.value = await loadReferenceImage("sample.jpg");

  if (!referenceDescriptor.value) {
    console.error("Gagal memuat gambar referensi!");
    return;
  }

  video.value.removeEventListener("play", onPlayHandler);
  video.value.addEventListener("play", onPlayHandler);

  if (!video.value.paused && !video.value.ended) {
    onPlayHandler();
  }

  isRecognizing.value = false;
};

// Handler untuk menangani event 'play'
const onPlayHandler = () => {
  if (!video.value) return;

  const canvas = faceapi.createCanvasFromMedia(video.value);
  canvas.style.position = "absolute";
  canvas.style.left = `${video.value.offsetLeft}px`;
  canvas.style.top = `${video.value.offsetTop}px`;
  canvas.style.width = `${video.value.clientWidth}px`;
  canvas.style.height = `${video.value.clientHeight}px`;
  canvas.style.pointerEvents = "none"; // Agar klik tetap ke video
  video.value.parentElement.appendChild(canvas);

  const displaySize = { width: video.value.videoWidth, height: video.value.videoHeight };
  faceapi.matchDimensions(canvas, displaySize);

  const ctx = canvas.getContext("2d");
  ctx.font = "30px Arial";

  const intervalId = setInterval(async () => {
    if (!video.value) {
      clearInterval(intervalId);
      return;
    }

    const detections = await faceapi
      .detectAllFaces(video.value, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceExpressions()
      .withFaceDescriptors();

    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    faceapi.draw.drawDetections(canvas, resizedDetections);
    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);
    faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

    if (detections.length > 0) {
      const bestMatch = new faceapi.FaceMatcher([referenceDescriptor.value]).findBestMatch(
        detections[0].descriptor
      );

      if (bestMatch.distance < 0.4) {
        ctx.fillStyle = "#00ff00";
        ctx.fillText("Wajah cocok! Lanjutkan proses...", 20, 50);

        const { expressions, landmarks } = detections[0];
        const isHappy = expressions.happy > 0.7;
        const leftEye = landmarks.getLeftEye();
        const rightEye = landmarks.getRightEye();
        const nose = landmarks.getNose();

        const isLookingRight = nose[0].x > 300;
        const isLookingLeft = nose[0].x < 200;

        if (currentStep.value === 0) {
          ctx.fillStyle = "#00ff00";
          ctx.fillText("Senyum untuk lanjut...", 20, 80);
          if (isHappy) {
            currentStep.value = 1;
          }
        } else if (currentStep.value === 1) {
          ctx.fillStyle = "#0000ff";
          ctx.fillText("Geser kanan/kiri...", 20, 110);
          if (isLookingRight || isLookingLeft) {
            currentStep.value = 2;
          }
        } else if (currentStep.value === 2) {
          ctx.fillStyle = "#ff00ff";
          ctx.fillText("Verifikasi selesai!", 20, 140);
          if (video.value && video.value.srcObject) {
            const tracks = video.value.srcObject.getTracks();
            tracks.forEach(track => track.stop()); // Stop semua track media
            video.value.srcObject = null; // Hapus sumber video
          }
          videoRecog.value = false

          // Hentikan loop deteksi wajah
          clearInterval(intervalId);
        }
      } else {
        ctx.fillStyle = "#ff0000";
        ctx.fillText("Wajah tidak cocok!", 20, 50);
      }
    } else {
      ctx.fillStyle = "#ff0000";
      ctx.fillText("Wajah tidak terdeteksi!", 20, 50);
    }
  }, 100);
};

</script>

<template>
  <div class="modern-login">
    <div class="underlay h-hidden-mobile h-hidden-tablet-p"></div>

    <div class="columns is-gapless is-vcentered">
      <div class="column is-relative is-8 h-hidden-mobile h-hidden-tablet-p">
        <div class="hero is-fullheight is-image">
          <div class="hero-body">
            <div class="container">
              <div class="columns">
                <div class="column">
                  <img class="hero-image" src="/@src/assets/illustrations/login/pengajuan.png" alt="" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-4 is-relative">
        <RouterLink :to="{ name: 'index' }" class="top-logo">
          <!-- <AnimatedLogoJMT width="60px" height="60px" /> -->
          <LogoRS />
          <!-- <AnimatedLogo width="38px" height="38px" /> -->
        </RouterLink>

        <label class="dark-mode ml-auto" tabindex="0"
          @keydown.space.prevent="(e) => (e.target as HTMLLabelElement).click()">
          <input type="checkbox" :checked="!darkmode.isDark" @change="darkmode.onChange" />
          <span></span>
        </label>
        <div class="is-form">
          <div class="hero-body">
            <div class="form-text" :class="[step !== 'login' && 'is-hidden']">
              <h2>U-LAB</h2>
              <p>Login to your Account</p>
            </div>
            <div class="form-text" :class="[step === 'login' && 'is-hidden']">
              <h2>Recover Account</h2>
              <p>Reset your account password.</p>
            </div>
            <form :class="[step !== 'login' && 'is-hidden']" class="login-wrapper" @submit.prevent="handleLogin">
              <VMessage color="danger" v-show="isError">{{ error }}</VMessage>
              <VField>
                <VControl icon="lnil lnil-envelope autv-icon">
                  <VLabel class="auth-label">Username</VLabel>
                  <VInput type="text" autocomplete="current-password" v-model="namaUser"
                    @input="changeUser($event.target.value)" />
                </VControl>
              </VField>
              <VField>
                <VControl icon="lnil lnil-lock-alt autv-icon">
                  <VLabel class="auth-label">Password</VLabel>
                  <Password v-model="kataSandi" toggleMask placeholder="" class="is-rounded w-100 is-login-pass"
                    inputStyle=" padding-top: 14px;
                      height: 60px;
                      border-radius: 10px;
                      padding-left: 55px;
                      transition: all 0.3s;" @input="changeUser($event.target.value)" />
                </VControl>
              </VField>
              <!-- <VField>
                <VControl icon="lnil lnil-lock-alt autv-icon">
                  <VLabel class="auth-label">Password</VLabel>
                  <VInput type="password" autocomplete="current-password" v-model="kataSandi"
                    @input="changeUser($event.target.value)" />
                </VControl>
              </VField> -->
              <!-- <div class="g-recaptcha" data-sitekey="6LdwosIZAAAAALF5LATNjFt7raOiBmI_37rB0bVe"></div> -->
              <VField>
                <VControl class="is-flex">
                  <VLabel raw class="remember-toggle">
                    <VInput raw type="checkbox" />
                    <span class="toggler">
                      <span class="active">
                        <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
                      </span>
                      <span class="inactive">
                        <i aria-hidden="true" class="iconify" data-icon="feather:circle"></i>
                      </span>
                    </span>
                  </VLabel>
                  <VLabel raw class="remember-me">Remember Me</VLabel>
                  <a tabindex="0" @keydown.space.prevent="step = 'forgot-password'" @click="step = 'forgot-password'">
                    Forgot Password?
                  </a>
                </VControl>
              </VField>

              <div class="button-wrap has-help">
                <VButton id="login-button" icon="feather:arrow-right" :loading="isLoading" color="primary" type="submit"
                  size="big" rounded raised bold>
                  Login
                </VButton>
                <span>
                  Or
                  <RouterLink :to="{ name: 'auth-signup-1' }">Create</RouterLink>
                  an account.
                </span>
              </div>
            </form>

            <form :class="[step !== 'forgot-password' && 'is-hidden']" class="login-wrapper" @submit.prevent>
              <p class="recover-text">
                Enter your email and click on the confirm button to reset your password.
                We'll send you an email detailing the steps to complete the procedure.
              </p>

              <VField>
                <VControl icon="lnil lnil-envelope autv-icon">
                  <VLabel class="auth-label">Email Address</VLabel>
                  <VInput type="email" autocomplete="current-password" />
                </VControl>
              </VField>
              <div class="button-wrap">
                <VButton color="white" size="big" lower rounded @click="step = 'login'">
                  Cancel
                </VButton>
                <VButton color="primary" size="big" type="submit" lower rounded solid @click="step = 'login'">
                  Confirm
                </VButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <Dialog v-model:visible="videoRecog" modal header="Video Recog" :style="{ width: '60vw' }">
      <video ref="video" autoplay playsinline></video>
      <p>{{ message }}</p>
    </Dialog>
  </div>
</template>

<style lang="scss" scoped>
.container {
  text-align: center;
}

video {
  width: 100%;
  // max-width: 400px;
  border-radius: 10px;
  margin-bottom: 10px;
}

// canvas {
//       position: absolute;
//     }
button {
  padding: 10px 15px;
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

.modern-login {
  position: relative;
  background: var(--white);
  min-height: 100vh;

  .column {
    &.is-relative {
      position: relative;
    }
  }

  .hero {
    &.has-background-image {
      position: relative;

      .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #5d4298 !important;
        opacity: 0.6;
      }
    }
  }

  .underlay {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 66.6%;
    height: 100%;
    background: #fdfdfd;
    z-index: 0;
  }

  .dark-mode {
    position: absolute;
    top: -64px;
    right: 38px;
    transform: scale(0.6);
    z-index: 2;
  }

  .top-logo {
    position: absolute;
    top: -70px;
    left: 0;
    right: 0;
    margin: 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;

    img {
      display: block;
      width: 100%;
      max-width: 50px;
      margin: 0 auto;
    }

    svg {
      height: 50px;
      width: 50px;
    }
  }

  .is-image {
    position: relative;
    border-right: 1px solid var(--fade-grey);

    .hero-image {
      position: relative;
      z-index: 2;
      display: block;
      margin: -80px auto 0;
      max-width: 60%;
      width: 60%;
    }
  }

  .is-form {
    position: relative;
    max-width: 420px;
    margin: 0 auto;

    form {
      animation: fadeInLeft 0.5s;
    }

    .form-text {
      padding: 0 20px;
      animation: fadeInLeft 0.5s;

      h2 {
        font-family: var(--font-alt);
        font-weight: 400;
        font-size: 2rem;
        color: var(--primary);
      }

      p {
        color: var(--muted-grey);
        margin-top: 10px;
      }
    }

    .recover-text {
      font-size: 0.9rem;
      color: var(--dark-text);
    }

    .login-wrapper {
      padding: 30px 20px;

      .control {
        position: relative;
        width: 100%;
        margin-top: 16px;

        .input {
          padding-top: 14px;
          height: 60px;
          border-radius: 10px;
          padding-left: 55px;
          transition: all 0.3s; // transition-all test

          &:focus {
            background: var(--fade-grey-light-6);
            border-color: var(--placeholder);

            ~.auth-label,
            ~.autv-icon i {
              color: var(--muted-grey);
            }
          }
        }

        .error-text {
          color: var(--danger);
          font-size: 0.8rem;
          display: none;
          padding: 2px 6px;
        }

        .auth-label {
          position: absolute;
          top: 6px;
          left: 55px;
          font-size: 0.8rem;
          color: var(--dark-text);
          font-weight: 500;
          z-index: 2;
          transition: all 0.3s; // transition-all test
        }

        .autv-icon,
        :deep(.autv-icon) {
          position: absolute;
          top: 0;
          left: 0;
          height: 60px;
          width: 60px;
          display: flex;
          justify-content: center;
          align-items: center;
          font-size: 24px;
          color: var(--placeholder);
          transition: all 0.3s;
        }

        &.has-validation {
          .validation-icon {
            position: absolute;
            top: 0;
            right: 0;
            height: 60px;
            width: 60px;
            display: none;
            justify-content: center;
            align-items: center;

            .icon-wrapper {
              height: 20px;
              width: 20px;
              display: flex;
              justify-content: center;
              align-items: center;
              border-radius: var(--radius-rounded);

              svg {
                height: 10px;
                width: 10px;
                stroke-width: 3px;
                color: var(--white);
              }
            }

            &.is-success {
              .icon-wrapper {
                background: var(--success);
              }
            }

            &.is-error {
              .icon-wrapper {
                background: var(--danger);
              }
            }
          }

          &.has-success {
            .validation-icon {
              &.is-success {
                display: flex;
              }

              &.is-error {
                display: none;
              }
            }
          }

          &.has-error {
            .input {
              border-color: var(--danger);
            }

            .error-text {
              display: block;
            }

            .validation-icon {
              &.is-error {
                display: flex;
              }

              &.is-success {
                display: none;
              }
            }
          }
        }

        &.is-flex {
          display: flex;
          align-items: center;

          a {
            display: block;
            margin-left: auto;
            color: var(--muted-grey);
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.3s;

            &:hover,
            &:focus {
              color: var(--primary);
            }
          }

          .remember-me {
            font-size: 0.9rem;
            color: var(--muted-grey);
            font-weight: 500;
          }
        }
      }

      .button-wrap {
        margin: 40px 0;

        &.has-help {
          display: flex;
          align-items: center;

          >span {
            margin-left: 12px;
            font-family: var(--font);

            a {
              color: var(--primary);
              font-weight: 500;
              padding: 0 2px;
            }
          }
        }

        .button {
          height: 46px;
          width: 140px;
          margin-left: 6px;

          &:first-child {
            &:hover {
              opacity: 0.8;
            }
          }
        }
      }
    }
  }
}

.remember-toggle {
  width: 65px;
  display: block;
  position: relative;
  cursor: pointer;
  font-size: 22px;
  user-select: none;
  transform: scale(0.9);

  input {
    position: absolute;
    opacity: 0;
    cursor: pointer;

    &:checked~.toggler {
      border-color: var(--primary);

      .active,
      .inactive {
        transform: translateX(100%) rotate(360deg);
      }

      .active {
        opacity: 1;
      }

      .inactive {
        opacity: 0;
      }
    }
  }

  .toggler {
    position: relative;
    display: block;
    height: 34px;
    width: 61px;
    border: 2px solid var(--placeholder);
    border-radius: 100px;
    transition: all 0.3s; // transition-all test

    .active,
    .inactive {
      position: absolute;
      top: 2px;
      left: 2px;
      height: 26px;
      width: 26px;
      border-radius: var(--radius-rounded);
      background: black;
      display: flex;
      justify-content: center;
      align-items: center;
      transform: translateX(0) rotate(0);
      transition: all 0.3s ease;

      svg {
        color: var(--white);
        height: 14px;
        width: 14px;
        stroke-width: 3px;
      }
    }

    .inactive {
      background: var(--placeholder);
      border-color: var(--placeholder);
      opacity: 1;
      z-index: 1;
    }

    .active {
      background: var(--primary);
      border-color: var(--primary);
      opacity: 0;
      z-index: 0;
    }
  }
}

@media only screen and (max-width: 767px) {
  .modern-login {
    .top-logo {
      top: 30px;
    }

    .dark-mode {
      top: 36px;
      right: 44px;
    }

    .is-form {
      padding-top: 100px;
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .modern-login {
    .top-logo {
      svg {
        height: 60px;
        width: 60px;
      }
    }

    .dark-mode {
      top: -58px;
      right: 30%;
    }

    .columns {
      display: flex;
      height: 100vh;
    }
  }
}

/* ==========================================================================
Dark mode
========================================================================== */

.is-dark {
  .modern-login {
    background: var(--dark-sidebar);

    .underlay {
      background: var(--dark-sidebar-light-10);
    }

    .is-image {
      border-color: var(--dark-sidebar-light-10);
    }

    .is-form {
      .form-text {
        h2 {
          color: var(--primary);
        }
      }

      .login-wrapper {
        .control {
          &.is-flex {
            a:hover {
              color: var(--primary);
            }
          }

          .input {
            background: var(--dark-sidebar-light-4);

            &:focus {
              border-color: var(--primary);

              ~.autv-icon {
                i {
                  color: var(--primary);
                }
              }
            }
          }

          .auth-label {
            color: var(--light-text);
          }
        }

        .button-wrap {
          &.has-help {
            span {
              color: var(--light-text);

              a {
                color: var(--primary);
              }
            }
          }
        }
      }
    }
  }

  .remember-toggle {
    input {
      &:checked+.toggler {
        border-color: var(--primary);

        >span {
          background: var(--primary);
        }
      }
    }

    .toggler {
      border-color: var(--dark-sidebar-light-12);

      >span {
        background: var(--dark-sidebar-light-12);
      }
    }
  }
}
</style>
