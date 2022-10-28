<?php
// Include config file
require_once "config.php";

// Prepare a delete statement
$sql = "SELECT * FROM data_user";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $rowcount=mysqli_num_rows($result);
    } else {
        $rowcount = "null";
    }
}
$today = date("Y-m-d");
$sql = "SELECT data_access.uid, tanggal, nama, member,
			 min(case when status='IN' then  waktu end) jam_masuk,
			 max(CASE WHEN status='OUT' then waktu end) jam_keluar
		  FROM data_access, data_user 
		  WHERE data_access.uid=data_user.uid  AND tanggal='".$today."'
		  GROUP BY data_access.uid";

if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $access =mysqli_num_rows($result);
    } else {
        $access = "null";
    }
}

$sql = "SELECT * FROM data_invalid GROUP BY uid";
if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $invalid =mysqli_num_rows($result);
    } else {
        $invalid = "null";
    }
}

$sql = "SELECT member,count( * ) FROM data_user WHERE member='Co-Working'";
if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $member_cw =mysqli_fetch_assoc($result);
    } else {
        $member_cw = "null";
    }
}

$sql = "SELECT member,count( * ) FROM data_user WHERE member='Meeting Room'";
if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $member_mr =mysqli_fetch_assoc($result);
    } else {
        $member_mr = "null";
    }
}

$sql = "SELECT member,count( * ) FROM data_user WHERE member='Private Office'";
if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $member_po =mysqli_fetch_assoc($result);
    } else {
        $member_po = "null";
    }
}

$sql = "SELECT member,count( * ) FROM data_user WHERE member='Staff'";
if ($stmt = mysqli_prepare($link, $sql)) {
    //mysqli_stmt_bind_param($stmt, "i", $today );
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $member_pr =mysqli_fetch_assoc($result);
    } else {
        $member_pr = "null";
    }
}


// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($link);
?>

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
    <script>
    function todayDate() {
        var d = new Date();
        var n = d.getFullYear() + " ";
        return document.getElementById("date").innerHTML = n;
    }
    </script>

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total
                                                User</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $rowcount; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Access Hari ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $access; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">20
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 20%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kartu
                                                Invalid</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $invalid; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-stop-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Membership</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Co-Working Space<span class="float-right">
                                            <?php echo $member_cw['count( * )']?>
                                            Person
                                        </span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Meeting Room<span class="float-right">
                                            <?php echo $member_mr['count( * )']?>
                                            Person
                                        </span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Private Office<span class="float-right">
                                            <?php echo $member_po['count( * )']?>
                                            Person
                                        </span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Staff <span class="float-right">
                                            <?php echo $member_pr['count( * )']?>
                                            Person
                                        </span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="">
                                    </div>
                                    <p>Add some quality, svg illustrations to your project courtesy of <a
                                            target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                        constantly updated collection of beautiful svg images that
                                        you can use completely free and without attribution!</p>
                                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                        unDraw &rarr;</a>
                                </div>
                            </div> -->

                            <!-- Approach -->
                            <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                                </div>
                                <div class="card-body">
                                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                        CSS bloat and poor
                                        page performance. Custom CSS classes are used to create custom components and
                                        custom utility
                                        classes.</p>
                                    <p class="mb-0">Before working with this theme, you should become familiar with the
                                        Bootstrap
                                        framework, especially the utility classes.</p>
                                </div>
                            </div> -->

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

    <!-- Bootstrap core JavaScript-->
    <script src="../src/vendor/jquery/jquery.min.js"></script>
    <script src="../src/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../src/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../src/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../src/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../src/js/demo/chart-area-demo.js"></script>
    <script src="../src/js/demo/chart-pie-demo.js"></script>

</body>

</html>