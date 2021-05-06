import PerfectScrollbar from 'perfect-scrollbar'


function load() {
    // Set viewport
    let viewport = document.querySelector("meta[name=viewport]");
    viewport.setAttribute('content', 'width=device-width, initial-scale=1, shrink-to-fit=no');

    // Add hidden class to sidebar
    let sidebar = document.querySelector('.w-sidebar');
    sidebar.classList.add("sidebar-hidden");

    // Add hamburger menu to header
    let hamburger = document.createElement("span");
    hamburger.className = 'hamburger-menu';
    let contentHeader = document.querySelector('.content .h-header');
    contentHeader.prepend(hamburger);

    // Hamburger click event
    hamburger.addEventListener("click", function (e) {
        e.stopPropagation();
        let sidebar = document.querySelector('.w-sidebar');
        sidebar.classList.toggle("sidebar-hidden");
    }, true);

    // Sidebar links click event
    let sidebarLinks = document.querySelectorAll('.w-sidebar a, .w-sidebar .cursor-pointer');
    sidebarLinks.forEach(function (sidebarLink) {
        sidebarLink.addEventListener("click", function () {
            let sidebar = document.querySelector('.w-sidebar');
            sidebar.classList.add("sidebar-hidden");
        }, false);
    });

    // Hide sidebar when clicking outside
    let rootElements = document.querySelectorAll('body,html');
    rootElements.forEach(function (rootElement) {
        rootElement.addEventListener("click", function (e) {
            let sidebar = document.querySelector('.w-sidebar');
            if (e.target !== sidebar && !sidebar.contains(e.target)) {
                sidebar.classList.add("sidebar-hidden");
            }
        });
    });

    // Config based theme tweaking
    if (Nova.config.nt) {
        // Hide sidebar headlines
        let sidebarHeadlines = document.querySelectorAll('.w-sidebar h4');
        sidebarHeadlines.forEach(function (sidebarHeadline) {
            if (Nova.config.nt.hide_all_sidebar_headlines
                || Nova.config.nt.hidden_sidebar_headlines.indexOf(sidebarHeadline.textContent) !== -1) {
                sidebarHeadline.classList.add("hidden");
            }
        });

        // Sticky resource table actions
        if (Nova.config.nt.resource_tables_sticky_actions) {
            let contents = document.querySelectorAll('.content');
            contents.forEach(function (content) {
                content.classList.add("sticky-actions");
            });
        }
        if (Nova.config.nt.resource_tables_sticky_actions_on_mobile) {
            let contents = document.querySelectorAll('.content');
            contents.forEach(function (content) {
                content.classList.add("sticky-actions-on-mobile");
            });
        }

        // Hide "Update & Continue Editing" button
        if (Nova.config.nt.hide_update_and_continue_editing_button) {
            let contents = document.querySelectorAll('.content');
            contents.forEach(function (content) {
                content.classList.add("hide-update-and-continue-editing-button");
            });
        }
        if (Nova.config.nt.hide_update_and_continue_editing_button_on_mobile) {
            let contents = document.querySelectorAll('.content');
            contents.forEach(function (content) {
                content.classList.add("hide-update-and-continue-editing-button-on-mobile");
            });
        }

        if (Nova.config.nt.fixed_sidebar) {
            document.querySelector('body').classList.add("fixed-sidebar");

            if(document.querySelector('.sidebar-scroll')){
                const ps = new PerfectScrollbar('.sidebar-scroll');
            }
        }

        if (Nova.config.nt.fixed_navbar) document.querySelector('.content .h-header').classList.add('sticky-navbar');

        if(document.querySelector('#app-loader')) document.getElementById('app-loader').style.display = 'none'
    }
}

document.addEventListener("DOMContentLoaded", load, false);
