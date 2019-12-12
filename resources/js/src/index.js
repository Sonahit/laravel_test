import functionThatReturnsNull from "./Helpers/functionThatReturnsNull";
import TemplateHelper from "./Helpers/TemplateHelper";

const templateHelper = new TemplateHelper();
sessionStorage.setItem("week", "0");
sessionStorage.setItem("city", "");
function changeWeek(direction) {
    const week = parseInt(sessionStorage.getItem("week"));
    const nextWeek = direction === "left" ? week + -1 : week + 1;
    sessionStorage.setItem("week", nextWeek);
    const currCity = sessionStorage.getItem("city") || "";
    templateHelper
        .getTemplate(
            `${templateHelper.publicUrl}/calendar?week=${nextWeek}&city=${currCity}`
        )
        .then(({ html, bookingInterval }) => {
            document.querySelector(".calendar").outerHTML = html;
            initRows(parseInt(bookingInterval));
        });
}

function changeCity(city) {
    const week = parseInt(sessionStorage.getItem("week"));
    sessionStorage.setItem("city", city);
    templateHelper
        .getTemplate(
            `${templateHelper.publicUrl}/calendar?week=${week}&city=${city}`
        )
        .then(({ html, bookingInterval }) => {
            if (html) {
                document.querySelector(".calendar").outerHTML = html;
                sessionStorage.setItem('bookingInterval', bookingInterval);
                initRows(bookingInterval);
            }
        });
}

function highLightRow(row, parent, bookingInterval) {
    if (!row.classList.contains("disabled")) {
        row.classList.toggle("hovered");
        let nextRow = null;
        for (let index = 0; index < bookingInterval; index++) {
            if (nextRow !== null) {
                if (nextRow.nextElementSibling !== null) {
                    nextRow = nextRow.nextElementSibling;
                }
            } else {
                nextRow = row.nextElementSibling;
            }
            if (nextRow !== null) nextRow.classList.toggle("hovered");
        }
    }
}

document.querySelector(".select_cities").addEventListener("change", e => {
    changeCity(e.target.value);
});

function initRows(
    bookingInterval = parseInt(sessionStorage.getItem("bookingInterval"))
) {
    document.querySelectorAll(".calendar__rows").forEach(el => {
        Array.from(el.children).forEach(row => {
            const isDisabled = Array.from(row.children).some(e =>
                e.classList.contains("disabled")
            );
            if (isDisabled) {
                row.classList.toggle("disabled");
            }
        });
        el.addEventListener("mouseover", e => {
            const { target, currentTarget } = e;
            const header = Array.from(
                currentTarget.parentNode.children
            ).find(el => el.classList.contains("calendar__header"));
            if (header.classList.contains("disabled")) return;
            if (target.classList.contains("calendar__row") && !target.classList.contains("booked")) {
                highLightRow(target, target.parentNode, bookingInterval);
            }
        });
        el.addEventListener("mouseout", e => {
            const { target, currentTarget } = e;
            const header = Array.from(
                currentTarget.parentNode.children
            ).find(el => el.classList.contains("calendar__header"));
            if (header.classList.contains("disabled")) return;
            if (target.classList.contains("calendar__row") && !target.classList.contains("booked")) {
                highLightRow(target, target.parentNode, bookingInterval);
            }
        });
    });
}

initRows();
window.changeWeek = changeWeek;
window.functionThatReturnsNull = functionThatReturnsNull;
