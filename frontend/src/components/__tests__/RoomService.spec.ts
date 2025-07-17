// src/services/__tests__/roomService.spec.ts
import { fetchRooms } from '../../services/roomService'
import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'

describe('fetchRooms', () => {
  beforeEach(() => {
    vi.stubGlobal('fetch', vi.fn(() =>
      Promise.resolve({
        json: () => Promise.resolve([{ name: 'Room1' }])
      })
    ) as any)
  })

  afterEach(() => {
    vi.unstubAllGlobals()
  })

  it('renvoie la liste des rooms', async () => {
    const rooms = await fetchRooms('dummy_token')
    expect(rooms.length).toBe(1)
    expect(rooms[0].name).toBe('Room1')
  })
})
