<?php

class UserController {
    // Helper for messages
    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }
    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    // 1. Zobrazení profilu
    public function show($id = null) {
        if ($id === null && isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
        }

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        $user = $userModel->findById((int)$id);

        if (!$user) {
            $this->addErrorMessage('Uživatel nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $gamesCount = $userModel->getGamesCount((int)$id);
        $commentsCount = $userModel->getCommentsCount((int)$id);
        
        $userGames = [];
        $userComments = [];
        if (isset($_SESSION['user_id'])) {
            $userGames = $userModel->getGamesByUserId((int)$id);
            $userComments = $userModel->getCommentsByUserId((int)$id);
        }

        require_once '../app/views/user/profile_show.php';
    }

    // 2. Formulář pro úpravu profilu
    public function edit($id) {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != $id && empty($_SESSION['is_admin']))) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento profil.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        $user = $userModel->findById((int)$id);

        if (!$user) {
            $this->addErrorMessage('Uživatel nebyl nalezen.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/user/profile_edit.php';
    }

    // 3. Zpracování úpravy profilu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != $id && empty($_SESSION['is_admin']))) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tento profil.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $firstName = htmlspecialchars($_POST['first_name'] ?? '');
            $lastName = htmlspecialchars($_POST['last_name'] ?? '');
            $nickname = htmlspecialchars($_POST['nickname'] ?? '');

            require_once '../app/models/Database.php';
            require_once '../app/models/User.php';
            $db = (new Database())->getConnection();
            $userModel = new User($db);

            if ($userModel->update((int)$id, $firstName, $lastName, $nickname)) {
                if ($_SESSION['user_id'] == $id) {
                    $user = $userModel->findById((int)$id);
                    $_SESSION['user_name'] = !empty($user['nickname']) ? $user['nickname'] : $user['username'];
                }
                
                $this->addSuccessMessage('Profil byl úspěšně aktualizován.');
                header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $id);
            } else {
                $this->addErrorMessage('Došlo k chybě při aktualizaci profilu.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
            }
            exit;
        }
    }

    // 4. Výpis všech uživatelů (jen pro admina)
    public function index() {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění k zobrazení této stránky.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        $users = $userModel->getAll();

        require_once '../app/views/user/user_list.php';
    }

    // 5. Smazání uživatele (jen admin)
    public function delete($id) {
        if (empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění mazat uživatele.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SESSION['user_id'] == $id) {
            $this->addErrorMessage('Nemůžete smazat svůj vlastní účet tímto způsobem.');
            header('Location: ' . BASE_URL . '/index.php?url=user/index');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->delete((int)$id)) {
            $this->addSuccessMessage('Uživatel byl úspěšně smazán.');
        } else {
            $this->addErrorMessage('Došlo k chybě při mazání uživatele.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=user/index');
        exit;
    }

    // 6. Změna hesla
    public function updatePassword($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] != $id && empty($_SESSION['is_admin']))) {
                $this->addErrorMessage('Nemáte oprávnění měnit heslo tomuto uživateli.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $this->addErrorMessage('Všechna pole musí být vyplněna.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $this->addErrorMessage('Nová hesla se neshodují.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }

            if (strlen($newPassword) < 8 || !preg_match('/[0-9]/', $newPassword) || !preg_match('/[A-Z]/', $newPassword)) {
                $this->addErrorMessage('Nové heslo musí mít alespoň 8 znaků, obsahovat 1 číslo a 1 velké písmeno.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }

            require_once '../app/models/Database.php';
            require_once '../app/models/User.php';
            $db = (new Database())->getConnection();
            $userModel = new User($db);

            // Ověření starého hesla
            $currentHash = $userModel->getPasswordHash((int)$id);
            if (!password_verify($currentPassword, $currentHash)) {
                $this->addErrorMessage('Současné heslo není správné.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
                exit;
            }

            if ($userModel->updatePassword((int)$id, $newPassword)) {
                $this->addSuccessMessage('Heslo bylo úspěšně změněno.');
                header('Location: ' . BASE_URL . '/index.php?url=user/show/' . $id);
            } else {
                $this->addErrorMessage('Došlo k chybě při změně hesla.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $id);
            }
            exit;
        }
    }
}
