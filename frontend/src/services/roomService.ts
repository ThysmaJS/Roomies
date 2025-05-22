const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export async function fetchRooms() {
  const res = await fetch(`${API_URL}/rooms`, {
    headers: { Accept: 'application/ld+json' },
  })
  const data = await res.json()
  return data['hydra:member'] || data.member || []
}

export async function createRoom(token: string, name: string, maxPlayers: number) {
  const res = await fetch(`${API_URL}/rooms`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/ld+json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ name, maxPlayers }),
  })
  const data = await res.json()
  return { ok: res.ok, data }
}
