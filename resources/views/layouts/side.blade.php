<div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
    <div class="brand-logo">
        <a href="index.html">
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
            <h5 class="logo-text">KNN Regression House</h5>
        </a>
    </div>
    <ul class="sidebar-menu do-nicescrol">
        <li class="sidebar-header">MAIN</li>
        <li>
            <a href="/" class="sidebar-link">
                <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
                <span class="hover-effect"></span>
            </a>
        </li>

        <li>
            <a href="/training" class="sidebar-link">
                <i class="zmdi zmdi-invert-colors"></i> <span>Data Training</span>
                <span class="hover-effect"></span>
            </a>
        </li>

        <li>
            <a href="/testing" class="sidebar-link">
                <i class="zmdi zmdi-format-list-bulleted"></i> <span>Data Testing</span>
                <span class="hover-effect"></span>
            </a>
        </li>

        {{-- <li>
        <a href="/result" class="sidebar-link">
            <i class="zmdi zmdi-grid"></i> <span>Result</span>
            <span class="hover-effect"></span>
        </a>
    </li> --}}
    </ul>

    <style>
        /* Base Styles */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            position: relative;
        }

        .sidebar-menu .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #b1b1b5;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu .sidebar-link i {
            margin-right: 10px;
            font-size: 18px;
        }

        /* Hover Effect */
        .sidebar-menu .sidebar-link .hover-effect {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #14abef;
            transition: width 0.3s ease;
        }

        .sidebar-menu .sidebar-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu .sidebar-link:hover .hover-effect {
            width: 100%;
        }

        /* Active State */
        .sidebar-menu .sidebar-link.active {
            color: #fff;
            background-color: rgba(20, 171, 239, 0.1);
        }

        .sidebar-menu .sidebar-link.active .hover-effect {
            width: 100%;
            background-color: #14abef;
        }

        /* Header Style */
        .sidebar-header {
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            padding: 10px 15px;
            letter-spacing: 1px;
            pointer-events: none;
        }
    </style>

    <script>
        // Add active class to current menu item
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.pathname;
            const links = document.querySelectorAll('.sidebar-link');

            links.forEach(link => {
                if (link.getAttribute('href') === currentUrl) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</div>
