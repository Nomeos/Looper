function doRequest(button) {
    return fetch(button.href, {
        method: button.getAttribute('data-method'),
    }).then(response => response);
}

function main() {
    const buttons = document.querySelectorAll('a[data-method]');
    let current_location = window.location.href;

    buttons.forEach(function (button) {
        console.log(button)
        button.addEventListener('click', function (event) {
            // tell the browser not to respond to the link click
            event.preventDefault();
            doRequest(button)
                .then(function (response) {
                    if (response.status !== 200) {
                        console.log("ERROR");
                        // ADD ERROR MESSAGE TO HTML PAGE
                    } else {
                        window.location.replace(current_location);
                    }
                })
                .catch(error => console.log("ERROR"));
        });
    });
}

main();