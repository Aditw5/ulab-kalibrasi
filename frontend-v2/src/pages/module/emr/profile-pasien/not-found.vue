<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
useHead({
  title: 'Not Found - ' + import.meta.env.VITE_PROJECT,
})
</script>

<template>
  <div class="error-container">
    <div class="error-wrapper">
      <div class="error-inner has-text-centered">
        <img
          class="light-image"
          src="/@src/assets/illustrations/placeholders/error-4.svg"
          alt=""
        />
        <img
          class="dark-image"
          src="/@src/assets/illustrations/placeholders/error-4-dark.svg"
          alt=""
        />
        <h3 class="dark-inverted">We couldn't find that page</h3>
        <p>
          Looks like we couldn't find that page. Please try again or contact an
          administrator if the problem persists.
        </p>
        <!-- <div class="button-wrap">
          <VButton color="primary" elevated rounded @click="$router.go(-1)">
            Take me Back
          </VButton>
        </div> -->
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.error-container {
  .error-wrapper {
    max-width: 840px;
    margin: 0 auto;
    padding-top: 40px;

    .error-inner {
      position: relative;
      max-width: 540px;
      margin: 0 auto;

      .bg-number {
        font-family: var(--font);
        position: absolute;
        top: -58px;
        left: -50px;
        right: 0;
        margin: 0 auto;
        font-size: 28rem;
        font-weight: 600;
        opacity: 0.15;
        z-index: 0;
      }

      img,
      svg,
      h3,
      p,
      .button-wrap {
        position: relative;
        z-index: 1;
      }

      img,
      svg {
        display: block;
        max-width: 100%;
        margin: 0 auto;
      }

      h3 {
        font-size: 1.5rem;
        font-family: var(--font-alt);
        color: var(--dark-text);
        font-weight: 600;
        margin-top: 10px;
      }

      p {
        font-family: var(--font);
        font-size: 1.1rem;
        margin-bottom: 16px;
      }

      .button-wrap {
        .button {
          min-width: 220px;
          min-height: 50px;
        }
      }
    }
  }
}

.is-dark {
  .error-container {
    .error-wrapper {
      .error-inner {
        .bg-number {
          opacity: 0.09;
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .error-container {
    .error-wrapper {
      padding-top: 60px;

      .error-inner {
        padding: 10px;

        .bg-number {
          top: -35px;
          left: -18px;
          right: 0;
          font-size: 13rem;
        }

        img,
        svg {
          max-width: 345px;
        }
      }
    }
  }
}
</style>
