import functionThatReturnsNull from './Helpers/functionThatReturnsNull';
import TemplateHelper from './Helpers/TemplateHelper';

const templateHelper = new TemplateHelper();
sessionStorage.setItem('week', '0');
sessionStorage.setItem('city', '');
function changeWeek(direction){
    const week = parseInt(sessionStorage.getItem('week'));
    const nextWeek = direction === 'left' ? week + -1 : week + 1;
    sessionStorage.setItem('week', nextWeek);
    const currCity = sessionStorage.getItem('city') || '';
    templateHelper.getTemplate(`${templateHelper.publicUrl}/calendar?week=${nextWeek}&city=${currCity}`).then(({ html }) => {
        document.querySelector('.calendar').outerHTML = html;
    })
}

function changeCity(city){
    const week = parseInt(sessionStorage.getItem('week'));
    sessionStorage.setItem('city', city);
    templateHelper.getTemplate(`${templateHelper.publicUrl}/calendar?week=${week}&city=${city}`).then(({ html }) => {
        if(html){
            document.querySelector('.calendar').outerHTML = html;
        }
    })
}

document.querySelector('.select_cities').addEventListener('change', (e) => {
    changeCity(e.target.value);
})


window.changeWeek = changeWeek;
window.functionThatReturnsNull = functionThatReturnsNull;