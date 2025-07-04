<script setup lang="ts">
import { useStorage } from '@vueuse/core'
import { ref } from 'vue'

const openSubsidebarLinks = ref('')
const emit = defineEmits(['close'])
const listMenu: any = useStorage('list_menu', [])
const filter = ref('')

let route = flatDataArray(listMenu.value)

function flatDataArray(array: any[]) {
  let dataArray: any = []
  array.forEach((node, _index, _object) => {
    if (node.children) {
      dataArray = dataArray.concat(flatDataArray(node.children))
    } else {
      dataArray.push({
        label: node.name,
        routerLink: node.link,
      })
    }
  })
  return dataArray
}

</script>

<template>
  <div class="sidebar-panel is-generic">
    <div class="subpanel-header">
      <ProjectsQuickDropdown />

      <h3 class="no-mb">ULAB</h3>
      <div
        class="panel-close"
        tabindex="0"
        @keydown.space.prevent="emit('close')"
        @click="emit('close')"
      >
        <i aria-hidden="true" class="iconify" data-icon="feather:x"></i>
      </div>
    </div>
    <div class="inner" data-simplebar>
      <ul >
        <VCollapseLinks v-model:open="openSubsidebarLinks" :collapse-id="items.id" v-for="(items, key) in listMenu" :key="key">
          <template #header>
            {{ items.name }}
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>
          
          <li v-for="(items2, key2) in items.children" :key="key2">
          <RouterLink :to="{ name: items2.link }" class="is-submenu">
            <i aria-hidden="true" :class="['lnil lnil-' + items.icon]"></i>
            <span>{{ items2.name }}</span>
          </RouterLink>
         
          </li>
        </VCollapseLinks>

        <!-- <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="flex-lists">
          <template #header>
            Flex Lists
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>
          
          <RouterLink :to="{ name: 'sidebar-layouts-list-flex-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt-1"></i>
            <span>Flex List V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-flex-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt-1"></i>
            <span>Flex List V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-flex-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt-1"></i>
            <span>Flex List V3</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="datatable">
          <template #header>
            Datatable
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink
            :to="{ name: 'sidebar-layouts-list-datatable-1' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout-alt"></i>
            <span>Datatable V1</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-list-datatable-2' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout-alt"></i>
            <span>Datatable V2</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-list-datatable-3' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout-alt"></i>
            <span>Datatable V3</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-list-datatable-4' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout-alt"></i>
            <span>Datatable V4</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="form-layouts">
          <template #header>
            Form Layouts
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-form-layouts-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-passport"></i>
            <span>Form Layout V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-form-layouts-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-passport"></i>
            <span>Form Layout V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-form-layouts-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-passport"></i>
            <span>Form Layout V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-form-layouts-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-passport"></i>
            <span>Form Layout V4</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-form-layouts-5' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-passport"></i>
            <span>Form Layout V5</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="placeload">
          <template #header>
            Placeload
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-placeload-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V1</span>
            <VTag label="v1.2" color="primary" outlined curved />
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V2</span>
            <VTag label="v1.2" color="primary" outlined curved />
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V3</span>
            <VTag label="v1.2" color="primary" outlined curved />
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V4</span>
            <VTag label="v1.2" color="primary" outlined curved />
          </RouterLink>
        </VCollapseLinks>

        <li class="divider"></li>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="card-grid">
          <template #header>
            Card Grid
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-grid-cards-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Card Grid V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-cards-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Card Grid V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-cards-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Card Grid V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-cards-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Card Grid V4</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="tile-grid">
          <template #header>
            Tile Grid
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-grid-tiles-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-layout-alt-2"></i>
            <span>Tile Grid V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-tiles-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-layout-alt-2"></i>
            <span>Tile Grid V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-tiles-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-layout-alt-2"></i>
            <span>Tile Grid V3</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="user-grid">
          <template #header>
            User Grid
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-grid-users-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-users-alt"></i>
            <span>User Grid V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-users-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-users-alt"></i>
            <span>User Grid V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-users-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-users-alt"></i>
            <span>User Grid V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-grid-users-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-users-alt"></i>
            <span>User Grid V4</span>
          </RouterLink>
        </VCollapseLinks>

        <li class="divider"></li>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="personal">
          <template #header>
            Personal
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>
          <RouterLink :to="{ name: 'sidebar-layouts-profile-view' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-user-alt"></i>
            <span>Profile</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-profile-edit' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-pencil"></i>
            <span>Edit Profile</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-profile-notifications' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-notification"></i>
            <span>Notifications</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-profile-settings' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-cog"></i>
            <span>Settings</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="pages">
          <template #header>
            Pages
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'auth-login-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-pointer-right"></i>
            <span>Login v1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'auth-login-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-pointer-right"></i>
            <span>Login v2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'auth-login-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-pointer-right"></i>
            <span>Login v3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'auth-signup-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-crown"></i>
            <span>Signup v1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'auth-signup-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-crown"></i>
            <span>Signup v2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'auth-signup-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-crown"></i>
            <span>Signup Flow</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-search-results' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-search-alt"></i>
            <span>Search Results</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-search-empty' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-search-alt"></i>
            <span>Empty Search</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="subpages">
          <template #header>
            Subpages
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts-saas-billing' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-credit-card"></i>
            <span>SaaS Billing</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-action-page-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-thunderbolt"></i>
            <span>Action Page V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-action-page-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-thunderbolt"></i>
            <span>Action Page V2</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="projects">
          <template #header>
            Projects
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-projects-1' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Projects V1</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-projects-2' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Projects V2</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-projects-3' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Projects V3</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-details' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout"></i>
            <span>Project Details</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-kanban-board' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-layout-alt-1"></i>
            <span>Kanban Board</span>
          </RouterLink>
        </VCollapseLinks>

        <li class="divider"></li>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="utility">
          <template #header>
            Utility
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink
            :to="{ name: 'sidebar-layouts-utility-account-confirm' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-thunderbolt"></i>
            <span>Confirm Account</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-utility-promotion' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-magnet"></i>
            <span>Promotion Page</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-utility-invoice' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-calculator-alt"></i>
            <span>Invoice</span>
          </RouterLink>
          <RouterLink :to="{ name: 'status' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-checkmark-circle"></i>
            <span>App Status</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="onboarding">
          <template #header>
            Onboarding
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-welcome' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-door-alt"></i>
            <span>Onboarding Welcome</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-page-1' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-train"></i>
            <span>Onboarding V1</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-page-2' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-train-alt"></i>
            <span>Onboarding V2</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-page-3' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-car"></i>
            <span>Onboarding V3</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-page-4' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-car-alt"></i>
            <span>Onboarding V4</span>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-onboarding-page-5' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-train"></i>
            <span>Onboarding V5</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks v-model:open="openSubsidebarLinks" collapse-id="error-pages">
          <template #header>
            Error Pages
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'error-page-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-cross-circle"></i>
            <span>Error 404 V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'error-page-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-cross-circle"></i>
            <span>Error 404 V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'error-page-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-cross-circle"></i>
            <span>Error 404 V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'error-page-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-cross-circle"></i>
            <span>Error 404 V4</span>
          </RouterLink>
          <RouterLink :to="{ name: 'error-page-5' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-cross-circle"></i>
            <span>Error 500 V1</span>
          </RouterLink>
        </VCollapseLinks> -->
      </ul>
    </div>
  </div>
</template>

<style lang="scss">
@import '/@src/scss/layout/sidebar-panel';
</style>
