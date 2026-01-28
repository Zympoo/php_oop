<?php
declare(strict_types=1);

namespace Admin\Controllers;

use Admin\Core\View;
use Admin\Repositories\UsersRepository;

class UsersController
{
    private UsersRepository $usersRepository;
    private string $title = 'Users';

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * index()
     *
     * Toont het overzicht van users.
     */
    public function index(): void
    {
        $users = $this->usersRepository->getAll();

        View::render('users.php', [
            'title' => $this->title,
            'users' => $users,
        ]);
    }

    /**
     * show()
     *
     * Toont 1 user (optioneel, zoals bij posts)
     */
    public function show(int $id): void
    {
        $user = $this->usersRepository->find($id);

        if ($user === null) {
            (new ErrorController())->notFound('/users/' . $id);
            return;
        }

        View::render('user-show.php', [
            'title' => 'User #' . $id,
            'user' => $user,
        ]);
    }

    /**
     * create() – toont formulier voor nieuwe user
     */
    public function create(): void
    {
        View::render('user-create.php', [
            'title' => 'Nieuwe user',
            'errors' => [],
            'old' => [
                'name' => '',
                'email' => '',
                'role' => '',
            ],
        ]);
    }

    /**
     * store() – verwerkt formulier en slaat op
     */
    public function store(): void
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $role = (string)($_POST['role'] ?? 'editor');

        $errors = [];

        if ($name === '') $errors[] = 'Naam is verplicht.';
        if ($email === '') $errors[] = 'Email is verplicht.';
        if ($password === '') $errors[] = 'Wachtwoord is verplicht.';
        if (!in_array($role, ['editor','admin'], true)) $errors[] = 'Selecteer een geldige rol.';

        if (!empty($errors)) {
            View::render('user-create.php', [
                'title' => 'Nieuwe user',
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email, 'role' => $role],
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->usersRepository->create($name, $email, $passwordHash, $role);

        header('Location: /php/oef1/admin/users');
        exit;
    }

    /**
     * edit() – toont formulier voor bestaande user
     */
    public function edit(int $id): void
    {
        $user = $this->usersRepository->find($id);

        if ($user === null) {
            (new ErrorController())->notFound('/users/' . $id . '/edit');
            return;
        }

        View::render('user-edit.php', [
            'title' => 'User bewerken',
            'errors' => [],
            'userId' => $id,
            'old' => [
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => strtolower($user['role_name']), // editor / admin
            ],
        ]);
    }

    /**
     * update() – verwerkt edit formulier
     */
    public function update(int $id): void
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $role = (string)($_POST['role'] ?? 'editor');

        $errors = [];

        if ($name === '') $errors[] = 'Naam is verplicht.';
        if ($email === '') $errors[] = 'Email is verplicht.';
        if (!in_array($role, ['editor','admin'], true)) $errors[] = 'Selecteer een geldige rol.';

        if (!empty($errors)) {
            View::render('user-edit.php', [
                'title' => 'User bewerken',
                'errors' => $errors,
                'userId' => $id,
                'old' => ['name' => $name, 'email' => $email, 'role' => $role],
            ]);
            return;
        }

        $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;

        $this->usersRepository->update($id, $name, $email, $passwordHash, $role);

        header('Location: /php/oef1/admin/users');
        exit;
    }

    /**
     * deleteConfirm() – toont bevestigingspagina
     */
    public function deleteConfirm(int $id): void
    {
        $user = $this->usersRepository->find($id);

        if ($user === null) {
            (new ErrorController())->notFound('/users/' . $id . '/delete');
            return;
        }

        View::render('user-delete.php', [
            'title' => 'User verwijderen',
            'user' => $user,
        ]);
    }

    /**
     * delete() – verwijdert user
     */
    public function delete(int $id): void
    {
        $user = $this->usersRepository->find($id);

        if ($user === null) {
            (new ErrorController())->notFound('/users/' . $id . '/delete');
            return;
        }

        $this->usersRepository->delete($id);

        header('Location: /php/oef1/admin/users');
        exit;
    }
}