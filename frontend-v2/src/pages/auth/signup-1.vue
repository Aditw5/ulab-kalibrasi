<script setup lang="ts">
import { useRoute } from 'vue-router'
import type { TinySliderInstance } from 'tiny-slider/src/tiny-slider'
import { tns } from 'tiny-slider/src/tiny-slider'
import { useHead } from '@vueuse/head'
import { ref, onMounted, onUnmounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import sleep from '/@src/utils/sleep'
import { useNotyf } from '/@src/composable/useNotyf'
import Password from 'primevue/password'
import axios from 'axios'

const sliderElement = ref<HTMLElement>()
const router = useRouter()
const notif = useNotyf()
const step = ref(0)
const isLoading = ref(false)

const loginuser = reactive({
  name: '',
  nowa: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const isError = ref(false)
const errorMsg = ref('')

useHead({
  title: 'Auth Signup - ' + import.meta.env.VITE_PROJECT,
})

const handleSignup = async () => {
  isError.value = false
  errorMsg.value = ''
  if (
    !loginuser.name ||
    !loginuser.nowa ||
    !loginuser.email ||
    !loginuser.password ||
    !loginuser.password_confirmation
  ) {
    errorMsg.value = 'Semua field wajib diisi.'
    isError.value = true
    return
  }
  if (loginuser.password !== loginuser.password_confirmation) {
    errorMsg.value = 'Password dan Confirm Password tidak cocok.'
    isError.value = true
    return
  }
  isLoading.value = true
  try {
    const { data } = await axios.post(
      `${import.meta.env.VITE_API_BASE_URL}auth/register`,
      { loginuser }
    )
    if (data.status === 200) {
      notif.success('Registrasi berhasil! Cek email untuk verifikasi.')
      router.push({ name: 'auth-login' })
    } else {
      errorMsg.value = data.result || data.metaData?.message || 'Registrasi gagal.'
      isError.value = true
    }

  } catch (err: any) {
    let message = ''
    if (err.response?.data) {
      message =
        err.response.data.response?.result ||
        err.response.data.metaData?.message ||
        err.response.data.message ||
        err.message
    } else {
      message = err.message
    }
    errorMsg.value = message
    isError.value = true

  } finally {
    isLoading.value = false
  }
}

const route = useRoute()
const isVerified = ref(false)

useHead({
  title: 'Auth Signup - ' + import.meta.env.VITE_PROJECT,
})

onMounted(async () => {
  const verified = route.query.verified
  const id = route.query.id
  const hash = route.query.hash
  const expires = route.query.expires
  const signature = route.query.signature

  if (verified === '1' && id && hash && signature && expires) {
    try {
      await axios.get(`${import.meta.env.VITE_API_BASE_URL}auth/verify-email/${id}/${hash}`, {
        params: { signature, expires },
      })

      isVerified.value = true
    } catch (error) {
      notif.error('Verifikasi gagal atau tautan sudah kadaluarsa.')
      isVerified.value = false
    }
  }

  if (sliderElement.value) {
    slider = tns({
      container: sliderElement.value,
      controls: false,
      nav: false,
      mouseDrag: true,
      startIndex: 2,
      fixedWidth: 100,
      gutter: 40,
      slideBy: 1,
      swipeAngle: false,
      center: false,
      loop: true,
      edgePadding: 325,
    })
  }
})

onUnmounted(() => {
})
</script>
<template>
  <div>
    <div class="signup-nav">
      <div class="signup-nav-inner">
        <RouterLink :to="{ name: 'index' }" class="logo">
          <AnimatedLogoJMT width="70px" height="70px" />
        </RouterLink>
      </div>
    </div>
    <div v-if="isVerified" class="verify-success-page">
      <div class="box has-text-centered">
        <h1 class="title is-4">✅ Email Berhasil Diverifikasi!</h1>
        <p class="subtitle mb-4">
          Terima kasih, email Anda sudah terverifikasi.
          Silakan klik tombol di bawah untuk login.
        </p>
        <VButton color="primary" @click="router.push({ name: 'auth-login' })">
          Login Sekarang
        </VButton>
      </div>
    </div>
    <div v-if="!isVerified" id="vuero-signup" class="signup-wrapper">
      <img class="card-bg h-hidden-mobile" src="/@src/assets/backgrounds/signup/ulab-signup.png" alt="" />
      <div class="hero is-fullheight">
        <div class="hero-body">
          <div class="container">
            <div class="columns signup-columns" :class="[step !== 0 && 'is-hidden']">
              <div class="column is-4 is-offset-1">
                <h1 id="main-signup-title" class="title is-3 signup-title">
                  Become a ULABers
                </h1>
                <div class="signup-card">
                  <form class="signup-form is-mobile-spaced" @submit.prevent="handleSignup">
                    <VMessage color="danger" v-show="isError">{{ errorMsg }}</VMessage>
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VInput type="text" autocomplete="given-name" v-model="loginuser.name" />
                            <VLabel raw class="auth-label">Nama Lengkap</VLabel>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl>
                            <VInput type="text" autocomplete="tel" v-model="loginuser.nowa" />
                            <VLabel raw class="auth-label">No Whatsapp</VLabel>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VInput type="email" autocomplete="email" v-model="loginuser.email" />
                            <VLabel raw class="auth-label">Email</VLabel>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl icon="lnil lnil-lock-alt autv-icon">
                            <Password inputStyle=" padding-top: 14px;
                              height: 60px;
                              border-radius: 10px;
                              padding-left: 55px;
                              transition: all 0.3s;" v-model="loginuser.password" toggleMask placeholder=""
                              class="is-rounded w-100 is-login-pass" />
                            <VLabel raw class="auth-label">Password</VLabel>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl icon="lnil lnil-lock-alt autv-icon">
                            <Password inputStyle=" padding-top: 14px;
                              height: 60px;
                              border-radius: 10px;
                              padding-left: 55px;
                              transition: all 0.3s;" v-model="loginuser.password_confirmation" toggleMask
                              placeholder="" class="is-rounded w-100 is-login-pass" />
                            <VLabel raw class="auth-label">Confirm Password</VLabel>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="control is-agree">
                      <span>
                        By signup you agree to our <a href="#">Terms</a> and
                        <a href="#">Privacy</a>
                      </span>
                    </div>
                    <div class="button-wrap has-help">
                      <VButton type="submit" color="primary" size="big" bold fullwidth rounded :loading="isLoading">
                        Signup
                      </VButton>
                      <span>
                        Or
                        <RouterLink :to="{ name: 'auth-login' }"> Sign In </RouterLink>
                        to your account.
                      </span>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">

.verify-success-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 80vh;
}
.box.has-text-centered {
  max-width: 400px;
  padding: 2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.signup-nav {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 50px;
  z-index: 99;

  .signup-nav-inner {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;

    .logo {
      img {
        display: block;
        min-width: 68px;
        max-width: 48px;
      }
    }
  }
}

.signup-steps {
  position: absolute;
  top: 80px;
  left: 0;
  right: 0;
  margin: 0 auto;
  max-width: 380px;

  .steps-container {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;

    .progress {
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      transform: translateY(-50%);
      width: calc(100% - 80px);
      margin: 0 auto;
      height: 0.35rem !important;
      background-color: var(--white);
      z-index: 0;

      &::-webkit-progress-value {
        background-color: var(--primary);
        transition: width 0.5s ease;
      }

      &::-moz-progress-bar {
        background-color: var(--primary);
        transition: width 0.5s ease;
      }

      &::-ms-fill {
        background-color: var(--primary);
        transition: width 0.5s ease;
      }
    }

    .step-icon {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 46px;
      width: 46px;
      border-radius: var(--radius-rounded);
      background: var(--fade-grey);
      cursor: pointer;
      z-index: 1;

      &.is-active {
        .inner {
          background: var(--white);
          border-color: var(--primary);

          svg {
            color: var(--primary);
          }
        }
      }

      &.is-done {
        .inner {
          background: var(--primary);
          border-color: var(--primary);

          svg {
            color: var(--smoke-white);
          }
        }
      }

      &.is-inactive {
        pointer-events: none;
      }

      .inner {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 40px;
        width: 40px;
        border-radius: var(--radius-rounded);
        border: 2px solid var(--primary-grey);
        background: var(--primary-grey);
      }

      .step-label {
        position: absolute;
        top: 45px;
        left: 0;
        right: 0;
        margin: 0 auto;
        text-align: center;
        min-width: 100px;
        transform: translateX(-25%);
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--dark-text);
      }

      svg {
        height: 16px;
        width: 16px;
        color: var(--muted-grey);
      }
    }
  }
}

.signup-columns {
  animation: fadeInLeft 0.5s;

  .column.is-8 {
    margin: 0 auto;
  }
}

.signup-wrapper {
  position: relative;
  min-height: 100vh;
  background: var(--fade-grey);

  .card-bg {
    position: absolute;
    right: 0;
    bottom: 0;
    display: block;
    width: 100%;
    transition: all 0.3s; // transition-all test

    &.faded {
      opacity: 0;
    }
  }

  .signup-title {
    font-family: var(--font-alt);
    color: var(--dark-text);
  }

  .signup-subtitle {
    font-family: var(--font);
    color: var(--muted-grey);
    font-size: 1rem;
  }

  .hero {
    .signup-form {
      .control {
        position: relative;
        width: 100%;

        &.has-switch {
          display: flex;
          align-items: center;

          span {
            display: block;
            color: var(--muted-grey);
          }

          >div {
            margin-left: auto;
            transform: scale(0.8);
          }
        }

        &.is-agree {
          span {
            color: var(--placeholder-dark-8);

            a {
              color: var(--muted-grey);
              font-weight: 500;
              transition: color 0.3s;

              &:hover,
              &:focus {
                color: var(--primary);
              }
            }
          }
        }

        .input {
          padding-top: 10px;
          height: 60px;
          padding-left: 10px;
          border-radius: 8px;
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
          left: 10px;
          font-size: 0.8rem;
          color: var(--dark-text);
          font-weight: 500;
          z-index: 2;
          transition: all 0.3s; // transition-all test
        }

        .autv-icon {
          position: absolute;
          top: 0;
          left: 0;
          height: 60px;
          width: 60px;
          display: flex;
          justify-content: center;
          align-items: center;

          i {
            font-size: 24px;
            color: var(--placeholder);
            transition: all 0.3s; // transition-all test
          }
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
                color: var(--white) !important;
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
    }

    .button-wrap {
      margin: 20px 0 0;

      &.has-help {
        display: flex;
        align-items: center;

        >span {
          margin-left: 12px;
          font-family: var(--font);

          a {
            color: var(--primary);
            font-weight: 500;
            padding: 0 3px;
          }
        }
      }

      &.is-centered {
        margin-top: 40px;
        text-align: center;

        .button {
          min-width: 180px;
          margin-left: 0 !important;
        }
      }

      .button {
        height: 46px;
        width: 190px;
        margin-left: 6px;

        &:first-child {
          &:hover {
            opacity: 0.95;
            box-shadow: var(--primary-box-shadow);
          }
        }

        &:nth-child(2) {
          color: var(--dark-text);
          border-color: var(--placeholder);
        }
      }
    }

    .signup-type {
      display: flex;
      align-items: center;

      // margin-top: 16px;

      .box-wrap {
        width: 100%;
        position: relative;

        input {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          opacity: 0;
          cursor: pointer;

          &:checked+.signup-box {
            border-color: var(--primary);

            i {
              color: var(--primary);
            }

            .meta {
              span:first-child {
                color: var(--primary);
              }
            }
          }
        }

        .signup-box {
          display: flex;
          align-items: center;
          padding: 12px;
          background: var(--white);
          border: 1px solid var(--fade-grey-dark-3);
          border-radius: var(--radius-large);
          transition: all 0.3s; // transition-all test

          i {
            font-size: 2rem;
            color: var(--muted-grey);
          }

          .meta {
            margin-left: 10px;

            span {
              display: block;

              &:first-child {
                font-size: 0.85rem;
                font-weight: 500;
                color: var(--dark-text);
              }

              &:nth-child(2) {
                font-size: 0.8rem;
                color: var(--muted-grey);
              }
            }
          }
        }

        &:first-child {
          margin-right: 6px;
        }

        &:nth-child(2) {
          margin-left: 6px;
        }
      }
    }
  }
}

.signup-profile-wrapper {
  padding: 80px 60px 10px;

  .title,
  .subtitle {
    text-align: center;
  }

  .title {
    font-family: var(--font-alt);
    font-size: 1.4rem;
  }

  .subtitle {
    font-family: var(--font);
    font-size: 1rem;
  }

  .picture-selector,
  .skill-picture-selector {
    width: 100%;
    text-align: center;

    .image-container {
      position: relative;
      width: 140px;
      height: 140px;
      margin: 10px auto;
      border-radius: var(--radius-rounded);

      img {
        width: 140px;
        height: 140px;
        border-radius: var(--radius-rounded);
        display: block;
        border: 4px solid #e8e8e8;
        margin-left: -1px;
      }

      .upload-button {
        position: absolute;
        bottom: 18px;
        right: 0;
        width: 36px;
        height: 36px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--white);
        border-radius: var(--radius-rounded);
        border: 1px solid var(--fade-grey-dark-4);
        z-index: 5;
        transition: all 0.3s; // transition-all test
        cursor: pointer;

        &:hover,
        &:focus {
          box-shadow: var(--light-box-shadow);
        }

        svg {
          height: 16px;
          width: 16px;
          color: var(--dark-text);
        }
      }
    }
  }
}

.avatar-carousel {
  text-align: center;

  // max-width: 550px;
  // margin: 0 auto 20px auto;

  &:hover,
  &:focus .slick-custom {
    opacity: 1;
  }

  .carousel-item {
    margin: 0 10px;
  }

  .image-wrapper {
    position: relative;

    &.is-smaller img {
      height: 70px;
      width: 70px;
    }

    img {
      height: 70px;
      width: 70px;
      border-radius: var(--radius-rounded);
      margin: 0 auto;
      transition: all 0.3s; // transition-all test
    }
  }

  .tns-item {
    max-width: 120px;
    text-align: center;
    cursor: pointer;

    img {
      opacity: 0.6;
      border: 4px solid transparent;
      transform: scale(0.75);
    }

    &.active {
      img {
        opacity: 1;
        transform: scale(1);
        border: 2px solid var(--primary);
      }
    }
  }

  .slick-dots {
    bottom: -60px !important;
  }

  .slick-prev::before,
  .slick-next::before {
    color: var(--muted-grey);
  }

  .slick-custom {
    position: absolute;
    top: calc(50% - 15px);
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid var(--fade-grey);
    width: 30px;
    height: 30px;
    background: var(--white);
    border-radius: 100px;
    cursor: pointer;
    color: var(--dark-text);
    transition: all 0.3s; // transition-all test
    z-index: 25;
    opacity: 0;

    svg {
      height: 16px;
      width: 16px;
      color: var(--primary);
      transition: all 0.3s; // transition-all test
    }

    &:hover {
      border-color: var(--fade-grey-dark-4);
      color: var(--white);
      box-shadow: var(--light-box-shadow);
    }

    &.is-prev {
      left: -6px;
    }

    &.is-next {
      right: -6px;
    }
  }
}

.resize-handler {
  max-width: 200px;
  margin: 7px auto 10px;
}

.username-form {
  padding-top: 80px;
}

.is-dark {
  .signup-wrapper {
    background: var(--dark-sidebar-light-10);
  }

  .signup-steps {
    .steps-container {
      .progress {
        &::-webkit-progress-value {
          background: var(--primary);
        }

        &::-moz-progress-bar {
          background: var(--primary);
        }

        &::-ms-fill {
          background: var(--primary);
        }
      }

      .step-icon {
        background: var(--dark-sidebar-light-7);

        &.is-active {
          background: var(--dark-sidebar-light-16);

          .inner {
            background: var(--primary);

            svg {
              color: var(--white);
              stroke: var(--white);
            }
          }

          .step-label {
            color: var(--primary);
            opacity: 1;
          }
        }

        .inner {
          background: var(--dark-sidebar-light-9);
          border-color: var(--dark-sidebar-light-9);
        }

        .step-label {
          color: var(--dark-dark-text);
          opacity: 0.6;
        }
      }
    }
  }

  .hero {
    .signup-form {
      .control {
        .auth-label {
          color: var(--light-text);
        }

        .input {
          &:focus {
            background: var(--dark-sidebar-dark-4);
            border-color: var(--dark-sidebar-light-12);

            ~.auth-label,
            ~.auth-icon i {
              color: var(--primary);
            }
          }
        }
      }

      .signup-type {
        .box-wrap {
          input {
            &:checked+.signup-box {
              border-color: var(--primary);

              i {
                color: var(--primary);
              }

              .meta {
                span:first-child {
                  color: var(--primary);
                }
              }
            }
          }

          .signup-box {
            background-color: var(--dark-sidebar-light-2);
            border-color: var(--dark-sidebar-light-4);

            .meta {
              span:first-child {
                color: var(--dark-dark-text);
              }
            }
          }
        }
      }

      .button-wrap {
        &.has-help {
          >span {
            color: var(--light-text);

            a {
              color: var(--primary);
            }
          }
        }
      }
    }
  }

  .signup-profile-wrapper {
    .picture-selector {
      .image-container {
        img {
          border-color: var(--dark-sidebar-light-10);
        }

        .upload-button {
          background-color: var(--dark-sidebar-light-2);
          border-color: var(--dark-sidebar-light-10);

          svg {
            color: var(--light-text);
            stroke: var(--light-text);
          }
        }
      }
    }
  }

  .divider-container {
    .divider {
      span {

        &::before,
        &::after {
          border-color: var(--dark-sidebar-light-18);
        }
      }
    }
  }

  .avatar-carousel {
    .slick-slide {
      &.slick-current {
        img {
          border-color: var(--primary);
        }
      }
    }

    .slick-custom {
      background-color: var(--dark-sidebar-light-2);
      border-color: var(--dark-sidebar-light-10);

      &::before,
      &::after {
        color: var(--light-text);
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .steps-container {
    padding: 0 1rem;
  }

  .signup-wrapper {
    .card-bg {
      bottom: -75px;
    }

    .columns {
      padding: 0;
      text-align: center;
    }

    .signup-columns {
      max-width: 100vw;
    }

    .signup-subtitle {
      max-width: 330px;
      margin-left: auto;
      margin-right: auto;
    }

    .avatar-carousel .carousel-item {
      margin: 0;
    }

    .button-wrap {
      &.has-help {
        justify-content: center;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .signup-wrapper {
    .card-bg {
      bottom: -75px;
    }

    .columns {
      padding: 0 80px;
      text-align: center;
    }

    .signup-columns {
      max-width: 100vw;
    }

    .signup-subtitle {
      max-width: 330px;
      margin-left: auto;
      margin-right: auto;
    }

    .button-wrap {
      &.has-help {
        justify-content: center;
      }
    }
  }
}
</style>
