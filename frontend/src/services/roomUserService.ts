const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

export async function toggleReady(roomUserId: number, isReady: boolean, token: string) {
  try {
    const res = await fetch(`${API_URL}/room_users/${roomUserId}/ready`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify({ isReady })
    })
    let data = null
    try {
      // Si pas de contenu (204), pas de JSON à parser
      if (res.status !== 204) data = await res.json()
    } catch (e) {
      data = null
    }
    return { ok: res.ok, data }
  } catch (e) {
    return { ok: false }
  }
}
