// =============== !! Important for the buttons to work !!  =============== //
window.navigateTo = function(url) {
    window.location.href = url;
};

// =============== Hamburger menu only Inside  =============== //
class NavigationHamburgerMenu extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div class="off-screen-menu" id="menu">
            <ul>
                <li><button class="menu-btn" onclick="navigateTo('home.html')">Home</button></li>
                <li><button class="menu-btn" onclick="navigateTo('qrcode.html')">QR Code</button></li>
                <li><button class="menu-btn" onclick="navigateTo('qrscanner.html')">QR Scanner</button></li>
                <li><button class="menu-btn" onclick="navigateTo('logs.html')" /disabled>Logs</button></li>
                <li><button class="menu-btn" onclick="navigateTo('about.html')" /disabled>About</button></li>
                <li><button class="menu-btn" onclick="navigateTo('admin.html')" /disabled>Admin</button></li>
            </ul>
        </div>
        `;
    }
}

customElements.define ('hamburger-menu', NavigationHamburgerMenu)

// ===============  Outside  =============== //
class HeaderGuest extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <nav class="navoutside">
            <button id="homeButton" class="unreg" onclick="navigateTo('../index.html')">Home</button>
            <button id="aboutButton" class="unreg" onclick="navigateTo('outside/about.html')">About</button>
        </nav>
        `;

        const currentPath = window.location.pathname;
        if (currentPath.includes('index.html')) {
            const homeButton = document.getElementById('homeButton');
            homeButton.disabled = true;
            homeButton.classList.add('navBarUnregOn');
        } else if (currentPath.includes('about.html')) {
            const aboutButton = document.getElementById('aboutButton');
            aboutButton.disabled = true;
            aboutButton.classList.add('navBarUnregOn');
        }
    }
}

class FooterGuest extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <footer>
            <hr>
            <div class="ul-footer-container">
                <ul>
                    <li><h3>Navigate</h3></li>
                    <li><p><a href="index.html">Home</a></p></li>
                    <li><p><a href="indexabout.html">About</a></p></li>
                </ul>
                <ul>
                    <li><h3>Follow</h3></li>
                    <li><p>asd</p></li>
                </ul>
                <ul>
                    <li><h3>Contact</h3></li>
                    <li><p>asd</p></li>
                </ul>
                <ul>
                    <li><h3>About</h3></li>
                    <li><p>asd</p></li>
                </ul>
            </div>
            <div class="end-footer-container">
                <h6>All Rights Reserved.</h6>
                <h6>Safe Secured Students</h6>
                <h6>2024, Made from Lagro High School</h6>
            </div>
        </footer>
        `;
    }
}

customElements.define('header-guest', HeaderGuest);
customElements.define('footer-guest', FooterGuest);

// =============== Inside  =============== //
class HeaderRegistered extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <nav class="navinside">
            <div class="navleft">
                <img src="../assets/logo.png" alt="logo" class="logonav">
                <p>Lagro High School<br>VisiScan</p>
            </div>
            <div class="navright">
                <button class="nav-menu-btn" id="menuBtn" onclick="toggleMenu()"><img src="../assets/icons/menu.svg" alt="Menu"></button>
            </div>
        </nav>
        `;
    }
}

function toggleMenu() {
    let menu = document.getElementById("menu");

    if (menu.style.display === "none" || menu.style.display === "") {
        menu.style.display = "block";
        menuBtn.classList.add("opened"); 
    } else {
        menu.style.display = "none";
        menuBtn.classList.remove("opened");
    }
}

class FooterRegistered extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <footer>
            <hr>
            <div class="ul-footer-container">
                <ul>
                    <li><h3>Navigate</h3></li>
                    <li><p><a href="../index.html">Index/Home</a></p></li>
                    <li><p><a href="../indexabout.html">Index/About</a></p></li>
                    <li><p><a href="home.html">Home</a></p></li>
                    <li><p><a href="qrcode.html">Qr Code</a></p></li>
                    <li><p><a href="../#">none</a></p></li>
                    <li><p><a href="../#">none</a></p></li>
                </ul>
                <ul>
                    <li><h3>Follow</h3></li>
                    <li><p>asd</p></li>
                </ul>
                <ul>
                    <li><h3>Contact</h3></li>
                    <li><p>asd</p></li>
                </ul>
                <ul>
                    <li><h3>About</h3></li>
                    <li><p>asd</p></li>
                </ul>
            </div>
            <div class="end-footer-container">
                <h6>All Rights Reserved.</h6>
                <h6>Safe Secured Students</h6>
                <h6>2024, Made from Lagro High School</h6>
            </div>
        </footer>
        `;
    }
}

customElements.define ('header-registered', HeaderRegistered);
customElements.define ('footer-registered', FooterRegistered)
