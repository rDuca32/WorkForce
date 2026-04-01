const form = document.querySelector(".login_form");

form.addEventListener('input', (event) => {
    if (event.target.tagName === "INPUT") {
        const limit = /^[0-9a-z]+$/;

        const input = event.target;
        const dot = input.nextElementSibling;

        if (input.value === ""){
            dot.style.backgroundColor = "gray";
        } else if (limit.test(input.value)) {
            dot.style.backgroundColor = "green";
        } else {
            dot.style.backgroundColor = "red";
        }
    }
})