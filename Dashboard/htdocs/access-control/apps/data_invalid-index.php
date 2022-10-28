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
                    <h1 class="h3 mb-2 text-gray-800">Data Kartu Invalid</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Invalid Tap</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="data_invalid-delete-all.php" class="btn btn-danger pull-right"
                                            id="DelAllBtn">Hapus Semua
                                            Data</a>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <form action="data_karyawan-index.php" method="get">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                    placeholder="Pencarian data kartu invalid" name="search">
                                            </div>
                                        </form>
                                    </div> -->
                                </div>
                                <br>

                                <?php
                        // Include config file
                    require_once "config.php";

        //Pagination
        if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
        $no_of_records_per_page = 25;
        $offset = ($pageno-1) * $no_of_records_per_page;

        $total_pages_sql = "SELECT COUNT(*) FROM data_invalid";
        $result = mysqli_query($link, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

        //Column sorting on column name
        $orderBy = array('tanggal', 'waktu', 'uid', 'status');
        $order = 'id';
        if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
            $order = $_GET['order'];
        }

        //Column sort order
        $sortBy = array('asc', 'desc');
        $sort = 'desc';
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
            if ($_GET['sort']=='asc') {
                $sort='desc';
            } else {
                $sort='asc';
            }
        }
        // Attempt select query execution
        $sql = "SELECT * FROM data_invalid ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";

        if (!empty($_GET['search'])) {
            $search = ($_GET['search']);
            $sql = "SELECT * FROM data_invalid
                            WHERE CONCAT (tanggal,waktu,uid,status)
                            LIKE '%$search%'
                            ORDER BY $order $sort 
                            LIMIT $offset, $no_of_records_per_page";
        } else {
            $search = "";
        }

        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                echo "<table class='table table-bordered table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th><a href=?search=$search&sort=&order=tanggal&sort=$sort>Tanggal</th>";
                echo "<th><a href=?search=$search&sort=&order=waktu&sort=$sort>Jam</th>";
                echo "<th><a href=?search=$search&sort=&order=uid&sort=$sort>UID</th>";
                echo "<th><a href=?search=$search&sort=&order=status&sort=$sort>Status</th>";

                echo "<th>Action</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "<td>" . $row['waktu'] . "</td>";
                    echo "<td>" . $row['uid'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    echo "<a href='data_invalid-add.php?uid=". $row['uid'] ."' title='Daftarkan' data-toggle='tooltip'><span class='fa fa-plus'></span></a> &nbsp";
                    echo "<a href='data_invalid-delete.php?id=". $row['id'] ."' title='Hapus' data-toggle='tooltip'><span class='fa fa-trash'></span></a>";

                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                ?>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                        <li class="page-item <?php if ($pageno <= 1) {
                                            echo 'disabled';
                                        } ?>">
                                            <a class="page-link" href="<?php if ($pageno <= 1) {
                                                echo '#';
                                            } else {
                                                echo "?pageno=".($pageno - 1);
                                            } ?>">Prev</a>
                                        </li>
                                        <li class="page-item <?php if ($pageno >= $total_pages) {
                                            echo 'disabled';
                                        } ?>">
                                            <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                echo '#';
                                            } else {
                                                echo "?pageno=".($pageno + 1);
                                            } ?>">Next</a>
                                        </li>
                                        <li class="page-item"><a class="page-link"
                                                href="?pageno=<?php echo $total_pages; ?>">Last</a>
                                        </li>
                                    </ul>
                                </nav>
                                <?php
                                                     // Free result set
                                                mysqli_free_result($result);
            } else {
                echo "<p class='lead'><em>No records were found.</em></p>";
                echo '<script language="javascript" type="text/javascript"> 
									document.getElementById("DelAllBtn").className = "btn btn-danger pull-right disabled";
								</script>';
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }

        // Close connection
        mysqli_close($link);
        ?>
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