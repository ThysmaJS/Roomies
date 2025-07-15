const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export async function toggleReady(roomUserId: number, isReady: boolean, token: string) {
  try {
    const res = await fetch(`/api/room_users/${roomUserId}/ready`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify({ isReady })
    })
    return await res.json()
  } catch (e) {
    return { ok: false }
  }
}



