"use strict";

// /* Top bar scrolling start */
document.addEventListener('DOMContentLoaded', () => {
    const dbMain = document.querySelector('.db-main');
    const subHeader = document.querySelector('.sub-header');
    const dbHeader = document.querySelector('.db-header');

    if (dbMain) {
        let headerSize = dbMain.offsetWidth;
        if (headerSize < 736) {
            if (subHeader) {
                subHeader.style.top = `${dbHeader.scrollHeight}px`;
            }

            dbMain.addEventListener('scroll', () => {
                let scroll = dbMain.scrollTop;
                if (scroll === 0) {
                    subHeader.style.display = 'block';
                } else {
                    subHeader.style.display = 'none';
                }
            });
        }
    }
});


// /* Variation start */

document.addEventListener('click', function (event) {
    if (event.target.matches('.size-tabs label')) {
        document.querySelectorAll('.size-tabs label').forEach((label) => {
            label.classList.remove('active');
        });
        event.target.classList.add('active');
    }
});

document.addEventListener('click', function (event) {
    if (event.target.closest('.extra-swiper .extra')) {
        const extra = event.target.closest('.extra-swiper .extra');
        const input = extra.querySelector('input');
        if (input) {
            input.checked = !input.checked;
            extra.classList.toggle('active', input.checked);
        }
    }
});

document.addEventListener('click', function (event) {
    if (event.target.closest('.addon-swiper .addon')) {
        const addon = event.target.closest('.addon-swiper .addon');
        addon.classList.toggle('active');
    }
});
// /* Variation end */

// /* Other button tab stat */
document.querySelectorAll('.other-tabBtn').forEach(function (tabBtn) {
    tabBtn.addEventListener('click', function () {
        document.querySelectorAll('.other-tabBtn').forEach(function (btn) {
            btn.classList.remove('active');
            const tab = btn.getAttribute('data-tab');
            if (tab) {
                const tabElement = document.querySelector(tab);
                if (tabElement) {
                    tabElement.classList.remove('active');
                }
            }
        });

        this.classList.add('active');
        const dataTab = this.getAttribute('data-tab');
        if (dataTab) {
            const tabElement = document.querySelector(dataTab);
            if (tabElement) {
                tabElement.classList.add('active');
            }
        }
    });
});
// /* Other button tab end */

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('db-tab-btn')) {
        document.querySelectorAll('.db-tab-btn').forEach((btn) => {
            btn.classList.remove('active');
            const tabSelector = btn.getAttribute('data-tab');
            if (tabSelector) {
                const tab = document.querySelector(tabSelector);
                if (tab) {
                    tab.classList.remove('active');
                }
            }
        });
        event.target.classList.add('active');
        const dataTab = event.target.getAttribute('data-tab');
        if (dataTab) {
            const tab = document.querySelector(dataTab);
            if (tab) {
                tab.classList.add('active');
            }
        }
    }
});
// /* db tab button end */

// /* POS responsive start */

document.querySelectorAll('.db-pos-cartBtn').forEach(function (cartBtn) {
    cartBtn.addEventListener('click', function () {
        const cartDiv = document.querySelector('.db-pos-cartDiv');
        if (!cartDiv.classList.contains('active')) {
            cartDiv.classList.add('active');
        } else {
            cartDiv.classList.remove('active');
        }
    });
});

document.querySelectorAll('.db-pos-cartCls').forEach(function (cartCls) {
    cartCls.addEventListener('click', function () {
        document.querySelector('.db-pos-cartDiv').classList.remove('active');
    });
});

// /* POS responsive close */


// /* Message code start */
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('close-the-image-file')) {
        event.target.parentElement.remove();
        document.querySelector('.chat-footer-data-list')?.classList.add('hidden');
    }
});

// /* Message code end */


// /* Installer menu active code start */
const handleLinkForInstaller = (param) => {
    window.location.replace(param);
};

document.querySelectorAll('.close-alert-button').forEach(function (button) {
    button.addEventListener('click', function () {
        this.parentElement.remove();
    });
});

// /* Installer menu active code close */


// /* oss part start */

function handleMouseMove(event) {
    let headerDiv = document.querySelector(".db-header");
    let main = document.querySelector("main");
    let display = document.querySelector('.customer-screen');

    if (event.clientY <= 100) {
        headerDiv.classList.remove("active", "hidden");
        main.classList.remove("hiddenHeader");
        display.classList.add('mt-[78px]');
    } else {
        headerDiv.classList.add("active", "hidden");
        main.classList.add("hiddenHeader");
        display.classList.remove('mt-[78px]');
    }
}

function handleExitFullscreen() {
    let main = document.querySelector("main");
    let display = document.querySelectorAll('.customer-screen');
    let header = document.querySelector('.db-header');
    const elementDbCustomerMain = document?.querySelector(".db-main-customer");

    if (elementDbCustomerMain) {
        elementDbCustomerMain.classList.remove("db-main-customer", "customer-display");
        elementDbCustomerMain.classList.add("db-main");
        elementDbCustomerMain.classList.remove("hiddenHeader");
    }

    if (header) {
        header.classList.remove('active', 'hidden');
    }

    if (main) {
        main.classList.add('mt-[78px]');
    }

    display.forEach(function (d) {
        d.classList.add('md:h-[calc(100vh-117px)]');
        d.classList.remove('md:h-[calc(100vh-50px)]');
    });

    document.removeEventListener('mousemove', handleMouseMove);
}

document.addEventListener('keydown', function (event) {
    if (event.key === "Escape" || event.keyCode === 27 || event.keyCode === 18 || event.keyCode === 91) {
        event.preventDefault();
        if (document.fullscreenElement) {
            document.exitFullscreen().catch(err => {
                console.error('Error exiting fullscreen:', err);
            });
        }
        handleExitFullscreen();
    }
});

document.addEventListener('fullscreenchange', function () {
    if (!document.fullscreenElement) {
        handleExitFullscreen();
    }
});

// /* oss part  end */

// /* Pos Calculator Part  start */
function solve(val, id) {
    var v = document.getElementById(id);
    v.value += val;
}
function Clear(id) {
    var inp = document.getElementById(id);
    inp.value = '';
}
function Back(id) {
    var ev = document.getElementById(id);
    ev.value = ev.value.slice(0, -1);
}

// /* Pos Calculator Part  End */

// /* Pos Keyboard Part  start */
let changesht = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", '⇧', "a", "s", "d", "f", "g", "h", "j", "k", '←', "l", "z", "x", "c", "v", "b", "n", "m"]
let originalsht = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", '⇧', "A", "S", "D", "F", "G", "H", "J", "K", '←', "L", "Z", "X", "C", "V", "B", "N", "M"]
function backspace(id) {
    let textBoard = document.getElementById(id)
    textBoard.value = textBoard.value.slice(0, textBoard.value.length - 1)
}

const evaluateClick = (e) => {
    let btnclicked = e.target.classList[0]
    if (btnclicked != "board" && btnclicked != "rows") {
        let btnText = e.target.innerText
        let btnId = e.target.parentElement.parentElement.querySelector('input').getAttribute('id')
        action(btnText, btnId)
    }
}

function shift(btnId) {
    let btn = document.getElementById(btnId).parentElement.parentElement.querySelector('.shifter')
    if (btn?.classList.contains("noshift")) {
        shifton(changesht, btnId)
    } else {
        shifton(originalsht, btnId)
    }
}


function shifton(change, btnId) {
    let shift = document.getElementById(btnId).parentElement.parentElement.querySelector('.shifter');
    shift?.classList.toggle("noshift");
    let btnchng = document.getElementById(btnId).parentElement.parentElement.querySelectorAll(".cng");
    Array.from(btnchng).forEach((value, index) => {
        value.innerText = change[index]
    });
}

const action = (btnText, btnId) => {
    switch (btnText) {
        case '←':
            backspace(btnId)
            break
        case "⇧":
            shift(btnId)
            break
        default:
            setText(btnText, btnId)
    }
}

const setText = (text, id) => {
    const element = document.getElementById(id);
    const cursorPosition = element.selectionStart;
    const currentText = element.value;
    element.value = currentText.substring(0, cursorPosition) + text + currentText.substring(cursorPosition);
    element.selectionStart = element.selectionEnd = cursorPosition + text.length;
    element.focus();
};

let boards = document.querySelectorAll(".board");
const createkeyboard = (mfs) => {
    const mfsdiv = document.getElementById(mfs)
    const divboard = mfsdiv.lastElementChild
    divboard.addEventListener("click", evaluateClick)
    if (divboard.childElementCount == 0) {
        originalsht.map(sht => {
            const div = document.createElement('div')
            div.classList.add('btnr', 'cng')
            if (sht == '⇧') {
                div.classList.add('noshift', 'row-span-2', 'col-span-1', 'shifter')
                div.textContent = sht
            }
            else if (sht == '←') {
                div.classList.add('row-span-2', 'col-span-1')
                div.textContent = sht
            }
            else {
                div.textContent = sht
            }
            divboard.appendChild(div)
        })
    }

}

// /* Pos Keyboard Part  End */