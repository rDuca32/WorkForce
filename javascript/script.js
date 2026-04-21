// Validare text pentru pagina de Login

function validateText(event) {
    const limit = /^[0-9a-z]+$/;

    const input = event.target;
    const dot = input.nextElementSibling;

    if (input.value === "") {
        dot.style.backgroundColor = "gray";
    } else if (limit.test(input.value)) {
        dot.style.backgroundColor = "green";
    } else {
        dot.style.backgroundColor = "red";
    }
}

const usernameInput = document.querySelector("#username");
if (usernameInput) {
    usernameInput.addEventListener('input', validateText);
}

const passwordInputLogin = document.querySelector("#password");
if (passwordInputLogin) {
    passwordInputLogin.addEventListener('input', validateText);
}



// Validare parola

function validatePassword(event) {
    const input = event.target;
    const password = input.value;
    const dot = input.nextElementSibling;

    const hasSmallLetter = /[a-z]/.test(password);
    const hasCapitalLetter = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasExclamationMark = /[!]/.test(password);

    if (password === "") {
        dot.style.backgroundColor = "gray";
    } else if (hasSmallLetter && hasCapitalLetter && hasNumber && hasExclamationMark) {
        dot.style.backgroundColor = "green";
    } else {
        dot.style.backgroundColor = "red";
    }
}

const passwordRegister = document.querySelector("#password_register");
if (passwordRegister) {
    passwordRegister.addEventListener('input', validatePassword);
}

const passwordRegisterConfirm = document.querySelector("#password_confirmation");
if (passwordRegisterConfirm) {
    passwordRegisterConfirm.addEventListener('input', validatePassword);
}



// Validare email

function validateEmail(event) {
    const input = event.target;
    const email = input.value;
    const dot = input.nextElementSibling;

    const limit = /^[0-9a-zA-Z_]+@[0-9a-zA-Z_]+(\.[0-9a-zA-Z_]+)+$/;

    if (email === "") {
        dot.style.backgroundColor = "gray";
    } else if (limit.test(email)) {
        dot.style.backgroundColor = "green";
    } else {
        dot.style.backgroundColor = "red";
    }
}

const emailRegister = document.querySelector("#email");
if (emailRegister) {
    emailRegister.addEventListener('input', validateEmail);
}



// Validare telefon

function validatePhone(event) {
    const input = event.target;
    const phone = input.value;
    const dot = input.nextElementSibling;

    const limit = /\(\+40\) [0-9]{3} [0-9]{3} [0-9]{3}$/;

    if (phone === "") {
        dot.style.backgroundColor = "gray";
    } else if (limit.test(phone)) {
        dot.style.backgroundColor = "green";
    } else {
        dot.style.backgroundColor = "red";
    }
}

const phoneRegister = document.querySelector("#phone");
if (phoneRegister) {
    phoneRegister.addEventListener('input', validatePhone);
}



// Validare data

function checkDateFormat(date, format) {
    const dataParts = date.split("/");
    const formatParts = format.split("/");

    if (dataParts.length !== 3) {
        return false;
    }

    let day, month, year;
    let isYearValid = true;

    formatParts.forEach((p, i) => {
        const partValue = dataParts[i];

        if (p === "zz") day = parseInt(partValue, 10);
        if (p === "ll") month = parseInt(partValue, 10);
        if (p === "aaaa" || p === "aa") {
            year = parseInt(partValue, 10);
            if (partValue.length !== p.length) {
                isYearValid = false;
            }
        }
    });

    if (!isYearValid) {
        return false;
    }

    if (format.includes('aa') && !format.includes('aaaa')) {
        year += 2000;
    }

    const d = new Date(year, month - 1, day);
    return d.getFullYear() === year && d.getMonth() === month - 1 && d.getDate() === day;
}

function validateDate(event) {
    const input = event.target;
    const date = input.value;
    const dot = input.nextElementSibling;

    const acceptedFormats = ['zz/ll/aaaa', 'zz/ll/aa', 'll/zz/aaaa', 'll/zz/aa'];

    let isValid = false;

    for (let format of acceptedFormats) {
        if (checkDateFormat(date, format)) {
            isValid = true;
            break;
        }
    }

    if (date === "") {
        dot.style.backgroundColor = "gray";
    } else if (isValid) {
        dot.style.backgroundColor = "green";
    } else {
        dot.style.backgroundColor = "red";
    }
}

const dateRegister = document.querySelector("#date");
if (dateRegister) {
    dateRegister.addEventListener('input', validateDate);
}



// Pentru validarea tuturor formularelor

function validateFormOnSubmit(event) {
    event.preventDefault();
    const form = event.target;

    if (form.querySelector("#username")) validateText({ target: form.querySelector("#username") });
    if (form.querySelector("#password")) validateText({ target: form.querySelector("#password") });

    if (form.querySelector("#password_register")) validatePassword({ target: form.querySelector("#password_register") });
    if (form.querySelector("#password_confirmation")) validatePassword({ target: form.querySelector("#password_confirmation") });
    if (form.querySelector("#email")) validateEmail({ target: form.querySelector("#email") });
    if (form.querySelector("#phone")) validatePhone({ target: form.querySelector("#phone") });
    if (form.querySelector("#date")) validateDate({ target: form.querySelector("#date") });

    const dots = form.querySelectorAll(".status-dot");
    let allDotsValid = true;

    dots.forEach(dot => {
        if (dot.style.backgroundColor !== "green") {
            allDotsValid = false;
        }
    });

    const ageInput = form.querySelector("#age");
    const confirmCheckbox = form.querySelector("#confirm");

    let isAgeValid = true;
    let isConfirmValid = true;
    let matchingPasswords = true;

    if (ageInput) {
        const age = ageInput.value;
        if (age < 18 || age > 70 || age === "") {
            isAgeValid = false;
        }
    }

    if (confirmCheckbox) {
        isConfirmValid = confirmCheckbox.checked;
    }

    if (form.querySelector("#password_confirmation")) {
        const password = form.querySelector("#password_register").value;
        const passwordConfirm = form.querySelector("#password_confirmation").value;

        if (password !== passwordConfirm) {
            matchingPasswords = false;
        }
    }

    if (allDotsValid && isAgeValid && isConfirmValid && matchingPasswords) {
        alert("Succes!");
        // form.submit();
    } else {
        alert("Eroare!");
    }

}



// Judete si localitati

const locations = {
    "Cluj": ["Cluj-Napoca", "Turda", "Dej"],
    "Bistrița-Năsăud": ["Bistrița", "Napoca", "Beclean"],
    "Dolj": ["Craiova"],
    "Bucureşti": ["Bucureşti"],
    "Braşov": ["Braşov", "Făgăraș", "Săcele", "Zărnești"],
    "Alba": ["Alba Iulia"],
    "Argeș": ["Piteși"],
    "Timiș": ["Timișoara", "Lugoj", "Sânnicolau Mare"]
}

// Dropdown pentru judete si localitati register.html

function dropdowns() {
    const countySelect = document.querySelector("#county");
    const citySelect = document.querySelector("#city");

    if (!countySelect || !citySelect) {
        return;
    }

    for (let county in locations) {
        let option = document.createElement("option");
        option.value = county;
        option.textContent = county;
        countySelect.appendChild(option);
    }

    countySelect.addEventListener("change", function (event) {
        const countySelected = event.target.value;

        citySelect.innerHTML = '<option value="">Alege Orașul</option>';

        if (countySelected && locations[countySelected]) {
            citySelect.disabled = false;

            const cities = locations[countySelected];

            for (let city of cities) {
                let option = document.createElement("option");
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            }
        } else {
            citySelect.innerHTML = '<option value="">Alege județul prima dată</option>';
            citySelect.disabled = true;
        }
    });
}

dropdowns();



// Sortare Tabele

function compareRows(rowA, rowB, index, sortDirection) {
    let cellA = rowA.querySelectorAll('td')[index].textContent.trim();
    let cellB = rowB.querySelectorAll('td')[index].textContent.trim();

    let isNumeric = false;
    if (!isNaN(cellA) && !isNaN(cellB) && cellA !== "" && cellB !== "") {
        isNumeric = true;
    }

    if (isNumeric === true) {
        let numA = parseFloat(cellA);
        let numB = parseFloat(cellB);

        if (sortDirection === "asc") {
            if (numA > numB) return 1;
            if (numA < numB) return -1;
            return 0;
        } else {
            if (numA > numB) return -1;
            if (numA < numB) return 1;
            return 0;
        }
    } else {
        if (sortDirection === "asc") {
            return cellA.localeCompare(cellB, 'ro');
        } else {
            return cellB.localeCompare(cellA, 'ro');
        }
    }
}

function makeTablesSortable() {
    const tables = document.querySelectorAll('table');

    tables.forEach(table => {
        const hasSpans = table.querySelector('[colspan], [rowspan]');

        if (hasSpans) {
            return;
        }

        const headers = table.querySelectorAll('th');

        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';

            header.sortDirection = "not-sorted";

            header.addEventListener('click', () => {
                if (header.sortDirection === "not-sorted" || header.sortDirection === "desc") {
                    header.sortDirection = "asc";
                } else {
                    header.sortDirection = "desc";
                }

                const rows = Array.from(table.querySelectorAll('tr'));

                rows.shift(); // Eliminam linia de header din tabel

                if (rows.length === 0) {
                    return;
                }

                const parent = rows[0].parentNode; // Parintele linilor din tabel

                rows.sort((rowA, rowB) => compareRows(rowA, rowB, index, header.sortDirection)); // Sortarea linilor

                rows.forEach(function (row) {
                    parent.appendChild(row);
                });
            });
        });
    });
}

makeTablesSortable();



// Slider Lista

const listItems = document.querySelectorAll("#content-list li");
const prevButton = document.querySelector("#prev-button");
const nextButton = document.querySelector("#next-button");

if (listItems.length > 0 && prevButton && nextButton) {

    let currentIndex = 0;
    const n = 3000;
    let autoSlideTimer;

    function showItem(index) {
        listItems[currentIndex].classList.remove("active");
        currentIndex = (index + listItems.length) % listItems.length;
        listItems[currentIndex].classList.add("active");
    }

    function showNext() {
        showItem(currentIndex + 1);
        resetTimer();
    }

    function showPrev() {
        showItem(currentIndex - 1);
        resetTimer();
    }

    function resetTimer() {
        clearInterval(autoSlideTimer);
        autoSlideTimer = setInterval(showNext, n);
    }

    nextButton.addEventListener("click", showNext);
    prevButton.addEventListener("click", showPrev);

    autoSlideTimer = setInterval(showNext, n);

}



// Slider Imagini 

const imageSlides = document.querySelectorAll(".slider-main .slide");
const thumbnails = document.querySelectorAll(".thumbnail-link");
const playPauseButton = document.querySelector("#play-pause-button");
const repeatCheckbox = document.querySelector("#repeat-checkbox");
const intervalSelect = document.querySelector("#interval-select");

if (imageSlides.length > 0 && playPauseButton && repeatCheckbox && intervalSelect) {
    let currentImageIndex = 0;
    let isImagePlaying = false;
    let imageSlideTimer;

    function showImage(index) {
        imageSlides[currentImageIndex].classList.remove("active-slide");
        thumbnails[currentImageIndex].classList.remove("active-thumbnail");

        currentImageIndex = index;

        imageSlides[currentImageIndex].classList.add("active-slide");
        thumbnails[currentImageIndex].classList.add("active-thumbnail");
    }

    function showNextImage() {
        if (currentImageIndex === imageSlides.length - 1) {
            if (repeatCheckbox.checked) {
                showImage(0);
            }
            else {
                stopImagesSlideshow();
            }
        }
        else {
            showImage(currentImageIndex + 1);
        }
    }

    function startImageSlideshow() {
        if (currentImageIndex === imageSlides.length - 1 && !repeatCheckbox.checked) {
            showImage(0);
        }

        const interval = parseInt(intervalSelect.value, 10);

        imageSlideTimer = setInterval(showNextImage, interval);
        isImagePlaying = true;

        playPauseButton.textContent = "Pauză";
        playPauseButton.style.backgroundColor = "red";
    }

    function stopImagesSlideshow() {
        clearInterval(imageSlideTimer);
        isImagePlaying = false;

        playPauseButton.textContent = "Rulează";
        playPauseButton.style.backgroundColor = "green";
    }

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener("click", (event) => {
            event.preventDefault();

            if (isImagePlaying) {
                stopImagesSlideshow();
            }

            showImage(index);
        });

        thumbnail.addEventListener("mouseenter", () => {
            if (isImagePlaying) {
                stopImagesSlideshow();
            }

            showImage(index);
        });
    });

    playPauseButton.addEventListener("click", () => {
        if (isImagePlaying) {
            stopImagesSlideshow();
        }
        else {
            startImageSlideshow();
        }
    });

    intervalSelect.addEventListener("change", () => {
        if (isImagePlaying) {
            stopImagesSlideshow();
            startImageSlideshow();
        }
    });
}