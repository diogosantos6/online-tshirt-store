const cModal = document.getElementById('confirmationModal')
const cForm = document.getElementById('confirmationModalForm')
cModal.addEventListener('show.bs.modal', event => {
    let action = (event.relatedTarget.getAttribute("data-action") ?? "").trim()
    let method = (event.relatedTarget.getAttribute("data-formMethod") ?? "").trim()
    let title = (event.relatedTarget.getAttribute("data-title") ?? "").trim()
    let msgLine1 = (event.relatedTarget.getAttribute("data-msgLine1") ?? "").trim()
    let msgLine2 = (event.relatedTarget.getAttribute("data-msgLine2") ?? "").trim()
    let buttonText = (event.relatedTarget.getAttribute("data-confirmationButton") ?? "").trim()
    if (action) {
        cForm.action = action
    }
    if (method) {
        cForm._method.value = method
    }
    if (title) {
        document.getElementById('confirmationModalTitleLabel').textContent = title
    }
    if (msgLine1) {
        document.getElementById('confirmationModalMsgLine1').innerHTML = msgLine1
    }
    if (msgLine2) {
        document.getElementById('confirmationModalMsgLine2').innerHTML = msgLine2
    }
    if (buttonText) {
        document.getElementById('confirmationModalButton').textContent = buttonText
    }
})
