function doRequest(button) {
    return fetch(button.href, {
        method: button.getAttribute('data-method'),
    }).then(response => response);
}

function main() {
    const buttons = document.querySelectorAll('a[data-method]');
    let url = window.location.href;
    let publish_quiz_button_id = "publish_quiz";

    buttons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            // tell the browser not to respond to the link click
            event.preventDefault();

            if (button.id === publish_quiz_button_id) {
                url = "/quiz/admin";
            }

            if (confirm('Are you sure?')) {
                doRequest(button)
                    .then(function (response) {
                        if (response.status !== 200) {
                            console.log("ERROR");
                            // ADD ERROR MESSAGE TO HTML PAGE
                        } else {
                            window.location.replace(url);
                        }
                    })
                    .catch(error => console.log("ERROR"));
            } else {
                window.location.href = url;
            }
        });
    });
}

main();