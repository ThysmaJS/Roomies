// src/components/__tests__/RoomCard.spec.ts
import { render } from '@testing-library/vue'
import RoomCard from '../Room/RoomCard.vue'
import { describe, it, expect } from 'vitest'

describe('RoomCard', () => {
  it('affiche le nom de la room', () => {
    const room = { name: 'Salle 42', maxPlayers: 3, roomUsers: [], owner: { username: 'Alice' } }
    const { getByText } = render(RoomCard, { props: { room } })
    expect(getByText('Salle 42')).toBeTruthy()
  })
})
