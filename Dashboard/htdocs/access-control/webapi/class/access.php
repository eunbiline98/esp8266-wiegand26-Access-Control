<?php

date_default_timezone_set('Asia/Jakarta');
class Absensi
{
    // Connection
    private $conn;

    // Table
    private $db_table = "data_access";
    private $db_table1 = "data_user";
    private $db_table2 = "data_invalid";

    // Columns
    public $id;
    public $tanggal;
    public $waktu;
    public $uid;
    public $status;
    public $last_status;
    public $nama;
    public $id_device;
    public $member;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // CREATE
    public function createData()
    {
        //1. Cek user
        $sqlQuery = "SELECT * FROM ". $this->db_table1 ." WHERE uid = :uid LIMIT 0,1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":uid", $this->uid);
        $stmt->execute();
        if ($stmt->errorCode() == 0) {
            while (($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) != false) {
                $this->nama = $dataRow['nama'];
                $this->member = $dataRow['member'];
            }
        } else {
            $errors = $stmt->errorInfo();
            echo($errors[2]);
        }
        $itemCount = $stmt->rowCount();

        if ($itemCount > 0) {
            //UID terdaftar -> cek status terakhir
            $sqlQuery = "SELECT data_access.id, data_access.uid, data_access.status, data_user.nama, data_user.member 
						FROM ". $this->db_table .", ". $this->db_table1 ."
						WHERE data_access.id = (SELECT MAX(data_access.id) 
						FROM ". $this->db_table ." WHERE data_access.uid = :uid) 
						AND data_user.uid= :uid";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":uid", $this->uid);
            $stmt->execute();
            $itemCount = $stmt->rowCount();
            if ($itemCount > 0) {
                //error handling
                if ($stmt->errorCode() == 0) {
                    while (($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)) != false) {
                        $this->last_status = $dataRow['status'];
                        $this->nama = $dataRow['nama'];
                        $this->member = $dataRow['member'];
                        //echo($this->last_status);
                    }
                } else {
                    $errors = $stmt->errorInfo();
                    echo($errors[2]);
                }
            } else {
                $this->last_status ="OUT";
            }

            //set status
            if ($this->last_status == "IN") {
                $this->status = "OUT";
            } else {
                $this->status= "IN";
            }
            //Insert Data to data_access
            $sqlQuery = "INSERT INTO ". $this->db_table ."
					SET	waktu = :waktu, uid = :uid, status = :now_status, id_device = :id_device";

            $this->waktu = date("H:i:s");

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->uid=htmlspecialchars(strip_tags($this->uid));
            $this->id_device=htmlspecialchars(strip_tags($this->id_device));

            // bind data
            $stmt->bindParam(":uid", $this->uid);
            $stmt->bindParam(":now_status", $this->status);
            $stmt->bindParam(":waktu", $this->waktu);
            $stmt->bindParam(":id_device", $this->id_device);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } else {
            //UID tidak terdaftar
            $this->status= "INVALID";
            $this->nama ="Invalid";

            //Insert Data to data_invalid
            $sqlQuery = "INSERT INTO
						". $this->db_table2 ."
					SET
						waktu = :waktu,
						uid = :uid, 
						status = :now_status, id_device = :id_device";
            $this->waktu = date("H:i:s");

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->uid=htmlspecialchars(strip_tags($this->uid));
            $this->id_device=htmlspecialchars(strip_tags($this->id_device));

            // bind data
            $stmt->bindParam(":uid", $this->uid);
            $stmt->bindParam(":now_status", $this->status);
            $stmt->bindParam(":waktu", $this->waktu);
            $stmt->bindParam(":id_device", $this->id_device);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }
    }
}