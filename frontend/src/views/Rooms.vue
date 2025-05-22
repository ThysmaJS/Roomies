<template>
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-12 font-sans text-blue-900">
      <h1 class="text-3xl font-bold text-center text-blue-800 uppercase">🎮 Gérer les rooms</h1>
      <RoomCreateForm @refresh="refreshRooms" />
      <RoomListFull ref="listRef" />
    </div>
  </template>
  
  <script setup lang="ts">
  import { onBeforeMount, ref } from 'vue'
  import { useRouter } from 'vue-router'
  import RoomCreateForm from '@/components/Room/RoomCreateForm.vue'
  import RoomListFull from '@/components/Room/RoomListFull.vue'
  
  const router = useRouter()
  const token = localStorage.getItem('jwt_token')
  if (!token) router.push('/login')
  
  const listRef = ref<InstanceType<typeof RoomListFull> | null>(null)
  function refreshRooms() {
    listRef.value?.loadRooms()
  }
  </script>
  