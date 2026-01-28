<?php
declare(strict_types=1);

namespace Admin\Repositories;

use Admin\Core\Database;
use PDO;

class PostsRepository
{
    private PDO $pdo;

    /**
     * __construct()
     *
     * Doel:
     * Bewaart de PDO connectie zodat alle queries via dezelfde connectie lopen.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * getAll()
     *
     * Doel:
     * Haalt alle posts op voor het overzicht.
     *
     * Werking:
     * 1) Voert SELECT query uit.
     * 2) Sorteert nieuwste eerst.
     * 3) Geeft array van posts terug.
     */
    public function getAll(): array
    {
        $sql = "SELECT p.id, p.title, p.content, p.status, p.created_at, c.naam AS categorie_naam
                FROM posts p
                LEFT JOIN categorie c ON p.categorie_id = c.id
                ORDER BY p.id DESC";
        $stmt = $this->pdo->query($sql);


        return $stmt->fetchAll();
    }

    /**
     * find()
     *
     * Doel:
     * Haalt één post op via id.
     *
     * Werking:
     * 1) Prepared statement met :id.
     * 2) Execute met parameter.
     * 3) Fetch 1 record.
     * 4) Return array of null.
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT p.id, p.title, p.content, p.status, p.created_at, c.naam AS categorie_naam
                FROM posts p
                LEFT JOIN categorie c ON p.categorie_id = c.id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $post = $stmt->fetch();

        return $post === false ? null : $post;
    }
    /**
     * create()
     *
     * Doel:
     * Voegt een nieuwe post toe in de database.
     */
    public function create(string $title, string $content, string $status): int
    {
        $sql = "INSERT INTO posts (title, content, status)
                VALUES (:title, :content, :status)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'status' => $status,
        ]);

        return (int)$this->pdo->lastInsertId();
    }
    /**
     * update()
     *
     * Doel:
     * Past een bestaande post aan in de database.
     *
     * Werking:
     * 1) Prepared UPDATE met placeholders.
     * 2) updated_at wordt gezet op NOW().
     * 3) execute() vult placeholders veilig in.
     * 4) rowCount() toont hoeveel rijen gewijzigd zijn.
     */
    public function update(int $id, string $title, string $content, string $status, string $categorie): int
    {
        $sql = "UPDATE posts
                SET 
                    title = :title,
                    content = :content,
                    status = :status,
                    categorie_id = (SELECT id FROM categorie WHERE naam = :categorie LIMIT 1),
                    updated_at = NOW()
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'status' => $status,
            'categorie' => $categorie,
        ]);

        return (int)$stmt->rowCount();
    }
    /**
     * delete()
     *
     * Doel:
     * Verwijdert een post uit de database.
     *
     * Werking:
     * 1) Prepared DELETE met :id.
     * 2) execute() voert delete uit.
     * 3) rowCount() toont of er effectief iets verwijderd is.
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM posts
                WHERE id = :id
                LIMIT 1";

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
