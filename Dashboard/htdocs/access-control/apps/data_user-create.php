<?php
date_default_timezone_set('Asia/Jakarta');
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$created = "";
$uid = "";
$nama = "";
$member = "";
$mail = "";
$alamat = "";
$picture = "";

$created_err = "";
$uid_err = "";
$nama_err = "";
$member_err = "";
$mail_err = "";
$alamat_err = "";
$picture_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /*
        // Validate input
        $input_address = trim($_POST["address"]);
        if(empty($input_address)){
            $address_err = "Please enter an address.";
        } else{
            $address = $input_address;
        }

        // Check input errors before inserting in database
        if(empty($name_err) && empty($address_err) && empty($salary_err)){
            // Prepare an insert statement
     */
    $created = date("Y-m-d");
    $uid = trim($_POST["uid"]);
    $nama = trim($_POST["nama"]);
    $member = trim($_POST["member"]);
    $mail = trim($_POST["mail"]);
    $alamat = trim($_POST["alamat"]);
    $picture = "";


    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];
    try {
        $pdo = new PDO($dsn, $db_user, $db_password, $options);
    } catch (Exception $e) {
        error_log($e->getMessage());
        exit('Something weird happened'); //something a user can understand
    }
    $stmt = $pdo->prepare("INSERT INTO data_user (created,uid,nama,member,mail,alamat,picture) VALUES (?,?,?,?,?,?,?)");

    if ($stmt->execute([ $created,$uid,$nama,$member,$mail,$alamat,$picture  ])) {
        $stmt = null;
        //echo "<script type='text/javascript'>alert('Data berhasil ditambahkan');</script>";
        //header("location: data_user-index.php");
        echo '<script language="javascript" type="text/javascript"> 
								alert("Data berhasil ditambahkan");
								window.location.replace("data_user-index.php");
					  </script>';
    } else {
        echo "Something went wrong. Please try again later.";
    }
}
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

    <!-- Custom styles for this page -->
    <link href="../src/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                    <h1 class="h3 mb-2 text-gray-800">Data User</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>"
                                            placeholder="Input nama user" required>
                                        <span class="help-block"><?php echo $nama_err; ?></span>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="uid">UID</label>
                                            <input type="text" name="uid" class="form-control"
                                                value="<?php echo $uid; ?>" placeholder="Input UID kartu Member"
                                                required>
                                            <span class="help-block"><?php echo $uid_err; ?></span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mail">Email</label>
                                            <input type="text" name="mail" class="form-control"
                                                value="<?php echo $mail; ?>" placeholder="Input email user" required>
                                            <span class="help-block"><?php echo $mail_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="member">Divisi</label>
                                        <select class="form-control" name="member">
                                            <option value="Staff">Staff</option>
                                            <option value="Co-Working">Co-Working</option>
                                            <option value="Private Office">Private Office</option>
                                            <option value="Meeting Room">Meeting Room</option>
                                        </select>
                                        <!--
						<input type="text" name="member" class="form-control" value="<?php echo $member; ?>"
                                        placeholder="Input divisi/bagian" required>
                                        -->
                                        <span class="help-block"><?php echo $member_err; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea name="alamat" class="form-control"
                                            placeholder="Input alamat rumah user"><?php echo $alamat ; ?></textarea>
                                        <span class="help-block"><?php echo $alamat_err; ?></span>
                                    </div>
                                    <hr>
                                    <div class="row justify-content-end">
                                        <input type="submit" class="btn btn-success" value="Tambah"> &nbsp
                                        <a href="data_user-index.php" class="btn btn-primary">Batal</a>
                                    </div>
                                </form>

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


    <!-- Core plugin JavaScript-->
    <script src="../src/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../src/js/sb-admin-2.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../src/vendor/jquery/jquery.min.js"></script>
    <script src="../src/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>