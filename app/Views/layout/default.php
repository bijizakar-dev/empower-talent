<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <?= $this->renderSection('title') ?>
        
        <link href="<?= base_url()?>/assets/css/simple-datatables.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
        <link href="<?= base_url()?>/template/css/styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="<?= base_url()?>/template/assets/img/favicon.png" />
        <style>
            /* Loading Style */
            .loadingOverlay-custom {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(5px); 
                display: none;
                z-index: 9999;
            }

            #loadingSpinner {
                position: absolute;
                top: 50%;
                left: 50%;
                z-index: 10000; 
                display: none;
                width: 3rem; 
                height: 3rem;
            }
            /* Loading Style */
        </style>
        
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url()?>/assets/js/feather.min.js"></script>
        <script src="<?= base_url()?>/assets/js/jquery.min.js"></script>
        <script src="<?= base_url()?>/assets/js/sweetalert2.all.min.js"></script>
        <script src="<?= base_url()?>/template/js/scripts.js"></script>
        <script src="<?= base_url()?>/assets/js/simple-datatables.min.js"></script>
        <script src="<?= base_url()?>/assets/js/helper_lib.js""></script>

        <script type="text/javascript">
            function showLoading() {
                $('#loadingSpinner').show();  // Menampilkan spinner
                $('#loadingOverlay').show();
            }

            function hideLoading() {
                $('#loadingSpinner').hide();  // Menyembunyikan spinner
                $('#loadingOverlay').hide();
            }
        </script>
    </head>
    <body class="nav-fixed">
        <!-- LOADING SPINNER -->
        <div class="loadingOverlay-custom" id="loadingOverlay"></div>
        <div class="spinner-border text-primary" role="status" id="loadingSpinner" style="">
            <span class="visually-hidden">Loading...</span>
        </div>
        <!-- LOADING SPINNER -->


        <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
            <?= $this->include('layout/navbar') ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?= $this->include('layout/sidebar') ?>
            </div>
            <div id="layoutSidenav_content">
                <?= $this->renderSection('content') ?>

                <footer class="footer-admin mt-auto footer-light">
                    <div class="container-xl px-4">
                        <div class="row">
                            <div class="col-md-6 small">Copyright &copy; Empower Talent 2024</div>
                            <div class="col-md-6 text-md-end small">
                                <a href="#!">Privacy Policy</a>
                                &middot;
                                <a href="#!">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?= base_url()?>/template/assets/demo/chart-area-demo.js"></script>
        <script src="<?= base_url()?>/template/assets/demo/chart-bar-demo.js"></script>
        <script src="<?= base_url()?>/template/js/datatables/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
        <script src="<?= base_url()?>/template/js/litepicker.js"></script>
    </body>
</html>
