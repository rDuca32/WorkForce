// Validare text

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

const password = document.querySelector("#password");
if (password) {
    password.addEventListener('input', validatePassword);
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

    if (form.querySelector("#password")) validatePassword({ target: form.querySelector("#password") });
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

    const confirmCheckbox = form.querySelector("#confirm");

    let isConfirmValid = true;

    let matchingPasswords = true;

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

    if (allDotsValid && isConfirmValid && matchingPasswords) {
        alert("Succes!");
        form.submit();
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

    // definim intervalul si inaltimea imaginilor
    let sliderInterval;
    const imgHeight = 300;

    function initVerticalSlider() {
        // curatam intervalul anterior
        clearInterval(sliderInterval);

        // selectam valorile din campurile definite de utilizator
        const visibleCount = parseInt($('#imgCount').val());
        const durationSeconds = parseInt($('#speedCount').val());
        const duration = durationSeconds * 1000;

        // facem sliderul de dimensiunea a cate imagini avem seletate
        $('#vertical-slide-container').css('height', (visibleCount * imgHeight) + 'px');

        function moveUp() {
            const wrapper = $('#images-wrapper');
            const firstChild = wrapper.children().first();

            // animam prima imagine (cea care pleaca) sa se micsoreze si sa dspara
            firstChild.css({
                'transition': 'all 0.5s',
                'opacity': '0',
                'transform': 'scale(0.8) translateY(-50px)'
            });

            // animam miscarea intregului corp in sus pe 0.5 secunde
            // prevenim acumularea animatiilor la multe clickuri rapide
            wrapper.stop(true, true).animate({ top: -imgHeight }, 500, function () {
                wrapper.append(firstChild); // primul copil in punel la capat, adica imaginea care a iesit din cadru

                // resetam imaginea pentru urmatoarea ei aparitie
                firstChild.css({
                    'transition': 'none',
                    'opacity': '1',
                    'transform': 'none'
                });

                wrapper.css('top', '0'); // resetam pozitia la inceput
            });
        }

        function moveDown() {
            const wrapper = $('#images-wrapper');
            const lastChild = wrapper.children().last();

            // pregatim ultima imagine sa apara sus cu efect
            lastChild.css({
                'opacity': '0',
                'transform': 'scale(0.8) translateY(50px)'
            });

            wrapper.prepend(lastChild); // punem inainte ultimul copil, adica ultima imagine
            wrapper.css('top', -imgHeight + 'px'); // modificam pozitia pentru a incapea noul element

            // animam revenirea listei in sus
            wrapper.stop(true, true).animate({ top: 0 }, 500);

            // readucem imaginea la normal printr-o tranzitie
            lastChild.css('transition', 'all 0.5s').css({
                'opacity': '1',
                'transform': 'none'
            });
        }

        // pornim intervalul incepand si functia dupa numarul de secunde din duration
        sliderInterval = setInterval(moveUp, duration);

        // legam sagetile de actiunile corespunzatoare
        // mai intai curatam instructiunea anterioara sa nu se acumuleze
        $('#next-arrow').off('click').on('click', moveDown);
        $('#prev-arrow').off('click').on('click', moveUp);

        $('#vertical-slide-container').off('mouseenter mouseleave').hover(
            // curatam intervalul cand dam hover pe container
            function () {
                clearInterval(sliderInterval);
            },

            // repornim intervalul cand scoatem hoverul
            function () {
                sliderInterval = setInterval(moveUp, duration);
            }
        )
    }

    // cand dam start la slider dupa ce am setat valorile acesta se initializeaza sau reinitializeaza
    $('#startSlider').on('click', function (event) {
        event.preventDefault();
        initVerticalSlider();
    })

    // initializam intervalul
    initVerticalSlider();
})

/* Filtrare si cautare in tabel */

$(document).ready(function () {
    $('#searchInput, .filter-radio, .filter-checkbox').on('input change', function () {
        let searchText = $('#searchInput').val().toLowerCase(); // selectam valoarea pentru search

        let radioLocation = $('.filter-radio:checked').val(); // selectam radiou-ul apasat
        if (radioLocation) {
            radioLocation = radioLocation.toLowerCase();
        }

        let checkedStatuses = []
        $('.filter-checkbox:checked').each(function () {
            checkedStatuses.push($(this).val()); // adaugam fiecare checkbox bifat
        })

        // parcurgem fiecare rand din tabelul principal
        $('.main-table > tbody > tr').each(function () {
            let $row = $(this); // alegem randul curent

            // luam pentru fiecare valorile corespunzatoare pe baza coloanelor
            let colWorksites = $row.find('> td').eq(1).text().toLowerCase();
            let colLocations = $row.find('> td').eq(2).text().toLowerCase();
            let colTasks = $row.find('> td').eq(4).text();

            // definim variabilele de corespondenta
            let matchesSearct = colWorksites.includes(searchText) || colLocations.includes(searchText);
            let matchesRadio = (radioLocation === "toate" || colLocations.includes(radioLocation));
            let matchesCheckbox = true;

            // parcurgem fiecare status ca sa putem face selectia doar pentru cele adevarate
            $.each(checkedStatuses, function (index, status) {
                if (!colTasks.includes(status)) {
                    matchesCheckbox = false;
                }
            })

            // verificam si afisam randul care indeplineste toate conditiile
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

        // masuri de preventie pentru click pe casuta din tabel care are alta actiune
        event.preventDefault();
        event.stopPropagation();

        // clonam poza si scoatem clasa pentru a lasa sa se extinda
        let $clonedImg = $(this).clone();
        $clonedImg.removeClass('table-img');

        // clonam informatia din datele ascunse
        let targetId = $(this).attr('data-target');
        let $clonedInfo = $(targetId).clone();

        // scoatem clasa care o ascunde si o facem sa se afiseze corespunzator
        $clonedInfo.removeClass('hidden-info').css('display', 'block');

        // golim pop-up si adaugam imaginea si descrierea ascunsa cu animatie
        $('#popup-content-area').empty().append($clonedImg).append($clonedInfo);
        $('#popup-overlay').css('display', 'flex').hide().fadeIn(500);
    })

    // la click pe butonul x inchidem cu animatie pop-up si golim informatia din acesta
    $('#popup-close').on('click', function () {
        $('#popup-overlay').fadeOut(500, function () {
            $('#popup-content-area').empty();
        })
    })

    // cand dam click pe langa de asemenea inchidem
    $('#popup-overlay').on('click', function (event) {
        if (event.target === this) {
            // apelam functia de deasupra
            $('#popup-close').click();
        }
    })
})

/* Calculator 

$(document).ready(function () {
    let conversionRate = 5; // backup
    let currentMoneyType = "RON";

    // apelam un API public pentru conversia de valuta
    $.getJSON('https://open.er-api.com/v6/latest/EUR', function (data) {
        if (data && data.rates && data.rates.RON) {
            conversionRate = data.rates.RON;
            getTotal();
        }
    });

    // materiale predefinite
    const materials = [
        { name: "Beton C25/30", price: 350, unit: "mc" },
        { name: "Cărămidă Porotherm", price: 5, unit: "buc" },
        { name: "Oțel Beton", price: 25, unit: "kg" },
        { name: "Vopsea Lavabilă", price: 120, unit: "găleată" },
        { name: "Ciment Multibat", price: 28, unit: "sac" },
        { name: "Glet Meseriaș", price: 45, unit: "sac" },
        { name: "Polistiren 10cm", price: 85, unit: "pachet" },
        { name: "Adeziv Polistiren", price: 32, unit: "sac" },
        { name: "Placă Rigips 12.5mm", price: 40, unit: "foaie" },
        { name: "Țiglă Metalică", price: 55, unit: "mp" },
        { name: "Cherestea Rășinoase", price: 1200, unit: "mc" },
        { name: "Nisip Sortat", price: 90, unit: "mc" }
    ];

    // initializam variabila de selectare a materialului si punem prima optiune de alegere
    let $select = $('#calc-material');
    $select.append('<option value="0"> Alege material </option>');

    // pentru fiecare material din lista predefinita adaugam optiunea impreuna cu pretul per unitate
    $.each(materials, function (index, material) {
        $select.append(`<option value="${material.price}"> ${material.name} (${material.price} RON / ${material.unit})</option>`);
    });

    function getTotal() {

        // extragem pretul, cantitatea si daca e apasat checkboxul de urgenta
        let price = parseFloat($('#calc-material').val()) || 0;
        let quantity = parseInt($('#calc-quantity').val()) || 0;
        let urgent = $('#calc-urgent').is(':checked');

        // pentru livrare gratuita
        const baseThreshold = 5000;
        let freeShippingThreshold = baseThreshold;

        // daca suntem pe euro convertim pragul de afișare
        if (currentMoneyType === "EUR") {
            freeShippingThreshold = baseThreshold / conversionRate;
        }

        // forteaza tipul de valuta care e intre taguri strong sa se interschimbe
        $('#calc-total').next('strong').text(currentMoneyType);

        // daca pretul sau cantitatea e zero atunci punem 0 la total
        if (price == 0 || quantity == 0) {
            $('#calc-total').text("0.00");
            $('#discount-message').slideUp(200);

            // resetam bara de progres pentru livrare gratis
            $('#shipping-bar').css('width', '0%');
            $('#shipping-info').text(`Mai adaugă ${freeShippingThreshold.toLocaleString('ro-RO', { maximumFractionDigits: 2 })} ${currentMoneyType} pentru livrare gratuită.`);

            return;
        }

        // definim totalul
        let total = price * quantity;

        // daca cantitatea depaseste 50 bucati aplicam 10% reducere
        if (quantity > 50) {
            total = total - (total * 0.1);

            // daca nu avem mesajul deja vizibil afisam cu animatie
            if (!$('#discount-message').is(':visible')) {
                $('#discount-message').slideDown(200);
            }
        } else {
            $('#discount-message').slideUp(200);
        }

        // crestem pretul daca e urgent
        if (urgent) {
            total += 100;
        }

        // definim ce vrem sa afisam
        let showTotal = total;

        // pentru tipul euro facem conversia
        if (currentMoneyType === "EUR") {
            showTotal = total / conversionRate;
        }

        // animam schimbarea pretului cu doua zecimale
        $('#calc-total').stop(true, true).fadeOut(100, function () {
            $(this).text(showTotal.toLocaleString('ro-RO', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })).fadeIn(100);

            // adaugam valuta in elementul strong
            $(this).next('strong').text(currentMoneyType);
        });

        // calculam procentajul atins pana la discount
        let percentage = (showTotal / freeShippingThreshold) * 100;

        // nu sarim peste limita
        if (percentage > 100) {
            percentage = 100;
        }

        // animam bara
        $('#shipping-bar').css('width', percentage + '%');

        // actualizăm textul informativ
        if (showTotal >= freeShippingThreshold) {
            $('#shipping-info').html('<span style="color: green; font-weight: bold;"> Livrare gratuită</span>');
            $('#shipping-bar').css('background', 'forestgreen');
        } else {
            let remaining = freeShippingThreshold - showTotal;
            $('#shipping-info').text(`Mai adaugă ${remaining.toLocaleString('ro-RO', { maximumFractionDigits: 2 })} ${currentMoneyType} pentru livrare gratuită`);
            $('#shipping-bar').css('background', 'var(--primary-color)');
        }
    }

    // apelam functia cand se face o schimbare in campurile definite
    $('#calc-material, #calc-quantity, #calc-urgent').on('input change', getTotal);

    // functia de conversie
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

    // la click pe buton facem conversia
    $('#btn-currency').on('click', changeMoneyType);
})

*/

/* Functionalitate editare si finalizare task 

$(document).ready(function () {
    // selectam butoanele cu acele clase
    let $editButton = $('.editTask');
    let $finishButton = $('.finishTask');

    function editTask() {
        // luam cardurile cele mai apropiate de butoane
        const $card = $(this).closest('.card');

        // selectam butonul si ce vrem sa modificam
        const $button = $(this);
        const $title = $card.find('h2');
        const $description = $card.find('p');
        const $status = $card.find('.card-tag');

        // adaugam proprietatea
        const editing = $button.data('editing');

        if (!editing) {
            // facem casutele editabile
            $title.attr('contenteditable', 'true');
            $description.attr('contenteditable', 'true');

            // luam statusul curent
            const currentStatus = $status.text().trim();

            // cream dropdown cu valoarea selectata din status curent
            const $select = $('<select>').html(`
                <option>Urgent</option>
                <option>Normal</option>
                <option>Lejer</option>
                <option>Optional</option>
            `).val(currentStatus);

            $status.html('').append($select);
            $button.text('Salvează').data('editing', true)
        } else {
            // scoatem editabilitate
            $title.removeAttr('contenteditable');
            $description.removeAttr('contenteditable');
            $status.removeAttr('contenteditable');

            // luam statusul selectat
            const selectedStatus = $status.find('select').val();
            $status.text(selectedStatus);

            // daca statusul contine textul urgent il facem rosu
            if (selectedStatus.toLowerCase().includes('urgent')) {
                $card.addClass('priority-high');
            } else {
                $card.removeClass('priority-high');
            }

            $button.text('Editează').data('editing', false)
        }
    }

    function finishTask() {
        const $card = $(this).closest('.card');

        // la finalizare umplet bara si facem textul de status in finalizat
        $card.find('.progress-bar').css('width', '100%');
        $card.find('.card-tag').html('Finalizat');
        $card.removeClass('priority-high');

        // ascundem butonul de editare
        $card.find('.editTask').hide();
    }

    // mapam functiile pe butoane
    $editButton.on('click', editTask);
    $finishButton.on('click', finishTask);
})

*/

/* AJAX */

/* AJAX: Trimitere Recenzie */

$(document).ready(function() {
    $('#review-form').on('submit', function(e) {
        e.preventDefault(); 
        let feedback = $('#feedback-text').val();
        
        $.ajax({
            url: 'save_review.php',
            type: 'POST',
            data: { feedback: feedback },
            success: function(response) {
                $('#review-message').html(response); 
                $('#feedback-text').val(''); 
            },
            error: function() {
                $('#review-message').html("<span style='color: red;'>Eroare de conexiune la server.</span>");
            }
        });
    });
});


 /*  AJAX: Calculator de Materiale */

$(document).ready(function () {
    let conversionRate = 5; 
    let currentMoneyType = "RON";

    // API conversie valuta
    $.getJSON('https://open.er-api.com/v6/latest/EUR', function (data) {
        if (data && data.rates && data.rates.RON) {
            conversionRate = data.rates.RON;
            getTotal();
        }
    });

    function getTotal() {
        let materialId = $('#calc-material').val(); // Acum ia ID-ul din PHP
        let quantity = parseInt($('#calc-quantity').val()) || 0;
        let isUrgent = $('#calc-urgent').is(':checked');

        const baseThreshold = 5000;
        let freeShippingThreshold = currentMoneyType === "EUR" ? baseThreshold / conversionRate : baseThreshold;

        $('#calc-total').next('strong').text(currentMoneyType);

        // Reset daca nu avem date
        if (!materialId || quantity == 0) {
            $('#calc-total').text("0.00");
            $('#discount-message').slideUp(200);
            $('#shipping-bar').css('width', '0%');
            $('#shipping-info').text(`Mai adaugă ${freeShippingThreshold.toLocaleString('ro-RO', { maximumFractionDigits: 2 })} ${currentMoneyType} pentru livrare gratuită.`);
            return;
        }

        // Animatie discount frontend
        if (quantity > 50) {
            if (!$('#discount-message').is(':visible')) $('#discount-message').slideDown(200);
        } else {
            $('#discount-message').slideUp(200);
        }

        // APELUL AJAX CATRE BACKEND-UL PHP
        $.ajax({
            url: 'calculate.php',
            type: 'POST',
            data: {
                material_id: materialId,
                quantity: quantity,
                urgent: isUrgent
            },
            success: function(response) {
                // response este totalul (ex: 245.50) in RON calculat de PHP
                let totalRon = parseFloat(response);
                let showTotal = currentMoneyType === "EUR" ? totalRon / conversionRate : totalRon;

                // Animatie schimbare pret
                $('#calc-total').stop(true, true).fadeOut(100, function () {
                    $(this).text(showTotal.toLocaleString('ro-RO', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })).fadeIn(100);
                    $(this).next('strong').text(currentMoneyType);
                });

                // Update bara de livrare gratuita
                let percentage = (showTotal / freeShippingThreshold) * 100;
                if (percentage > 100) percentage = 100;

                $('#shipping-bar').css('width', percentage + '%');

                if (showTotal >= freeShippingThreshold) {
                    $('#shipping-info').html('<span style="color: green; font-weight: bold;"> Livrare gratuită</span>');
                    $('#shipping-bar').css('background', 'forestgreen');
                } else {
                    let remaining = freeShippingThreshold - showTotal;
                    $('#shipping-info').text(`Mai adaugă ${remaining.toLocaleString('ro-RO', { maximumFractionDigits: 2 })} ${currentMoneyType} pentru livrare gratuită`);
                    $('#shipping-bar').css('background', 'var(--primary-color)');
                }
            },
            error: function() {
                $('#calc-total').text('Eroare conexiune');
            }
        });
    }

    // Declansatoare (Triggers)
    $('#calc-material, #calc-quantity, #calc-urgent').on('input change', getTotal);

    // Buton de conversie RON / EUR
    $('#btn-currency').on('click', function () {
        if (currentMoneyType === "RON") {
            currentMoneyType = "EUR";
            $(this).html('<i class="fa-solid fa-money-bill-transfer"></i> Schimbă în RON');
        } else {
            currentMoneyType = "RON";
            $(this).html('<i class="fa-solid fa-money-bill-transfer"></i> Schimbă în EURO');
        }
        getTotal(); // Reface calculul (si apelul AJAX) cu noua valuta
    });
});