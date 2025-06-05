<template>
  <VCard radius="small" v-for="data in dataFromDatabase.data">
    <div class="card-head">
      <VBlock
        :title="data.namapasien"
        :subtitle="data.namalengkap"
        center
        class="no-margin"
      >
        <template #icon>
          <Tippy
            class="has-help-cursor"
            interactive
            :offset="[0, 10]"
            placement="top-start"
          >
            <VAvatar
              picture="/demo/avatars/19.jpg"
              badge="/images/icons/flags/germany.svg"
            />
            <template #content>
              <UserPopoverContent
                :user="{
                  id: 19,
                  avatar: '/demo/avatars/19.jpg',
                  badge: '/images/icons/flags/germany.svg',
                  username: 'Greta K.',
                  fullName: 'Greta Kroppfer',
                  initials: 'GK',
                  color: 'h-yellow',
                  location: 'Los Angeles, CA',
                  position: 'Sales Manager',
                  bio: 'This is a nice user description that we can use as demo content.',
                }"
              />
            </template>
          </Tippy>
        </template>
      </VBlock>

      <UserCardDropdown />
    </div>

    <div class="card-inner">
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quibusnam praeteritis? At
        multis se probavit. Quoniam, si dis placet, ab Epicuro loqui discimus. Et ille
        ridens.
      </p>
    </div>
  </VCard>

  <Calendar v-model="date" />
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import Calendar from 'primevue/calendar';
import { useApi } from '/@src/composable/useApi'
  const arrayData = ref([
    {
      id: 19,
      avatar: '/demo/avatars/19.jpg',
      badge: '/images/icons/flags/germany.svg',
      username: 'Greta K.',
      fullName: 'Greta Kroppfer',
      initials: 'GK',
      color: 'h-yellow',
      location: 'Los Angeles, CA',
      position: 'Sales Manager',
      bio: 'This is a nice user description that we can use as demo content.',
    },
    {
      id: 19,
      avatar: '/demo/avatars/19.jpg',
      badge: '/images/icons/flags/germany.svg',
      username: 'Greta K.',
      fullName: 'Greta Kroppfer',
      initials: 'GK',
      color: 'h-yellow',
      location: 'Los Angeles, CA',
      position: 'Sales Manager',
      bio: 'This is a nice user description that we can use as demo content.',
    },
    {
      id: 19,
      avatar: '/demo/avatars/19.jpg',
      badge: '/images/icons/flags/germany.svg',
      username: 'Greta K.',
      fullName: 'Greta Kroppfer',
      initials: 'GK',
      color: 'h-yellow',
      location: 'Los Angeles, CA',
      position: 'Sales Manager',
      bio: 'This is a nice user description that we can use as demo content.',
    },
  ])

  const dataFromDatabase = await useApi().get('dashboard/igd-pasien?limit=10')
  console.log(dataFromDatabase)
</script>

<!-- VUERO -->
