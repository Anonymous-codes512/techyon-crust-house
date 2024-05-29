function setActiveMenuItem(item) {
    localStorage.setItem('activeMenuItem', item);
}

function getActiveMenuItem() {
    return localStorage.getItem('activeMenuItem');
}

function updateActiveMenuItem() {
    let currentPage = window.location.pathname.split('/').pop();
    let activeElement = document.querySelector('.menuItems.active');
    if (activeElement) {
        activeElement.classList.remove('active');
    }
    let activeMenuItem = getActiveMenuItem();
    if (activeMenuItem) {
        let targetElement = document.getElementById(activeMenuItem);
        if (targetElement) {
            targetElement.classList.add('active');
        }
    }
}

let options = document.querySelectorAll('.menuItems');

options.forEach(element => {
    element.addEventListener('click', (event) => {
        options.forEach(item => {
            item.classList.remove('active');
        });
        event.currentTarget.classList.add('active');
        setActiveMenuItem(event.currentTarget.id);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    let activeMenuItem = getActiveMenuItem();
    if (activeMenuItem) {
        let activeElement = document.getElementById(activeMenuItem);
        if (activeElement) {
            activeElement.classList.add('active');
        }
    }
    updateActiveMenuItem();
});


window.addEventListener('popstate', updateActiveMenuItem);
function toggleMenu() {
    let menu = document.getElementById('menu')
    let icon = document.getElementById('menuIcon')
    if (menu.style.display === 'none') {
        menu.style.display = 'flex'
        icon.classList.remove('bx-menu')
        icon.classList.add('bx-x')
    } else {
        menu.style.display = 'none'
        icon.classList.remove('bx-x')
        icon.classList.add('bx-menu')
    }
}

/*
// Theme color change
function toggleTheme() {
    let themebtn = document.getElementById('theme');
    
    if (themebtn.classList.contains('bx-moon')) {
        setTheme('dark');
        themebtn.classList.remove('bx-moon');
        themebtn.classList.add('bx-sun');
    } else if (themebtn.classList.contains('bx-sun')) {
        setTheme('light');
        themebtn.classList.remove('bx-sun');
        themebtn.classList.add('bx-moon');
    }
}

function setTheme(theme) {

    let header = document.getElementById('header');
    let main = document.getElementById('dashboard')
    let revenue = document.getElementById('revenue')
    let branch = document.getElementById('branch')
    let links = document.getElementsByClassName('link')
    if (theme === 'dark') {
        document.body.style.backgroundColor = '#222831';
        document.body.style.color = '#ffffff'
        header.style.backgroundColor = '#222831';
        main.style.backgroundColor = '#31363F';
        revenue.style.backgroundColor = '#222831'
        branch.style.backgroundColor = '#222831'
        
        Array.from(links).forEach(link => {
            link.style.color = '#ffffff'
        });

    } else if (theme === 'light') {
        document.body.style.backgroundColor = '#ffffff';
        document.body.style.color = '#000000'
        header.style.backgroundColor = '#ffffff';
        main.style.backgroundColor = '#e0e0e0';
        revenue.style.backgroundColor = '#ffffff'
        branch.style.backgroundColor = '#ffffff'

        Array.from(links).forEach(link => {
            link.style.color = '#000000'
        });
    }

    localStorage.setItem('theme', theme);
}

window.onload = function() {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        setTheme('dark');
        document.getElementById('theme').classList.add('bx-sun');
    } else {
        setTheme('light');
        document.getElementById('theme').classList.add('bx-moon');
    }
};
*/

var resizeCanvas = function () {
    var graphContainers = document.querySelectorAll('.orderGraph, .revenueGraph');
    graphContainers.forEach(function (container) {
        var canvas = container.querySelector('canvas');
        var width = container.offsetWidth;
        canvas.style.height = (width * 0.8) + 'px';
    });
};
window.addEventListener('resize', resizeCanvas);
resizeCanvas();

// My Staff
let texts = document.getElementsByClassName('status');
Array.from(texts).forEach(text => {
    if (text.textContent.toLowerCase() === "active") {
        text.style.color = '#3FC28A';
    } else if (text.textContent.toLowerCase() === "inactive") {
        text.style.color = '#F45B69';
    }
});

// Add new Member
function popup() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newRider');

    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

function closePopup() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newRider');

    overlay.style.display = 'none';
    popup.style.display = 'none';
}

// Add new table
function addTable() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newTable');

    overlay.style.display = 'block';
    popup.style.display = 'flex';
}

function closeAddTable() {
    let overlay = document.getElementById('overlay');
    let popup = document.getElementById('newTable');

    overlay.style.display = 'none';
    popup.style.display = 'none';
}

// Dining Table
let avaliable = document.getElementsByClassName('table-avaliablity');
Array.from(avaliable).forEach(avaliablity => {
    if (avaliablity.textContent.toLowerCase() === "avaliable") {
        avaliablity.style.color = '#3FC28A';
    } else if (avaliablity.textContent.toLowerCase() === "not avaliable") {
        avaliablity.style.color = '#F45B69';
    }
});

function showAndHidePswd() {
    let pswd = document.getElementById('password');
    if (pswd.type === 'password') {
        pswd.type = 'text';
    } else {
        pswd.type = 'password';
    }
}

function showAndHideCnfrmPswd() {
    let cnfrmPswd = document.getElementById('cnfrmPswd');
    if (cnfrmPswd.type === 'password') {
        cnfrmPswd.type = 'text';
    } else {
        cnfrmPswd.type = 'password';
    }
}
