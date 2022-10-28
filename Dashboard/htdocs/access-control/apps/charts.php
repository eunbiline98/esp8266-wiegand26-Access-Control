<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Access Control - Philoin</title>

    <!-- Custom fonts for this template-->
    <link href="../src/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../src/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../src/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    <!-- Bootstrap core JavaScript-->
    <script src="../src/vendor/jquery/jquery.min.js"></script>
    <script src="../src/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function todayDate() {
        var d = new Date();
        var n = d.getFullYear() + "  ";
        return document.getElementById("date").innerHTML = n;
    }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script type="text/javascript" src="C:\xampp\htdocs\access-control\chartjs\Chart.js"></script>

</head>

<body id="page-top" onload="todayDate()">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'partial_sidebar.php';?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'partial_topbar.php';?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Charts</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Chart Memberhip Kantorkuu </h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <canvas id="myChart" style="width:100%;max-width:100%"></canvas>
                                    <script>
                                    var xValues = ["Staff", "Co-Working", "Private Office", "Meeting Room"];
                                    var yValues = [25, 44, 14, 35];
                                    var barColors = ["red", "green", "blue", "orange"];

                                    new Chart("myChart", {
                                        type: "bar",
                                        data: {
                                            labels: xValues,
                                            datasets: [{
                                                backgroundColor: barColors,
                                                data: yValues
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            title: {
                                                display: true,
                                                text: "Kantorkuu"
                                            }
                                        }
                                    });
                                    </script>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <em id="date"></em> <a href="https://philoin.com/"
                                style="text-decoration: none;"><b>Philoin System</b></a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Page level plugins -->
    <script src="../src/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../src/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../src/js/sb-admin-2.min.js"></script>

</body>

</html>