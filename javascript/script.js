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

const usernameInput = document.getElementById("username");
if (usernameInput) {
    usernameInput.addEventListener('input', validateText);
}

const passwordInputLogin = document.getElementById("password");
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

    countySelect.addEventListener("change", (event) => {
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

                const parent = rows[0].parentNode; // Parintele linilor din tabel, adica tbody (este facut automat chiar daca nu e scris in HTML)

                rows.sort((rowA, rowB) => compareRows(rowA, rowB, index, header.sortDirection)); // Sortarea linilor

                rows.forEach(row => parent.appendChild(row));
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
        autoSlideTimer = setInterval(showNext, n); // showNext se va executa dupa n secunde
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

        imageSlideTimer = setInterval(showNextImage, interval); // showNextImage se va apela dupa interval secunde
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
            event.preventDefault(); // sa nu sara pe pagina de la elementul ancora

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

const clickableTownInfos = document.querySelectorAll(".clickable")

clickableTownInfos.forEach(town => town.addEventListener("click", function (event) {
    const row = this.closest("tr");

    const cells = row.querySelectorAll(".inner-table:first-of-type tr td:last-child");

    let total = 0;

    cells.forEach(cell => {
        const val = parseInt(cell.textContent.trim())
        if (!isNaN(val)) {
            total += val;
        }
    })

    const worksite = this.innerText.split('\n')[0].trim();

    alert(`${worksite} are: ${total} muncitori`);
}
))

/* JQUERY */

/* Slider Vertical */

$(document).ready(function () {
    let sliderInterval;
    const imgHeight = 300;

    function initVerticalSlider() {
        clearInterval(sliderInterval);

        const visibleCount = parseInt($('#imgCount').val());
        const durationSeconds = parseInt($('#speedCount').val());
        const duration = durationSeconds * 1000;

        $('#vertical-slide-container').css('height', (visibleCount * imgHeight) + 'px');

        function moveUp() {
            const wrapper = $('#images-wrapper');

            wrapper.stop(true, true).animate({ top: -imgHeight }, 500, function () {
                wrapper.append(wrapper.children().first());
                wrapper.css('top', '0');
            });
        }

        function moveDown() {
            const wrapper = $('#images-wrapper');

            wrapper.prepend(wrapper.children().last());
            wrapper.css('top', -imgHeight + 'px');

            wrapper.stop(true, true).animate({ top: 0 }, 500);
        }

        sliderInterval = setInterval(moveUp, duration);

        $('#next-arrow').off('click').on('click', moveDown);
        $('#prev-arrow').off('click').on('click', moveUp);

        $('#vertical-slide-container').off('mouseenter mouseleave').hover(
            function () {
                clearInterval(sliderInterval);
            },

            function () {
                sliderInterval = setInterval(moveUp, duration);
            }
        )
    }

    $('#startSlider').on('click', function (event) {
        event.preventDefault();
        initVerticalSlider();
    })

    initVerticalSlider();
})

/* Filtrare si cautare in tabel */

$(document).ready(function () {
    $('#searchInput, .filter-radio, .filter-checkbox').on('input change', function () {
        let searchText = $('#searchInput').val().toLowerCase();

        let radioLocation = $('.filter-radio:checked').val();
        if (radioLocation) {
            radioLocation = radioLocation.toLowerCase();
        }

        let checkedStatuses = []
        $('.filter-checkbox:checked').each(function () {
            checkedStatuses.push($(this).val());
        })

        $('.main-table > tbody > tr').each(function () {
            let $row = $(this);

            let colWorksites = $row.find('> td').eq(1).text().toLowerCase();
            let colLocations = $row.find('> td').eq(2).text().toLowerCase();
            let colTasks = $row.find('> td').eq(4).text();

            let matchesSearct = colWorksites.includes(searchText) || colLocations.includes(searchText);
            let matchesRadio = (radioLocation === "toate" || colLocations.includes(radioLocation));
            let matchesCheckbox = true;

            $.each(checkedStatuses, function (index, status) {
                if (!colTasks.includes(status)) {
                    matchesCheckbox = false;
                }
            })

            if (matchesSearct && matchesRadio && matchesCheckbox) {
                $row.show();
            } else {
                $row.hide();
            }
        })
    })
})

/* Pop-up */

$(document).ready(function () {
    $('.table-img').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let $clonedImg = $(this).clone();
        $clonedImg.removeClass('table-img');

        let targetId = $(this).attr('data-target');
        let $clonedInfo = $(targetId).clone();
        $clonedInfo.removeClass('hidden-info').css('display', 'block');

        $('#popup-content-area').empty().append($clonedImg).append($clonedInfo);
        $('#popup-overlay').css('display', 'flex').hide().fadeIn(500);
    })

    $('#popup-close').on('click', function () {
        $('#popup-overlay').fadeOut(500, function () {
            $('#popup-content-area').empty();
        })
    })

    $('#popup-overlay').on('click', function (event) {
        if (event.target === this) {
            $('#popup-close').click();
        }
    })
})

/* Calculator */

$(document).ready(function () {
    let conversionRate = 5;
    let currentMoneyType = "RON";

    $.getJSON('https://open.er-api.com/v6/latest/EUR', function(data) {
        if(data && data.rates && data.rates.RON) {
            conversionRate = data.rates.RON;
            getTotal();
        }
    });

    const materials = [
        { name: "Beton C25/30", price: 350, unit: "mc" },
        { name: "Cărămidă Porotherm", price: 5, unit: "buc" },
        { name: "Oțel Beton", price: 25, unit: "kg" },
        { name: "Vopsea Lavabilă", price: 120, unit: "găleată" }
    ];

    let $select = $('#calc-material');
    $select.append('<option value="0"> Alege material </option>');

    $.each(materials, function (index, material) {
        $select.append(`<option value="${material.price}"> ${material.name} (${material.price} RON / ${material.unit})</option>`);
    });

    function getTotal() {
        let price = parseFloat($('#calc-material').val()) || 0;
        let quantity = parseInt($('#calc-quantity').val()) || 0;
        let urgent = $('#calc-urgent').is(':checked');

        $('#calc-total').next('strong').text(currentMoneyType);

        if (price == 0 || quantity == 0) {
            $('#calc-total').text("0");
            $('#discount-message').slideUp(200);
            return;
        }

        let total = price * quantity;

        if (quantity > 50) {
            total = total - (total * 0.1);

            if (!$('#discount-message').is(':visible')) {
                $('#discount-message').slideDown(200);
            }
        } else {
            $('#discount-message').slideUp(200);
        }

        if (urgent) {
            total += 100;
        }

        let showTotal = total;
        let symbol = "RON"

        if (currentMoneyType === "EUR") {
            showTotal = total / conversionRate;
            symbol = "EUR"
        }

        $('#calc-total').stop(true, true).fadeOut(100, function() {
            $(this).text(showTotal.toLocaleString('ro-RO', { 
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })).fadeIn(100);
            
            $(this).next('strong').text(currentMoneyType);
        });
    }

    $('#calc-material, #calc-quantity, #calc-urgent').on('input change', getTotal);

    function changeMoneyType() {
        if (currentMoneyType === "RON") {
            currentMoneyType = "EUR";
            $(this).html('<i class="fa-solid fa-money-bill-transfer"></i> Schimbă în RON');
        } else {
            currentMoneyType = "RON";
            $(this).html('<i class="fa-solid fa-money-bill-transfer"></i> Schimbă în EURO');
        }
        getTotal();
    }

    $('#btn-currency').on('click', changeMoneyType);
})