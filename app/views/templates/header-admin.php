<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek Berkah - Admin</title>
    <!-- icon -->
    <link rel="shortcut icon" href="<?= BASEURL?>/assets/img/logo-img/Apotek-berkah-icon.png" type="image/x-icon">
    <!-- styles -->
    <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/header-headoffice-admin-style.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/content-headoffice-admin.css">
    <link rel="stylesheet" href="<?= BASEURL ?>/assets/css/content-admin.css">
    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jquery -->
    <script src="<?= BASEURL ?>/assets/js/jquery.js"></script>
    <!-- sweetalert -->
    <script src="<?= BASEURL ?>/assets/js/sweetalert2.all.min.js"></script>
</head>
<body id="body">
    <div class="wrapper">
        <nav class="shadow" id="sidebar">
            <div class="nav-container">
                <div class="nav-header">
                    <div class="apotek-logo">
                        <img src="<?= BASEURL ?>/assets/img/logo-img/Apotek-berkah-nobg.png" alt="Apotek Berkah logo">
                    </div>
                    <div class="apotek-identity">
                        <h3 id="apotek-name">Apotek Berkah</h3>
                        <p id="outlet-id"><?= $_SESSION['outlet_name'] ?></p>
                    </div>
                </div>
                
                <div id="close-nav" class="nav-toggle">
                    <img src="<?= BASEURL ?>/assets/img/icons/close.png" alt="close">
                </div>

                <div class="dateTime-container">
                    <div class="dateTime-innercontainer">
                        <div id="date"></div>
                        <div id="time"></div>
                    </div>
                </div>

                <div class="navigations">
                    <div class="separator-explanation-head">
                        <p>Menu Utama</p>
                    </div>

                    <!-- main tools (admin) -->
                    <?php for ($i=0; $i < count($data['navlinks']); $i++) { ?>
                        <?php if(is_array($data['navlinks'][$i])) {?>
                            <div class="navigation-item-subnav">
                                <div class="subnav-header">
                                    <div class="navigation-icon">
                                        <img src="<?= BASEURL ?>/assets/img/icons/<?= $data['navigations'][$i][0] ?>.png" alt="">
                                    </div>
                                    <p class="head-nav"><?= $data['navigations'][$i][0] ?></p>
                                    <span class="cover"></span>
                                    <img src="<?= BASEURL ?>/assets/img/icons/dropdown-arrow.png" class="dropdown-icon">
                                </div>
                                <div class="subnav-items d-none">
                                    <?php for($k=1; $k < count($data['navlinks'][$i]); $k++) { ?>
                                        <a href="<?= BASEURL ?>/app/admin/<?= $data['navlinks'][$i][$k] ?>" class="link" data-target="<?= $data['navlinks'][$i][$k] ?>">
                                            <p class="dropdown-item"><?= $data['navigations'][$i][$k] ?></p>
                                        </a>    
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="navigation-item">
                                <a href="<?= BASEURL ?>/app/admin/<?= $data['navlinks'][$i] ?>" class="link" data-target="<?= $data['navlinks'][$i] ?>">
                                    <div class="navigation-icon">
                                        <img src="<?= BASEURL ?>/assets/img/icons/<?= $data['navigations'][$i] ?>.png" alt="">
                                    </div>
                                    <p class="head-nav"><?= $data['navigations'][$i] ?></p>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <div class="separator-line"></div>
                    <div class="separator-explanation">
                        <p>Lainnya</p>
                    </div>
                    
                    <!-- other  -->
                    <?php for ($i=0; $i < count($data['insightlinks']); $i++) { ?>
                            <div class="navigation-item">
                                <a href="<?= BASEURL ?>/app/admin/<?= $data['insightlinks'][$i] ?>" class="link" data-target="<?= $data['insightlinks'][$i] ?>">
                                    <div class="navigation-icon">
                                        <img src="<?= BASEURL ?>/assets/img/icons/<?= $data['insights'][$i] ?>.png" alt="">
                                    </div>
                                    <p><?= $data['insights'][$i] ?></p>
                                </a>
                            </div>
                    <?php } ?>

                    <div class="separator-line"></div>
                    
                    <!-- logout -->
                    <div class="logout">
                        <a href="<?= BASEURL ?>/app/logout" id="logout" class="alertLogout">
                            <div class="navigation-icon">
                                <img src="<?= BASEURL ?>/assets/img/icons/logout.png" alt="">
                            </div>
                            <p>Logout</p>
                        </a>
                    </div>

                </div>
            </div>
        </nav>
        <main id="main">
            <span class="layer-cover"></span>
            <header class="shadow">
                <div class="left-header">
                    <div id="nav-toggle" class="nav-toggle">
                        <img src="<?= BASEURL ?>/assets/img/icons/nav-toggle.png" alt="nav-toggle">
                    </div>
                    <div class="page-information">
                        <h6 class="page-information-header">
                            Halaman
                        </h6>
                        <p>Admin</p>
                    </div>
                    <div class="user-information">
                        <h6 class="user-information-header">
                            Pengguna
                        </h6>
                        <p><?= $_SESSION['pengguna'] ?></p>
                    </div>
                </div>
                <div class="right-header">
                    <!-- <h3 class="page-info"><?= $data['page'] ?></h3> -->
                </div>
            </header>
            <section id="content">
