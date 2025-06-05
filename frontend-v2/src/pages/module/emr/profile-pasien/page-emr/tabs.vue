<template>
    <div class="flex mb-2 gap-2 justify-content-end">
      <!-- <span class="mt-2">Halaman</span> -->
  
      <VTag class="mt-2 mr-2" color="solid" label="Halaman" curved />
      <div v-if="isLoadTab">
        <VButton circle color="info" rounded raised bold @click="active = index" :outlined="active !== index"
          v-for="(items, index) in listPage" :key="index" class="ml-1"> {{ index + 1 }} </VButton>
      </div>
      <div v-else>
        <VAvatarStack>
          <VPlaceloadAvatar class="mx-1" />
          <VPlaceloadAvatar class="mx-1" />
          <VPlaceloadAvatar class="mx-1" />
        </VAvatarStack>
      </div>
       <VIconButton circle icon="feather:plus" raised bold @click="listPageT()" v-tooltip.bubble="'Tambah Page'"
        class="ml-5"> </VIconButton>
      <VIconButton circle icon="feather:minus" v-if="listPage > 1" raised bold @click="listPageK()"
        v-tooltip.bubble="'Kurangi Page'"> </VIconButton>
    </div>
    <!-- <VCard> -->
    <!-- <TabView v-model:activeIndex="active"> -->
    <!-- <TabPanel :header="'Page ' + (index + 1)" v-for="(items, index) in listPage" :key="index"> -->
    <RouterView v-slot="{ Component, route }" :pasien="props.pasien" :registrasi="props.registrasi" :FORM_NAME="FORM_NAME"
      v-if="isLoadTab" :FORM_URL="FORM_URL" :COLLECTION="COLLECTION">
      <component :is="Component" />
    </RouterView>
    <!-- </TabPanel> -->
    <!-- </TabView> -->
    <!-- </VCard> -->
  </template>
  
  <script setup lang="ts">
  import { useApi } from '/@src/composable/useApi'
  import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useHead } from '@vueuse/head'
  import * as H from '/@src/utils/appHelper'
  // import TabView from 'primevue/tabview';
  // import TabPanel from 'primevue/tabpanel';
  const route = useRoute()
  const router = useRouter()
  const props = withDefaults(
    defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
      COLLECTION?: string
    }>(),
    {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
      COLLECTION: '',
    }
  )
  const norec_emr = route.query.norec_emr as string
  let active: any = ref(
  Array.isArray(route.params.index_tabs) 
    ? parseInt(route.params.index_tabs[0]) 
    : parseInt(route.params.index_tabs)
    );
  let listPage = ref(active.value > 0 ? parseInt(active.value) : 1);
  let isLoadTab: any = ref(false)
  active.value = 0
  
  const fetchTabs = async () => {
    isLoadTab.value = false
    await useApi().get(
      `/emr/get-tabs-emr?nocmfk=${props.pasien.nocmfk}&norec_pd=${props.registrasi.norec_pd}&collection=${props.COLLECTION}`).then((response: any) => {
        // console.log(response)
        isLoadTab.value = true
        listPage.value = response
        // console.log(listPage.value)
  
      })
  }
  fetchTabs()
  watch(()=>
  (route.path),(newvalue,oldValue)=>{
    let arrayNewPath =newvalue.split('/')
    let arrayOldPath =oldValue.split('/')
    if (arrayNewPath[6] != arrayOldPath[6]) {
      let path = route.path
      if(path.indexOf('tabs') > -1){
        fetchTabs()
        path = path.replaceAll('/', '-')
        let hilangkanAwal = path.substr(1, path.length)
        let replace = hilangkanAwal.substr(hilangkanAwal.length - 2)
        let pathName = hilangkanAwal.replace(replace, '')
        active.value =0
        let query = {}
        if (norec_emr != undefined && norec_emr != '') {
          query = {
            nocmfk: props.registrasi.nocmfk,
            norec_pasien_daftar: props.registrasi.norec_pd,
            norec_pd: props.registrasi.norec_pd,
            norec_emr: norec_emr,
          }
        } else {
          query = {
            nocmfk: props.registrasi.nocmfk,
            norec_pasien_daftar: props.registrasi.norec_pd,
            norec_pd: props.registrasi.norec_pd,
          }
        }
        router.push({
          name: pathName + '-index_tabs',
          query: query,
          params: {
            index_tabs: 1
          }
        })
      }
    }
  })
  watch(
    () => active.value,
    (newValue, oldValue) => {
      let path = route.path
      path = path.replaceAll('/', '-')
      let hilangkanAwal = path.substr(1, path.length)
      let replace = hilangkanAwal.substr(hilangkanAwal.length - 2)
      let pathName = hilangkanAwal.replace(replace, '')

      // Fix: Mencatat nama rute yang dibuat untuk debugging
      console.log('Constructed path name:', pathName + '-index_tabs')
  
      let query = {}
      if (norec_emr != undefined && norec_emr != '') {
        query = {
          nocmfk: props.registrasi.nocmfk,
          norec_pasien_daftar: props.registrasi.norec_pd,
          norec_pd: props.registrasi.norec_pd,
          norec_emr: norec_emr,
        }
      } else {
        query = {
          nocmfk: props.registrasi.nocmfk,
          norec_pasien_daftar: props.registrasi.norec_pd,
          norec_pd: props.registrasi.norec_pd,
        }
      }
  
      // Fix: Gunakan try-catch untuk menangani error navigasi
      try {
        // Fix: Pastikan format nama rute sudah benar
        // Error menunjukkan adanya tanda hubung ganda pada nama rute
        const routeName = pathName.endsWith('-') 
          ? pathName + 'index_tabs' 
          : pathName + '-index_tabs';
          
        router.push({
          name: routeName,
          query: query,
          params: {
            index_tabs: (newValue + 1).toString() // Konversi ke string untuk memastikan parameter ditangani dengan benar
          }
        });
      } catch (error) {
        console.error('Navigation error:', error);
        
        // Navigasi alternatif menggunakan path daripada name
        try {
          const basePath = route.path.split('/index_tabs/')[0];
          router.push({
            path: `${basePath}/index_tabs/${newValue + 1}`,
            query: query
          });
        } catch (fallbackError) {
          console.error('Fallback navigation error:', fallbackError);
        }
      }
  
      // router.push({
      //   name: pathName + '-index_tabs',
      //   query: query,
      //   params: {
      //     index_tabs: newValue + 1
      //   }
      // })


  
    }
  )
  const listPageT = async()=> {
    listPage.value = listPage.value + 1
  }
  
  const listPageK = async()=> {
    if(listPage.value < 2){
      return
    }
    listPage.value = listPage.value - 1
  }
  </script>
  