const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

// 🔹 GET /rooms
export async function fetchRooms(token?: string) {
  const res = await fetch(`${API_URL}/rooms`, {
    headers: {
      Accept: 'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
  })

  const data = await res.json()
  return Array.isArray(data) ? data : []
}

// 🔹 POST /rooms
export async function createRoom(token: string, name: string, maxPlayers: number) {
  const res = await fetch(`${API_URL}/rooms`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ name, maxPlayers }),
  })

  const data = await res.json()
  return { ok: res.ok, data }
}

// 🔹 POST /room_users
export async function joinRoom(roomId: number, token: string) {
  const res = await fetch(`${API_URL}/rooms/${roomId}/join`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  return { ok: res.ok, data: await res.json() }
}


// 🔹 GET /my/rooms
export async function fetchMyRooms(token: string) {
  const res = await fetch(`${API_URL}/rooms/my/rooms`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
  })

  const data = await res.json()
  return Array.isArray(data) ? data : []
}

// 🔹 DELETE /rooms/{id}
export async function deleteRoom(roomId: number, token: string) {
  const res = await fetch(`${API_URL}/rooms/${roomId}`, {
    method: 'DELETE',
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })

  return res.ok
}

// 🔹 PATCH /room_users/{id}
export async function toggleReady(roomUserId: number, isReady: boolean, token: string) {
  const res = await fetch(`${API_URL}/room_users/${roomUserId}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ isReady }),
  })

  const data = await res.json()
  return { ok: res.ok, data }
}
