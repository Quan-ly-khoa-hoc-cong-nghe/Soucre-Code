<?php
class DonViDoiTac {
    private $conn;
    private $table = "DonViDoiTac";

    public $ma_doi_tac;
    public $ten_doi_tac;
    public $sdt_doi_tac;
    public $email_doi_tac;
    public $dia_chi_doi_tac;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        return $this->conn->query($query);
    }

    public function getOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE ma_doi_tac = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->ma_doi_tac);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function add() {
        $query = "INSERT INTO " . $this->table . " SET ma_doi_tac=?, ten_doi_tac=?, sdt_doi_tac=?, email_doi_tac=?, dia_chi_doi_tac=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $this->ma_doi_tac, $this->ten_doi_tac, $this->sdt_doi_tac, $this->email_doi_tac, $this->dia_chi_doi_tac);
        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET ten_doi_tac=?, sdt_doi_tac=?, email_doi_tac=?, dia_chi_doi_tac=? WHERE ma_doi_tac=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $this->ten_doi_tac, $this->sdt_doi_tac, $this->email_doi_tac, $this->dia_chi_doi_tac, $this->ma_doi_tac);
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE ma_doi_tac = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->ma_doi_tac);
        return $stmt->execute();
    }
}
?>
