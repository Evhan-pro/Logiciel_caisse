//Create modify button for each card
const modifs = document.querySelectorAll('.modify')
modifs.forEach(function(modif) {
    modif.addEventListener('click', function(event) {
        const forms = document.querySelectorAll('.formModify')
        const card = modif.parentElement.parentElement
        forms.forEach(function(form) {
            let otherCard = form.parentElement

            otherCard.appendChild(otherCard.querySelector('.clientId'))
            form.parentElement.querySelector('.modify').style.display = ''
            form.parentElement.querySelector('.delete').style.display = ''
            form.remove()

        })

        card.querySelector('.clientName').style.display = 'none'
        card.querySelector('.clientAddress').style.display = 'none'
        card.querySelector('.clientEmail').style.display = 'none'
        card.querySelector('.clientPhone').style.display = 'none'
        modif.style.display = 'none'
        modif.parentElement.querySelector('.delete').style.display = 'none'



        const form = document.createElement("form")
        form.action = 'inc/clientModify.php'
        form.method = 'POST'
        form.style.display = 'flex'
        form.style.flexDirection = 'column'
        form.classList.add('formModify')
        modif.parentElement.parentElement.appendChild(form)

        /* const idInput = document.createElement("input")
        idInput.value =  */

        const imgInput = document.createElement("input");
        imgInput.name = 'img'
        imgInput.type = 'file';

        const names = document.createElement("div")
        names.style.display = 'flex'


        const lastNameInput = document.createElement("input");
        lastNameInput.value = card.querySelector('.lastName').innerHTML;
        lastNameInput.name = 'lastName'
        lastNameInput.placeholder = 'Nom';

        const firstNameInput = document.createElement("input");
        firstNameInput.value = card.querySelector('.firstName').innerHTML;
        firstNameInput.name = 'firstName'
        firstNameInput.placeholder = 'PrÃ©nom';

        names.append(lastNameInput, firstNameInput)

        const addressInput = document.createElement("input");
        addressInput.value = card.querySelector(".address").innerHTML;
        addressInput.name = 'address'
        addressInput.placeholder = 'Adresse';

        const emailInput = document.createElement("input");
        emailInput.value = card.querySelector(".email").innerHTML;
        emailInput.name = 'email'
        emailInput.placeholder = 'Email';

        const phoneInput = document.createElement("input");
        phoneInput.value = card.querySelector(".phone").innerHTML;
        phoneInput.name = 'phone'
        phoneInput.placeholder = 'TÃ©lÃ©phone';

        //Create form buttons
        const buttons = document.createElement("div")
        buttons.style.display = 'flex'

        const confirm = document.createElement("button")
        confirm.type = 'submit'
        confirm.innerHTML = 'confirmer'

        const cancel = document.createElement("button")
        cancel.type = 'button'
        cancel.innerHTML = 'Annuler'
        cancel.addEventListener('click', function(event) {
            event.preventDefault
            card.appendChild(card.querySelector('.clientId'))
            form.remove()
            card.querySelector('.clientName').style.display = ''
            card.querySelector('.clientAddress').style.display = ''
            card.querySelector('.clientEmail').style.display = ''
            card.querySelector('.clientPhone').style.display = ''
            modif.style.display = ''
            card.querySelector('.delete').style.display = ''
        })

        buttons.append(confirm, cancel)
        form.append(card.querySelector('.clientId'), imgInput, names, addressInput, emailInput, phoneInput, buttons);
    })
})

//Create delete button
const dels = document.querySelectorAll('.delete')
dels.forEach(function(del) {
    del.addEventListener('click', function(event) {
        const card = del.parentElement.parentElement

        const forms = document.querySelectorAll('.formModify')
        forms.forEach(function(form) {
            let otherCard = form.parentElement

            otherCard.appendChild(otherCard.querySelector('.clientId'))

            form.parentElement.querySelector('.modify').style.display = ''
            form.parentElement.querySelector('.delete').style.display = ''
            form.remove()

        })

        del.style.display = 'none'
        del.parentElement.querySelector('.modify').style.display = 'none'

        const form = document.createElement("form")
        form.action = 'inc/clientDelete.php'
        form.method = 'POST'
        form.classList.add('formModify')
        form.style.display = 'flex'

        del.parentElement.parentElement.appendChild(form)


        const confirm = document.createElement('button')
        confirm.type = 'submit'
        confirm.innerHTML = 'Confirmer'

        const cancel = document.createElement('button')
        cancel.type = 'button'
        cancel.innerHTML = 'Annuler'
        cancel.addEventListener('click', function(event) {
            card.appendChild(card.querySelector('.clientId'))
            form.remove()
            del.style.display = ''
            del.parentElement.querySelector('.modify').style.display = ''
        })
        console.log(card.querySelector('.clientId'))

        form.append(card.querySelector('.clientId'), confirm, cancel)
    })
})