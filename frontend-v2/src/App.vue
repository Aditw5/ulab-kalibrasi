<script setup lang="ts">
import { initDarkmode } from '/@src/stores/darkmode'
import { onMounted,provide } from 'vue'
// import { initializeApp } from "firebase/app";
// import { getMessaging, onMessage, getToken } from 'firebase/messaging';
import { useNotyf } from '/@src/composable/useNotyf'

/**
 * Initialize the darkmode watcher
 *
 * @see /@src/stores/darkmode
 */
initDarkmode()
onMounted(() => {
  // let script = document.createElement('script')
  // let script2 = document.createElement('script')
  // let script3 = document.createElement('script')
  // script.setAttribute('src', '/plugins/qztray/qz-tray.js')
  // script2.setAttribute('src', '/plugins/qztray/jsrsasign-all-min.js')
  // script3.setAttribute('src', '/plugins/qztray/sign-message.js')
  // document.head.appendChild(script)
  // document.head.appendChild(script2)
  // document.head.appendChild(script3)

  // qz.security.setCertificatePromise(function (resolve, reject) {
  //   //Preferred method - from server
  //   fetch("/plugins/qztray/digital-certificate.txt", { cache: 'no-store', headers: { 'Content-Type': 'text/plain' } })
  //     .then(function (data) { data.ok ? resolve(data.text()) : reject(data.text()); });
  // });
})

// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional

// Initialize Firebase
// const app = initializeApp(firebaseConfig);


// Get registration token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
// const messaging = getMessaging();
// const requestNotificationPermission = async () => {
//   try {
//     const permission = await Notification.requestPermission();
//     const registrationToken = await getToken(messaging);
//     console.log('Registration token:', registrationToken);
//     console.log('Notification permission granted:', permission);
//   } catch (error) {
//     console.log('Error requesting notification permission:', error);
//   }
// };
// requestNotificationPermission()


// onMessage(messaging, (payload) => {
//   useNotyf().error(payload.notification?.body)
//   console.log('Message received. ', payload);
//   let e = {
//     "from": "480900949185",
//     "collapseKey": "campaign_collapse_key_330159876473807615",
//     "messageId": "72dc184d-19a8-42ee-939c-c260d793cd6f",
//     "notification": {
//       "title": "HI",
//       "body": "asa"
//     },
//     "data": {
//       "gcm.n.e": "1",
//       "google.c.a.ts": "1691398963",
//       "google.c.a.udt": "0",
//       "google.c.a.e": "1",
//       "google.c.a.c_id": "330159876473807615",
//       "google.c.a.c_l": "HI"
//     }
//   }

//   // ...
// });
// getToken(messaging, { vapidKey: 'MH7zZN8eyhnEn6u2_Nv_ZPx2tHYCPePK2gHIa3rs8Vk' }).then((currentToken) => {
//   if (currentToken) {
//     console.log('Token ',currentToken);
//     // Send the token to your server and update the UI if necessary
//     // ...
//   } else {
//     // Show permission request UI
//     console.log('No registration token available. Request permission to generate one.');
//     // ...
//   }
// }).catch((err) => {
//   console.log('An error occurred while retrieving token. ', err);
//   // ...
// });


</script>

<template>
  <div>
    <Suspense>
      <RouterView v-slot="{ Component }">
        <!-- <Transition name="fade-slow" mode="out-in"> -->
          <component :is="Component" />
        <!-- </Transition> -->
      </RouterView>
    </Suspense>
    <Toast position="bottom-right" group="br" />
    <VReloadPrompt app-name="Vue" />
  </div>
</template>

