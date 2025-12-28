import VueSimpleAlert from "vue3-simple-alert";
import store from "../store";
import statusEnum from "../enums/modules/statusEnum";
import orderStatusEnum from "../enums/modules/orderStatusEnum";
import askEnum from "../enums/modules/askEnum";
import taxTypeEnum from "../enums/modules/taxTypeEnum";
import currencyPositionEnum from "../enums/modules/currencyPositionEnum";
import { ref } from "vue";

export default {
    sideDrawerShow: function (id = 'sideDrawer') {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");

        drawerSets?.forEach((drawerSet) => {
            const targetElm = document?.querySelector(drawerSet?.dataset?.drawer);
            drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
            drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
            targetElm?.classList?.add("active");
            drawerSet?.classList?.add("active");
            document.body.style.overflowY = "hidden";
            document?.querySelector(".backdrop")?.classList?.add("active");
        });
    },
    sideDrawerHide: function (id = 'sideDrawer') {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");
        document?.querySelectorAll("#sidebar")?.forEach((closeBtn) => {
            drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
            drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
            document?.querySelector(".backdrop")?.classList?.remove("active");
            document.body.style.overflowY = "auto"
        });
    },

    modalShow: function (id = '.modal') {
        let modalButton = document?.querySelectorAll("[data-modal]");
        modalButton?.forEach((modalBtn) => {
            const modalTarget = document?.querySelector(id);
            modalTarget?.classList?.add("active");
            document.body.style.overflowY = "hidden";
        });
    },

    modalHide: function (id = ".modal") {
        let modalDivs = document?.querySelectorAll(id);
        document.body.style.overflowY = "auto";
        modalDivs?.forEach((modalDiv) => modalDiv?.classList?.remove("active"));
    },

    phoneNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[+]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    onlyNumber: function (e) {
        let res = (e.charCode !== 8 && e.charCode === 0 || (e.charCode >= 48 && e.charCode <= 57));
        if (res)
            return true;
        else
            e.preventDefault();
    },

    floatNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[.]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    currencyFormat(amount, decimal, currency, position) {
        if (position === currencyPositionEnum.LEFT) {
            return currency + parseFloat(amount).toFixed(decimal);
        } else {
            return parseFloat(amount).toFixed(decimal) + currency;
        }
    },
    logoutConfirmation: function () {
        return new VueSimpleAlert.confirm(
            "You will able to log in again using the kiosk machine!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Log Out!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    destroyConfirmation: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to recover the deleted record!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    updateConfirmation: function () {
        return new VueSimpleAlert.confirm(
            "",
            "Are you sure for update?",
            "warning",
            {
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    acceptOrder: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to cancel the order!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Accept it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    cancelOrder: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to accept the order!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Cancel it!",
                cancelButtonText: "No, Cancel",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },

    distance: function (lat1, lng1, lat2, lng2) {
        let radiationLat1 = Math.PI * lat1 / 180
        let radiationLat2 = Math.PI * lat2 / 180
        let theta = lng1 - lng2;
        let radiationTheta = Math.PI * theta / 180
        let distance = Math.sin(radiationLat1) * Math.sin(radiationLat2) + Math.cos(radiationLat1) * Math.cos(radiationLat2) * Math.cos(radiationTheta);
        distance = Math.acos(distance)
        distance = distance * 180 / Math.PI
        distance = distance * 60 * 1.1515
        distance = distance * 1.609344
        return distance;
    },

    recursiveRouter: function (routes, permission) {
        let i, j;
        for (i = 0; i < routes.length; i++) {
            for (j = 0; j < permission.length; j++) {
                if (typeof routes[i].meta !== "undefined" && routes[i].meta) {
                    if (typeof routes[i].meta.permissionUrl !== "undefined" && routes[i].meta.permissionUrl) {
                        if (routes[i].meta.permissionUrl === permission[j].url) {
                            routes[i].meta.access = permission[j].access;
                            routes[i].meta.title = permission[j].title;
                        }

                        if (typeof routes[i].children !== "undefined" && routes[i].children) {
                            this.recursiveRouter(routes[i].children, permission);
                        }
                    }
                }
            }
        }
    },

    textShortener: function (text, number = 30) {
        if (text) {
            if (!(text.length < number)) {
                return text.substring(0, number) + "..";
            }
        }
        return text;
    },
    statusClass: function (status) {
        if (status === statusEnum.ACTIVE) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    orderStatusClass: function (status) {
        if (status == orderStatusEnum.ACCEPT || status == orderStatusEnum.PREPARING) {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#2AC769] bg-[#CBFFE0]";
        }
        else if (status == orderStatusEnum.PENDING) {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#F6A609] bg-[#FFEEC6]";
        }
        else if (status == orderStatusEnum.PREPARED) {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#A953FF] bg-[#F5EAFF]";
        }
        else if (status == orderStatusEnum.OUT_FOR_DELIVERY) {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#008BBA] bg-[#BDEFFF]";
        }
        else if (status == orderStatusEnum.DELIVERED) {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-primary bg-[#FFD7E7]";
        }
        else {
            return "py-0.5 px-2 rounded-full text-[10px] font-rubik leading-4 first-letter:capitalize whitespace-nowrap text-[#FB4E4E] bg-[#FFDADA]";
        }
    },

    askClass: function (ask) {
        if (ask === askEnum.YES) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    taxTypeClass: function (type) {
        if (type === taxTypeEnum.FIXED) {
            return "db-table-badge text-blue-500 bg-blue-100";
        } else {
            return "db-table-badge text-orange-500 bg-orange-100";
        }
    },

    requestHandler: function (requests) {
        let i = 1;
        let what = "?";
        let response = "";

        for (let request in requests) {
            if (requests[request] !== "" && requests[request] !== null) {
                if (i !== 1) {
                    response += "&";
                }
                response += request + "=" + requests[request];
            }
            i++;
        }

        if (response) {
            response = what + response;
        }

        return response;
    },

    responsiveLoad: function () {
        let mainHeader = document?.querySelector(".db-header");
        let subHeader = document?.querySelector(".sub-header");
        let mainHeight = mainHeader?.scrollHeight;

        if (subHeader) {
            subHeader.style.top = `${mainHeight}px`;
        }
    },


    permissionChecker: function (permissionName) {
        let i, permissions = store.getters.authPermission;
        for (i = 0; i < permissions.length; i++) {
            if (typeof permissions[i].name !== "undefined" && permissions[i].name) {
                if (permissions[i].name === permissionName) {
                    return permissions[i].access;
                }
            }
        }
    },

    formDataShow: function (formData) {
        for (let pair of formData.entries()) {
            console.log(pair[0] + " : " + pair[1]);
        }
    },

    // pos customization code starts here
    singleSlideDown: function (dataAttr, attrName, toggleClass) {
        const btnElement = document?.querySelector(dataAttr);
        const tabElement = document?.querySelector(btnElement?.dataset[attrName]);
        document?.addEventListener("click", function (event) {
            if (btnElement && tabElement) {
                if (!btnElement?.contains(event?.target)) {
                    if (!tabElement?.contains(event?.target)) {
                        btnElement?.classList?.remove(toggleClass);
                        tabElement.style.display = "none";
                    }
                } else {
                    btnElement?.classList?.add(toggleClass);
                    tabElement.style.display = `block`;
                }
            }
        });
    },


    singleGroupActive: function (parentClass, addedClass) {
        const singleElements = document?.querySelectorAll(parentClass);

        singleElements?.forEach((singleElement) => {
            for (let i = 0; i < singleElement.childElementCount; i++) {
                singleElement?.children[i]?.addEventListener("click", function () {
                    for (let a = 0; a < singleElement.childElementCount; a++) singleElement?.children[a]?.classList?.remove(addedClass);
                    singleElement?.children[i]?.classList?.add(addedClass);
                })
            }
        })
    },
    //pos customization code ends here

    handleTab: function (event, targetID, targetButton, targetDiv, active) {
        const targetBtns = document.querySelectorAll(targetButton);
        const targetDivs = document.querySelectorAll(targetDiv);
        const currentBtn = event.currentTarget;
        const currentDiv = document.querySelector(targetID);
        targetBtns.forEach(item => item.classList.remove(active));
        targetDivs.forEach(item => item.classList.remove(active));
        currentBtn.classList.add(active);
        currentDiv.classList.add(active);
    },

    //handle tab end here
    openCanvas: function (targetID) {
        const targetElement = document.querySelector(`#${targetID}`);
        targetElement.classList.add('active');
        document.body.classList.add('overflow-hidden');
        document.body.style.overflowY = 'hidden'
    },

    closeCanvas: function (targetID) {
        const targetElement = document.querySelector(`#${targetID}`);
        targetElement.classList.remove('active');
        document.body.classList.remove('overflow-hidden');
        document.body.style.overflowY = 'auto'
    },

    closeBackdrop: function (event) {
        const containerElement = event.currentTarget.firstElementChild
        const isWrapperElement = event.target.contains(containerElement)

        if (isWrapperElement) {
            event.currentTarget.classList.remove('active');
            document.body.classList.remove('overflow-hidden');
            document.body.style.overflowY = 'auto';
        }
    },

    //Handle canvas end here
    handleSlide: function (id) {
        const targetElement = document.querySelector(`#${id}`);

        targetElement.classList.add("transition-all", "duration-300", "ease-in-out");

        if (targetElement.style.visibility == 'visible') {
            targetElement.style.height = '0px';
            targetElement.style.overflow = 'hidden';
            targetElement.style.opacity = '0';
            targetElement.style.visibility = 'hidden';
        }
        else {
            targetElement.style.height = targetElement.scrollHeight + 'px'
            setTimeout(() => {
                targetElement.style.overflow = 'visible';
            }, 300);
            targetElement.style.opacity = '1';
            targetElement.style.visibility = 'visible';
        }
    },

    //Kds filter open close here

    openFilterSlide: function (event) {
        const btn = event.currentTarget
        const options = btn.nextElementSibling;
        const isExpanded = btn.getAttribute("aria-expanded") === "true";
        const checkboxes = document.querySelectorAll(".filter");
        checkboxes.forEach(function (otherBtn) {
            if (otherBtn != btn) {
                const otherOptions = otherBtn.nextElementSibling;
                if (otherBtn.getAttribute("aria-expanded") === "true") {
                    otherOptions.style.height = "0px";
                    otherOptions.style.margin = "0px";
                    otherBtn.querySelector(".icon").classList.remove("fa-chevron-up");
                    otherBtn.querySelector(".icon").classList.add("fa-chevron-down");
                    otherBtn.setAttribute("aria-expanded", "false");
                }
            }
        });

        if (isExpanded) {
            options.style.height = "0px";
            options.style.margin = "0px";
            btn.querySelector(".icon").classList.remove("fa-chevron-up");
            btn.querySelector(".icon").classList.add("fa-chevron-down");
        } else {
            options.style.height = `${options.scrollHeight}px`;
            options.style.margin = "8px 0px 0px 0px";
            btn.querySelector(".icon").classList.remove("fa-chevron-down");
            btn.querySelector(".icon").classList.add("fa-chevron-up");
        }

        btn.setAttribute("aria-expanded", !isExpanded);

    },

    closeFilterSlide: function (event) {
        const filterBtns = document.querySelectorAll('.filter');
        if (!event.target.closest(".filter")) {
            filterBtns.forEach(function (btn) {
                const options = btn.nextElementSibling;
                if (btn.getAttribute("aria-expanded") === "true") {
                    options.style.height = "0px";
                    options.style.margin = "0px";
                    btn.querySelector(".icon").classList.remove("fa-chevron-up");
                    btn.querySelector(".icon").classList.add("fa-chevron-down");
                    btn.setAttribute("aria-expanded", "false");
                }
            });
        }
    },

    // open setting menu

    openSettingMenu: function (event) {
        const btn = event.currentTarget;
        const options = btn.nextElementSibling;
        const isExpanded = btn.getAttribute("aria-expanded") === "true";
        document.querySelectorAll(".settings-btn").forEach((otherBtn) => {
            if (otherBtn !== btn && otherBtn.getAttribute("aria-expanded") === "true") {
                const otherOptions = otherBtn.nextElementSibling;
                otherOptions.style.height = "0px";
                otherOptions.style.margin = "0px";
                otherBtn.querySelector(".icon").classList.remove("fa-chevron-up");
                otherBtn.querySelector(".icon").classList.add("fa-chevron-down");
                otherBtn.setAttribute("aria-expanded", "false");
            }
        });

        if (isExpanded) {
            options.style.height = "0px";
            options.style.margin = "0px";
            btn.querySelector(".icon").classList.remove("fa-chevron-up");
            btn.querySelector(".icon").classList.add("fa-chevron-down");
        } else {
            options.style.height = "auto";
            const pixel = options.scrollHeight;
            options.style.height = "0px";
            requestAnimationFrame(() => {
                options.style.height = `${pixel}px`;
                options.style.margin = "8px 0px 0px 0px";
            });

            btn.querySelector(".icon").classList.remove("fa-chevron-down");
            btn.querySelector(".icon").classList.add("fa-chevron-up");
        }

        btn.setAttribute("aria-expanded", !isExpanded);
    },

    closeSettingMenu: function (event) {
        if (!event.target.closest(".settings-btn")) {
            document.querySelectorAll(".settings-btn").forEach((btn) => {
                if (btn.getAttribute("aria-expanded") === "true") {
                    const options = btn.nextElementSibling;
                    options.style.height = "0px";
                    options.style.margin = "0px";
                    btn.querySelector(".icon").classList.remove("fa-chevron-up");
                    btn.querySelector(".icon").classList.add("fa-chevron-down");
                    btn.setAttribute("aria-expanded", "false");
                }
            });
        }
    },

    openPosCart: function (targetID) {
        const targetElement = document.querySelector(`#${targetID}`);
        targetElement.classList.add('active');
    },

    closePosCart: function (targetID) {
        const targetElement = document.querySelector(`#${targetID}`);
        targetElement.classList.remove('active');
    },

};
