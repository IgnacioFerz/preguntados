describe('When a user access to login screen and have a button for login', () => {
  it('Go to the login screen and have a login button', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
  })
})

describe('User cant login into the app with unregistred credentials', () => {
  it('User login into the app and fail', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('#username').type('Test1Fail')
    cy.get('#password').type('Test1Fail')
    cy.get('button[type=submit]').click()
    cy.wait(2000);

  })
})

 describe('User register into the app', () => {
   it('User can register', () => {
     cy.visit('http://127.1.5.20:8000/login')
     cy.get('.register-link').click()
     cy.get('#user_name').type('nachok')
     cy.get('#user_password').type('1234')
     cy.get('#user_email').type('nachok@a')
     cy.get('button[type=submit]').click()
     cy.wait(4000)


   })
 })

describe('User can login into the app', () => {
  it('User login into the app and fail', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('#username').type('nachok')
    cy.get('#password').type('1234')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.get('#logout').click()
  })
})

describe('User can log-out into the app', () => {
  it('User login into the app and fail', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('#username').type('nachok')
    cy.get('#password').type('1234')
    cy.get('button[type=submit]').click()
    cy.wait(1000); // Wait for 1 second before reload (adjust as needed)
    cy.get('#buscarPartidaButton').click()
    cy.wait(2000);
    cy.get('#cancelarColaButton').click()
    cy.get('#logout').click()
  })
})
describe('User can login into the app and check ranking', () => {
  it('User login into the app and check ranking', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('#username').type('nachok')
    cy.get('#password').type('1234')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('http://127.1.5.20:8000/ranking')
    cy.wait(1000)
    cy.get('#logout').click()
  })
})

describe('User login into the app and change somehing on profil', () => {
  it('User login change profil', () => {
    cy.visit('http://127.1.5.20:8000/login')
    cy.get('#username').type('nachok')
    cy.get('#password').type('1234')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('http://127.1.5.20:8000/perfil')
    cy.wait(1000)
    cy.get('#editar_perfil').click()
    cy.get('#user_name').type('nacho Fernandez')
    cy.wait(1000)
    cy.get('#actualizar_perfil_button').click()
    cy.wait(2000)
    cy.get('#logout').click()
  })
})


