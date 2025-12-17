<?php
class DatabaseClass {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll($table) {
        $sql = "SELECT * FROM $table";
        return mysqli_query($this->conn, $sql);
    }

    public function getById($table, $idField, $id) {
        $sql = "SELECT * FROM $table WHERE $idField=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function insertBarang($data) {
        $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, stok)
                VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            'ssii',
            $data['nama'],
            $data['kategori'],
            $data['harga_jual'],
            $data['stok']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function updateBarang($data) {
        $sql = "UPDATE data_barang SET nama=?, kategori=?, harga_jual=?, stok=?
                WHERE id_barang=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            'ssiii',
            $data['nama'],
            $data['kategori'],
            $data['harga_jual'],
            $data['stok'],
            $data['id']
        );
        return mysqli_stmt_execute($stmt);
    }

    public function delete($table, $idField, $id) {
        $sql = "DELETE FROM $table WHERE $idField=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt);
    }
}
