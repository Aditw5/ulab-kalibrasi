<script setup lang="ts">
import { useStorage } from '@vueuse/core'
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
const core_listMenu: any = useStorage('list_menu', [])
const filter = ref('')
const router = useRouter()
const filteredMenu = computed(() => {
  if (!filter.value) {
    return core_listMenu.value;
  }
  const filtered = [];
  for (let i = 0; i < core_listMenu.value.length; i++) {
    const parent = core_listMenu.value[i];
    const matchedChildren = [];
    if (parent.name.match(new RegExp(filter.value, 'i'))) {
      filtered.push({
        'id': parent.id,
        'parent_id': parent.parent_id,
        'name': parent.name,
        'icon': parent.icon,
        'ishide': parent.ishide,
        'children': parent.children
      });
    } else {
      for (let j = 0; j < parent.children.length; j++) {
        const child = parent.children[j];
        if (child.name.match(new RegExp(filter.value, 'i'))) {
          matchedChildren.push(child);
        }
      }
      if (matchedChildren.length > 0) {
        filtered.push({
          'id': parent.id,
          'parent_id': parent.parent_id,
          'name': parent.name,
          'icon': parent.icon,
          'ishide': parent.ishide,
          'children': matchedChildren
        });
      }
    }
  }

  return filtered;
});
const openMenu = (link: any) => {
  try {
    router.push({ name: link })
  } catch (error) {
    console.error(error)
    window.location.href = `/404${route.fullPath}`
  }

}
</script>
<template>
  <div class="mobile-subsidebar">
    <div class="inner">
      <div class="sidebar-title">
        <h3>Navigation</h3>
      </div>
      <ul class="submenu">

        <!-- <VCollapseLinks  v-for="(items, key) in core_listMenu" :key="key" v-for="(items2, key2) in items.children" :key="key2">
          <template #header >
            {{ items.name }}
            <i aria-hidden="true" class="iconify" dat-icon="feather:chevron-right" />
          </template>
          <RouterLink  :to="{ name: items2.link }"
            class="is-submenu">
            <i aria-hidden="true" :class="['lnil lnil-' + items.icon]"></i>
            <span>{{ items2.name }}</span>
          </RouterLink>
        </VCollapseLinks> -->
        <div class="columns">
            <div class="centered-search my-3">
              <div class="field">
                <div class="control has-icon">
                  <VField>
                    <VControl>
                      <VInput v-model="filter" type="text" class="input is-rounded search-input"
                        placeholder="Search menu..." :loading="isLoading" @keydown.enter.prevent="toggleEnter(filter)" />
                    </VControl>
                  </VField>

                  <div class="form-icon">

                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                  </div>
                </div>
             </div>
            </div>
          <div class="column is-3" v-for="(items, key) in filteredMenu" :key="key">
            <h4 class="column-heading">{{ items.name }}</h4>
            <ul>
              <li v-for="(items2, key2) in items.children" :key="key2">
                <a @click="openMenu(items2.link)">
                  <i :class="['lnil lnil-' + items.icon]" aria-hidden="true"></i>
                  <span>{{ items2.name }}</span>
                  <!-- <i aria-hidden="true" class="iconify" data-icon="feather:chevron-down"></i> -->
                </a>
              </li>
            </ul>
          </div>
        </div>
        <!-- <li>
          <RouterLink :to="{ name: 'wizard-v1' }">Setting</RouterLink>
        </li>

          <VCollapseLinks>
          <template #header>
            Lists
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V4</span>
          </RouterLink>
        </VCollapseLinks>
        <VCollapseLinks>
          <template #header>
            Lists
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-list-view-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-list-alt"></i>
            <span>List View V4</span>
          </RouterLink>
        </VCollapseLinks>

        <VCollapseLinks>
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

        <VCollapseLinks>
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

        <VCollapseLinks>
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

        <VCollapseLinks>
          <template #header>
            Placeload
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-1' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V1</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-2' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V2</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-3' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V3</span>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-placeload-4' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-reload"></i>
            <span>Placeload V4</span>
          </RouterLink>
        </VCollapseLinks>

        <li class="divider"></li>

        <VCollapseLinks>
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

        <VCollapseLinks>
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

        <VCollapseLinks>
          <template #header>
            User Grid
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>

          <RouterLink :to="{ name: 'sidebar-layouts' }" class="is-submenu">
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
        <VCollapseLinks>
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

        <VCollapseLinks>
          <template #header>
            Pages
            <i aria-hidden="true" class="iconify" data-icon="feather:chevron-right" />
          </template>
        </VCollapseLinks>

        <VCollapseLinks>
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

        <VCollapseLinks>
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

        <VCollapseLinks>
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
            <i aria-hidden="true" class="iconify is-auto" data-icon="feather:map-pin"></i>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-projects-2' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Projects V2</span>
            <i aria-hidden="true" class="iconify is-auto" data-icon="feather:map-pin"></i>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-projects-3' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-grid-alt"></i>
            <span>Projects V3</span>
            <i aria-hidden="true" class="iconify is-auto" data-icon="feather:map-pin"></i>
          </RouterLink>
          <RouterLink
            :to="{ name: 'sidebar-layouts-projects-details' }"
            class="is-submenu"
          >
            <i aria-hidden="true" class="lnil lnil-layout"></i>
            <span>Project Details</span>
            <i aria-hidden="true" class="iconify is-auto" data-icon="feather:map-pin"></i>
          </RouterLink>
          <RouterLink :to="{ name: 'sidebar-layouts-kanban-board' }" class="is-submenu">
            <i aria-hidden="true" class="lnil lnil-layout-alt-1"></i>
            <span>Kanban Board</span>
            <i aria-hidden="true" class="iconify is-auto" data-icon="feather:map-pin"></i>
          </RouterLink>
        </VCollapseLinks>

        <li class="divider"></li>
        <VCollapseLinks>
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
        <VCollapseLinks>
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

        <VCollapseLinks>
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
