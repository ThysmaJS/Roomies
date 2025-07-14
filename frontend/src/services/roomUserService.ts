const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export async function toggleReady(roomUserId: number, isReady: boolean, token: string) {
  const res = await fetch(`${API_URL}/room_users/${roomUserId}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json', // ✅ standard JSON, pas "merge-patch"
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ isReady }),
  })

  const data = await res.json()
  return { ok: res.ok, data }
}
