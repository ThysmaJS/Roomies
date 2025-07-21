<template>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-blue-700 mb-6 text-center">📁 Mes Rooms créées</h1>
    <div v-if="createdRooms.length">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <RoomCard
          v-for="room in createdRooms"
          :key="room.id"
          :room="room"
          :showActions="true"
          @edit="editRoom(room)"
          @delete="handleDeleteRoom(room)"
          @select="() => $router.push(`/room/${room.id}`)"
        />
      </div>
    </div>
    <div v-else class="text-center text-gray-500 mt-8">
      😢 Aucune room créée pour l’instant.
    </div>

    <hr class="my-8 border-blue-200">

    <h2 class="text-xl font-bold text-blue-700 mb-4 text-center">👥 Rooms où je participe</h2>
    <div v-if="joinedRooms.length">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <RoomCard
          v-for="room in joinedRooms"
          :key="room.id"
          :room="room"
          :showActions="false"
          @select="() => $router.push(`/room/${room.id}`)"
        />
      </div>
    </div>
    <div v-else class="text-center text-gray-500 mt-8">
      Tu ne participes à aucune autre room.
    </div>

    <!-- MODAL édition -->
    <EditRoomModal
      :visible="editModalOpen"
      :room="editRoomData"
      @close="editModalOpen = false"
      @saved="onRoomSaved"
    />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { fetchMyRooms, fetchMyJoinedRooms, deleteRoom as apiDeleteRoom, updateRoom } from '@/services/roomService'
import RoomCard from '@/components/Room/RoomCard.vue'
import EditRoomModal from '@/components/Room/EditRoomModal.vue'
import { jwtDecode } from 'jwt-decode'

const router = useRouter()
const rooms = ref<any[]>([])
const joinedRooms = ref<any[]>([])
const token = localStorage.getItem('jwt_token')
const username = token ? jwtDecode<any>(token).username : null

const createdRooms = computed(() =>
  rooms.value.filter(room => room.owner?.username === username)
)

onMounted(async () => {
  if (!token) return router.push('/login')
  try {
    rooms.value = await fetchMyRooms(token)
    joinedRooms.value = await fetchMyJoinedRooms(token)
  } catch (err) {
    console.error('Erreur lors du chargement des rooms', err)
  }
})

// Modal édition
const editModalOpen = ref(false)
const editRoomData = ref<any>(null)

function editRoom(room: any) {
  editRoomData.value = { ...room }
  editModalOpen.value = true
}

async function onRoomSaved(updates: { name: string; maxPlayers: number }) {
  if (!editRoomData.value) return
  try {
    const { ok, data } = await updateRoom(editRoomData.value.id, token!, updates)
    if (ok) {
      // MAJ dans rooms.value localement
      const idx = rooms.value.findIndex(r => r.id === editRoomData.value.id)
      if (idx !== -1) {
        rooms.value[idx].name = updates.name
        rooms.value[idx].maxPlayers = updates.maxPlayers
      }
      editModalOpen.value = false
    } else {
      alert('Erreur lors de la modification')
    }
  } catch (err) {
    alert('Erreur lors de la modification')
  }
}

async function handleDeleteRoom(room: any) {
  if (confirm(`Supprimer la room "${room.name}" ?`)) {
    try {
      const ok = await apiDeleteRoom(room.id, token!)
      if (ok) {
        rooms.value = rooms.value.filter(r => r.id !== room.id)
      }
    } catch (err) {
      alert('Erreur lors de la suppression de la room')
    }
  }
}
</script>
