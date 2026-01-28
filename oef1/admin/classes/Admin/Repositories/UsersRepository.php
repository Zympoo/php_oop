<?php
declare(strict_types=1);

namespace Admin\Repositories;

use Admin\Core\Database;
use PDO;

class UsersRepository
{
    private PDO $pdo;

    /**
     * __construct()
     *
     * Doel:
     * Bewaart PDO zodat we user-queries kunnen uitvoeren.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * findByEmail()
     *
     * Doel:
     * Zoekt een user op via email en haalt ook de rolnaam op.
     *
     * Werking:
     * 1) SELECT user velden.
     * 2) JOIN roles om roles.name als role_name mee te geven.
     * 3) Prepared statement met :email.
     * 4) fetch() -> array of null.
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT u.id, u.email, u.password_hash, u.name, r.name AS role_name
                FROM users u
                JOIN roles r ON r.id = u.role_id
                WHERE u.email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    public function getAll(): array
    {
        $sql = "SELECT u.id, u.name, u.email, r.name AS role_name, u.created_at
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT u.id, u.name, u.email, r.name AS role_name, u.created_at
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.id = :id
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user === false ? null : $user;
    }

    public function create(string $name, string $email, string $passwordHash, string $roleName): int
    {
        $sql = "INSERT INTO users (name, email, password_hash, role_id)
            VALUES (:name, :email, :password_hash, (SELECT id FROM roles WHERE LOWER(name) = :roleName LIMIT 1))";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
            'roleName' => strtolower($roleName),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $name, string $email, ?string $passwordHash, string $roleName): int
    {
        $sql = "UPDATE users SET
                name = :name,
                email = :email,
                role_id = (SELECT id FROM roles WHERE LOWER(name) = :roleName LIMIT 1)";

        if ($passwordHash !== null) {
            $sql .= ", password_hash = :password_hash";
        }

        $sql .= " WHERE id = :id LIMIT 1";

        $params = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'roleName' => strtolower($roleName),
        ];

        if ($passwordHash !== null) {
            $params['password_hash'] = $passwordHash;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->rowCount();
    }

    public function delete(int $id): int
    {
        $sql = "DELETE FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return (int)$stmt->rowCount();
    }

    /**
     * make()
     *
     * Doel:
     * Factory method om repository snel te maken met standaard connectie.
     */
    public static function make(): self
    {
        return new self(Database::getConnection());
    }
}
