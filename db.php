<?php

class Database
{
    private PDO $db;

    public function __construct(string $path = 'pesel.sqlite')
    {
        $this->db = new PDO('sqlite:' . $path);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->createTable();
    }

    private function createTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS generated_pesel (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pesel TEXT NOT NULL UNIQUE,
            gender TEXT NOT NULL,
            birth_date TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);
    }

    public function savePesel(string $pesel, string $gender, string $birthDate): void
    {
        $stmt = $this->db->prepare("INSERT INTO generated_pesel (pesel, gender, birth_date) VALUES (:pesel, :gender, :birth_date)");
        $stmt->bindValue(':pesel', $pesel);
        $stmt->bindValue(':gender', $gender);
        $stmt->bindValue(':birth_date', $birthDate);
        $stmt->execute();
    }

    public function getHistory(int $limit = 20): array
    {
        $stmt = $this->db->prepare("SELECT * FROM generated_pesel ORDER BY created_at DESC, id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}