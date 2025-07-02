<script setup lang="ts">
import { computed, ref, watch, onUnmounted, reactive, nextTick, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { VAvatarProps, VAvatarColor } from '/@src/components/base/avatar/VAvatar.vue'
import type { UserPopover } from '/@src/models/users'
import { popovers } from '/@src/data/users/userPopovers'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { usePanels } from '/@src/stores/panels'
import { useStorage } from '@vueuse/core'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
export type Menu = Model.Global.Menu
export type NavbarTheme = 'default' | 'colored' | 'fade'
export type SubnavId =
  | 'closed'
  | 'home'
  | 'layouts'
  | 'elements'
  | 'components'
  | 'search'

const colorPasien: any = ['h-yellow', 'info', 'h-purple', 'danger', 'success', 'warning']
const avatarColor: any = [

  'success'
  , 'info'
  , 'warning'
  , 'danger'
  , 'h-purple'
  , 'h-orange'
  , 'h-blue'
  , 'h-green'
  , 'h-red'
  , 'h-yellow'
]
const props: any = withDefaults(
  defineProps<{
    theme?: NavbarTheme
    nowrap?: boolean
  }>(),
  {
    theme: 'default',
  }
)
const isLoading: any = ref(false)
const viewWrapper: any = useViewWrapper()
// viewWrapper.setFullWidth(true)
// console.log(viewWrapper.isFullWidth)
const panels: any = usePanels()
const route: any = useRoute()
const filter: any = ref('')
const isMobileSidebarOpen: any = ref(false)
const activeMobileSubsidebar: any = ref('layouts')
const activeSubnav: any = ref<SubnavId>('closed')
let resultP = reactive([])
const filteredPasiens: any = ref([])
const namaProfile = useUserSession().getProfile().namaexternal
const router = useRouter()

const toggleSubnav = (subnav: SubnavId) => {
  if (activeSubnav.value === subnav) {
    activeSubnav.value = 'closed'
  } else {
    activeSubnav.value = subnav
  }
}

const getAvatarData = (user: any): VAvatarProps => {
  return {
    picture: user.avatar,
    initials: user.initials,
    color: user.color as VAvatarColor,
  }
}

const gotoLink = (name: any) => {
  router.push({
    name: name,
  })
}
const showForm = (e: any) => {

  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.replace({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.id,
      norec_pd: e.norec,
      isfull: 'true'
    }
  })
}
const toggleEnter = (params: any) => {
  router.push({
    name: 'module-registrasi-list-pasien',
    query: {
      namapasien: params,
    },
  })
}
const toggleDashboard = () => {
  const user = useUserSession().getUser()
  if (user.kelompokUser.menu != null) {
    router.push({
      name: user.kelompokUser.menu,
    })
  } else {
    router.push({
      name: 'module-dashboard-registrasi',
    })
  }

}

watch(
  () => route.fullPath,
  () => {
    activeSubnav.value = 'closed'
    isMobileSidebarOpen.value = false
  }
)

const waktusekarang = ref('00:00:00 WIB');
let worker: Worker | null = null;

const getTimeInServer = async () => {
  const response = await useApi().get('/get-time-server');
  const { date, time, second } = response;
  const serverTime = new Date(`${date}T${time}:${second}`).getTime();
  if (worker) {
    worker.postMessage({ timeServer: serverTime });
  }
};

onMounted(() => {
  worker = new Worker('/workers/timeWorker.js');
  worker.onmessage = (event) => {
    waktusekarang.value = event.data;
  };
  getTimeInServer();
});

onUnmounted(() => {
  if (worker) {
    worker.terminate();
    worker = null;
  }
});

let listMenu = useStorage('list_menu', [])
const api = useApi()
// if (listMenu.value.length == 0) {
await api.get(
  `/general/menu/list-menu?idUser=${useUserSession().getUser().id}`
).then((response: any) => {
  listMenu.value = response
}, (error) => {
  listMenu.value = []
  console.log(error)
})



// }
</script>
<template>
  <div class="navbar-layout">
    <div class="app-overlay"></div>

    <!-- Mobile navigation -->
    <MobileNavbar :is-open="isMobileSidebarOpen" @toggle="isMobileSidebarOpen = !isMobileSidebarOpen">
      <template #brand>
        <RouterLink :to="{ name: 'index' }" class="navbar-item is-brand">
          <AnimatedLogoJMT />
        </RouterLink>

        <div class="brand-end">
          <NotificationsMobileDropdown />
          <UserProfileDropdown />
        </div>
      </template>
    </MobileNavbar>

    <!-- Mobile sidebar links -->
    <MobileSidebar :is-open="isMobileSidebarOpen" @toggle="isMobileSidebarOpen = !isMobileSidebarOpen">
      <template #links>
        <!-- <li>
          <a :class="[activeMobileSubsidebar === 'dashboard' && 'is-active']" tabindex="0"
            @keydown.space.prevent="toggleDashboard()" @click="toggleDashboard()">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
          </a>
        </li> -->
        <li>
          <a :class="[activeMobileSubsidebar === 'layouts' && 'is-active']" tabindex="0"
            @keydown.space.prevent="activeMobileSubsidebar = 'layouts'" @click="activeMobileSubsidebar = 'layouts'">
            <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
          </a>
        </li>
        <!-- <li :class="[activeMobileSubsidebar === 'elements' && 'is-active']" tabindex="0"
          @keydown.space.prevent="activeMobileSubsidebar = 'elements'" @click="activeMobileSubsidebar = 'elements'">
          <a>
            <i aria-hidden="true" class="iconify" data-icon="feather:box"></i>
          </a>
        </li>
        <li :class="[activeMobileSubsidebar === 'components' && 'is-active']" tabindex="0"
          @keydown.space.prevent="activeMobileSubsidebar = 'components'" @click="activeMobileSubsidebar = 'components'">
          <a>
            <i aria-hidden="true" class="iconify" data-icon="feather:cpu"></i>
          </a>
        </li>
        <li>
          <RouterLink :to="{ name: 'messaging-v1' }">
            <i aria-hidden="true" class="iconify" data-icon="feather:message-circle"></i>
          </RouterLink>
        </li> -->
      </template>

      <template #bottom-links>
        <li>
          <a tabindex="0" @keydown.space.prevent="panels.setActive('search')" @click="panels.setActive('search')">
            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
          </a>
        </li>
        <li>
          <a href="#">
            <i aria-hidden="true" class="iconify" data-icon="feather:settings"></i>
          </a>
        </li>
      </template>
    </MobileSidebar>

    <!-- Mobile subsidebar links -->
    <Transition name="slide-x">
      <LayoutsMobileSubsidebar v-if="isMobileSidebarOpen && activeMobileSubsidebar === 'layouts'" />
      <DashboardsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'dashboard'" />
      <ComponentsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'components'" />
      <ElementsMobileSubsidebar v-else-if="isMobileSidebarOpen && activeMobileSubsidebar === 'elements'" />
    </Transition>

    <!-- Desktop navigation -->
    <Navbar :theme="props.theme">
      <template #title>
        <!-- <RouterLink :to="{ name: 'index' }" class="brand"> -->
        <div class="brand" @click="toggleDashboard">
          <AnimatedLogoJMT width="38" height="38" />
        </div>
        <!-- </RouterLink> -->

        <div class="separator"></div>
        <LogoRS width="90" height="90" class="mr-3" />
        <!-- <ProjectsQuickDropdown /> -->
        <div>
          <h1 class="title is-5 mt-0 ml-3 mb-0">{{ namaProfile }}</h1>
          <!-- <p class="title is-4  mt-0 ml-3">{{ viewWrapper.pageTitle }}</p> -->
        </div>
      </template>

      <template #toolbar>
        <div style="display: flex; align-items: center;">
          <div style="font-size: 15px; font-weight: bold; margin-right: 20px;">
            {{ waktusekarang }}
          </div>
          <Toolbar class="desktop-toolbar">
            <ToolbarNotification />

            <a class="toolbar-link right-panel-trigger" tabindex="0"
              @keydown.space.prevent="panels.setActive('activity')" @click="panels.setActive('activity')">
              <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
            </a>
          </Toolbar>
        </div>

        <LayoutSwitcher />
        <UserProfileDropdown right />
      </template>



      <template #links>
        <div class="centered-links" :class="[activeSubnav === 'search' && 'is-hidden']">
          <!-- <a :class="[
            (activeSubnav === 'home' || route.path.startsWith('/navbar/dashboards')) &&
            'is-active',
          ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleSubnav('home')"
            @click="toggleSubnav('home')">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
            <span>Dashboards</span>
          </a> -->

          <a :class="[
            (activeSubnav === 'home' || route.path.startsWith('/navbar/dashboards')) &&
            'is-active',
          ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleDashboard()"
            @click="toggleDashboard()">
            <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
            <span>Dashboards</span>
          </a>

          <a :class="[
            (activeSubnav === 'layouts' || route.path.startsWith('/navbar/layouts')) &&
            'is-active',
          ]" class="centered-link centered-link-toggle" tabindex="0" @keydown.space.prevent="toggleSubnav('layouts')"
            @click="toggleSubnav('layouts')">
            <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
            <span>Navigation</span>
          </a>
        </div>

        <div class="centered-search" :class="[activeSubnav !== 'search' && 'is-hidden']">
          <div class="field">

          </div>
        </div>
      </template>

      <template #subnav>
        <div :class="[
          !(activeSubnav === 'closed' || activeSubnav === 'search') && 'is-active',
        ]" class="navbar-subnavbar">
          <DashboardsSubnav :class="[activeSubnav === 'home' && 'is-active']" />

          <LayoutsSubnav :class="[activeSubnav === 'layouts' && 'is-active']" style="    height: 620px;" />

          <ElementsSubnav :class="[activeSubnav === 'elements' && 'is-active']" />

          <ComponentsSubnav :class="[activeSubnav === 'components' && 'is-active']" />
        </div>
      </template>
    </Navbar>

    <LanguagesPanel />
    <ActivityPanel />
    <TaskPanel />

    <VViewWrapper top-nav>
      <VPageContentWrapper :fullWidth="viewWrapper.isFullWidth">
        <template v-if="props.nowrap">
          <slot></slot>
        </template>
        <VPageContent v-else class="is-relative">
          <div class="is-navbar-lg">
            <div class="page-title has-text-centered">
              <!-- Mobile Page Title -->
              <div class="title-wrap">
                <h1 class="title is-4">{{ viewWrapper.pageTitle }}</h1>
              </div>

              <Toolbar class="mobile-toolbar">
                <!-- <ToolbarNotification /> -->

                <a class="toolbar-link right-panel-trigger" tabindex="0"
                  @keydown.space.prevent="panels.setActive('activity')" @click="panels.setActive('activity')">
                  <i aria-hidden="true" class="iconify" data-icon="feather:grid"></i>
                </a>
              </Toolbar>
            </div>

            <slot></slot>
          </div>
        </VPageContent>
      </VPageContentWrapper>
    </VViewWrapper>
  </div>
</template>
