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
                <li><button class="menu-btn" onclick="navigateTo('home.php')">Home</button></li>
                <li><button class="menu-btn" onclick="navigateTo('qrcode.php')">QR Code</button></li>
                <li><button class="menu-btn" onclick="navigateTo('log.php')">Logs</button></li>
                <li><button class="menu-btn" onclick="navigateTo('about.html')" /disabled>About</button></li>
            </ul>
        </div>
        `;
    }
}

customElements.define('hamburger-menu', NavigationHamburgerMenu);

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
            <h2>VisiScan</h2><br>
            <div class="ul-footer-container">
                <ul>
                    <li><h3>Navigate</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p><a href="../index.html">Home</a></p></li>
                    <li><p><a href="outside/about.html">About</a></p></li>
                </ul>
                <ul>
                    <li><h3>Follow Us</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p>Contact Us</p></li>
                    <li><p>Help Center</p></li>
                    <li><p>FAQ</p></li>
                </ul>
                <ul>
                    <li><h3>Contact Us</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p>Facebook</p></li>
                    <li><p>Twitter</p></li>
                    <li><p>Instagram</p></li>
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

// for Profile
document.addEventListener("DOMContentLoaded", function () {
    // Fetch user's profile picture from PHP
    fetch("get_profile_picture.php")
        .then(response => response.json())
        .then(data => {
            const profilePic = document.querySelector(".profile-pic");
            if (profilePic && data.profile_picture) {
                profilePic.src = data.profile_picture;
            }
        })
        .catch(error => console.error("Error loading profile picture:", error));

    // Update profile picture after uploading a new one
    const form = document.getElementById("uploadForm");
    if (form) {
        form.addEventListener("submit", function (event) {
            setTimeout(() => {
                const profilePic = document.querySelector(".profile-pic");
                if (profilePic) {
                    profilePic.src = profilePic.src.split("?")[0] + "?" + new Date().getTime();
                }
            }, 1000);
        });
    }
});

// ===============  Inside  =============== //
class HeaderRegistered extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <nav class="navinside">
            <div class="navleft">
                <img src="../assets/logo.png" alt="logo" class="logonav">
                <p>Lagro High School<br>VisiScan</p>
            </div>
            <div class="navright">
                <button class="nav-menu-btn" id="menuBtn" onclick="toggleMenu()">
                    <img src="../assets/icons/menu.svg" alt="Menu">
                </button>
                <button class="nav-menu-btn" onclick="navigateTo('viewprofile.php')">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <img src='../assets/icons/user.svg' alt='Profile'>
                    <h3>Profile</h3>
                </button>
                <button class="nav-menu-btn" id="logoutBtn" onclick="logout()">
                    <img src='../assets/icons/log-out.svg' alt='Logout'>
                    <h3>Logout</h3>
                </button>
            </div>
        </nav>
        `;
    }
}

class FooterRegistered extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <footer>
            <hr>
            <h2>VisiScan</h2><br>
            <div class="ul-footer-container">
                <ul>
                    <li><h3>Navigate</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p><a href="home.php">Home</a></p></li>
                    <li><p><a href="about.php">About Us</a></p></li>
                    <li><p><a href="qrcode.php">QR Code</a></p></li>
                    <li><p><a href="Logs.php">Logs</a></p></li>
                </ul>
                <ul>
                    <li><h3>Follow Us</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p>Contact Us</p></li>
                    <li><p>Help Center</p></li>
                    <li><p>FAQ</p></li>
                </ul>
                <ul>
                    <li><h3>Contact Us</h3></li>
                    <hr align="left" style="width: 124px;">
                    <li><p>Facebook</p></li>
                    <li><p>Twitter</p></li>
                    <li><p>Instagram</p></li>
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

customElements.define('header-registered', HeaderRegistered);
customElements.define('footer-registered', FooterRegistered);

// ===============  Admin  =============== //
class HeaderRegisteredAdmin extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <nav class="navinside">
            <div class="navleft">
                <img src="../assets/logo.png" alt="logo" class="logonav">
                <p>Lagro High School<br>VisiScan</p>
            </div>
            <div class="navright">
                <button class="nav-menu-btn" id="menuBtnAdmin" onclick="toggleMenuAdmin()"><img src="../assets/icons/menu.svg" alt="Menu"></button>
                <button class="nav-menu-btn" id="logoutBtn" onclick="logout()"><img src='../assets/icons/log-out.svg' alt='Logout'><h3>Logout</h3></button>
            </div>
        </nav>
        `;
    }
}

class NavigationHamburgerMenuAdmin extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <div class="off-screen-menu" id="menuAdmin">
            <ul>
                <li><button class="menu-btn" onclick="navigateTo('admin.php')">Admin Panel</button></li>
                <li><button class="menu-btn" onclick="navigateTo('qrscanner.php')">QR Scanner</button></li>
                <li><button class="menu-btn" onclick="navigateTo('adminlog.php')">Entry Logs</button></li>
                <li><button class="menu-btn" onclick="navigateTo('manageaccount.php')">Account Management</button></li>
                <li><button class="menu-btn" onclick="navigateTo('about.html')" /disabled>About</button></li>
            </ul>
        </div>
        `;
    }
}

customElements.define('header-registered-admin', HeaderRegisteredAdmin);
customElements.define('hamburger-menu-admin', NavigationHamburgerMenuAdmin);

// ===============  Functions  =============== //
function toggleMenu() {
    let menu = document.getElementById("menu");
    let menuBtn = document.getElementById("menuBtn");

    if (menu.classList.contains("open")) {
        menu.classList.remove("open");
        menuBtn.classList.remove("opened");
    } else {
        menu.classList.add("open");
        menuBtn.classList.add("opened");
    }
}

function toggleMenuAdmin() {
    let menuAdmin = document.getElementById("menuAdmin");
    let menuBtnAdmin = document.getElementById("menuBtnAdmin");

    if (menuAdmin.classList.contains("open")) {
        menuAdmin.classList.remove("open");
        menuBtnAdmin.classList.remove("opened");
    } else {
        menuAdmin.classList.add("open");
        menuBtnAdmin.classList.add("opened");
    }
}

function logout() {
    window.location.href = '../form/logout.php';
}
