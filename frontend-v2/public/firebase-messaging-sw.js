// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js')

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
  apiKey: 'AIzaSyD0PT-popeB2wYEYZg9PdRwmD2zzdYTYvQ',
  authDomain: 'transmedic-v3-dcd4c.firebaseapp.com',
  projectId: 'transmedic-v3-dcd4c',
  storageBucket: 'transmedic-v3-dcd4c.appspot.com',
  messagingSenderId: '480900949185',
  appId: '1:480900949185:web:433c114e9f5099b4972040',
  measurementId: 'G-747LFMWQPB',
})

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging()

messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload)
  // Customize notification here
  const notificationTitle = payload.notification.title
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/logo-rs.png',
  }

  self.registration.showNotification(notificationTitle, notificationOptions)
})
