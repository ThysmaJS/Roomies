const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

// Liste toutes les rooms
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

// Créer une room
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

// Supprimer une room
export async function deleteRoom(roomId: number, token: string) {
  const res = await fetch(`${API_URL}/rooms/${roomId}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` }
  })
  return res.ok
}

// Modifier une room (nom ou maxPlayers)
export async function updateRoom(roomId: number, token: string, updates: { name?: string; maxPlayers?: number }) {
  const res = await fetch(`${API_URL}/rooms/${roomId}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(updates),
  })
  const data = await res.json()
  return { ok: res.ok, data }
}

// Rooms dont je suis owner
export async function fetchMyRooms(token: string) {
  const res = await fetch(`${API_URL}/rooms/my/rooms`, {
    headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
  })
  const data = await res.json()
  return Array.isArray(data) ? data : []
}

// Rooms où je participe (hors owner)
export async function fetchMyJoinedRooms(token: string) {
  const res = await fetch(`${API_URL}/my/joined`, {
    headers: { Authorization: `Bearer ${token}` }
  })
  if (!res.ok) throw new Error('Erreur API')
  return await res.json()
}

// Rejoindre une room (en tant qu'utilisateur)
export async function joinRoom(roomId: number, token: string) {
  const res = await fetch(`${API_URL}/rooms/${roomId}/join`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
  const data = await res.json()
  return { ok: res.ok, data }
}
