CREATE DATABASE IF NOT EXISTS access_control;

USE absensi;

/*Table structure for table `data_access` */
CREATE TABLE data_access (
    id int(100) NOT NULL AUTO_INCREMENT,
    tanggal date NOT NULL DEFAULT current_timestamp(),
    waktu time NOT NULL DEFAULT current_timestamp(),
    uid varchar(20) NOT NULL,
    status varchar(20) NOT NULL,
    PRIMARY KEY (id)
);

/*Table structure for table `data_invalid` */
CREATE TABLE data_invalid (
    id int(100) NOT NULL AUTO_INCREMENT,
    tanggal date NOT NULL DEFAULT current_timestamp(),
    waktu time NOT NULL DEFAULT current_timestamp(),
    uid varchar(10) NOT NULL,
    status varchar(10) NOT NULL,
    PRIMARY KEY (id)
);

/*Table structure for table `data_user` */
CREATE TABLE data_user (
    id int(50) NOT NULL AUTO_INCREMENT,
    created date NOT NULL DEFAULT current_timestamp(),
    uid varchar(20) NOT NULL,
    nama varchar(50) NOT NULL,
    division varchar(50) NOT NULL,
    mail varchar(50) NOT NULL,
    alamat text NOT NULL,
    picture varchar(100) NOT NULL,
    PRIMARY KEY (id)
);