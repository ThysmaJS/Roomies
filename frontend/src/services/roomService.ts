const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export async function fetchMyRooms(token: string) {
  const res = await fetch(`${API_URL}/rooms/my/rooms`, {
    headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
  })
  const data = await res.json()
  return Array.isArray(data) ? data : []
}

export async function fetchMyJoinedRooms(token: string) {
  // CORRECT URL : pas de "/rooms"
  const res = await fetch(`${API_URL}/my/joined`, {
    headers: { Authorization: `Bearer ${token}` }
  });
  if (!res.ok) throw new Error('Erreur API');
  return await res.json();
}


export async function deleteRoom(roomId: number, token: string) {
  const res = await fetch(`${API_URL}/rooms/${roomId}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` }
  });
  return res.ok
}

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
