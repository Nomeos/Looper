
const button = document.querySelector('#delete1');

button.addEventListener('click', function (event) {
    //tell the browser not to respond to the link click
    event.preventDefault();

    return fetch(button.href, {
        method: button.getAttribute('data-method'),
    }).then(response => alert(response));
});
