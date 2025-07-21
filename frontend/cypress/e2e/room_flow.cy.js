// cypress/e2e/room_flow.cy.js

describe('Flow utilisateur complet', () => {
  it('peut créer et rejoindre une room', () => {
    cy.visit('/login')
    cy.get('input[type=text]').type('thysma')
    cy.get('input[type=password]').type('Sonie007;)')
    cy.get('button[type=submit]').click()

    // Attendre d’être redirigé et que le bouton soit là
    cy.contains('Afficher toutes les rooms', { timeout: 8000 }).click()

    // Attendre d'être sur la page gestion
    cy.contains('Créer la room', { timeout: 8000 })

    cy.contains('Créer la room').click()
    cy.get('input').first().type('Room Cypress')
    cy.get('button[type=submit]').click()

    cy.contains('Mes Rooms').click()
    cy.contains('Room Cypress').click()
    cy.get('input[placeholder="Écris ton message..."]').type('Hello Cypress!')
    cy.get('button').contains('Envoyer').click()
    cy.contains('Hello Cypress!')
  })
})
