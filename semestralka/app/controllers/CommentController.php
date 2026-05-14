<?php

class CommentController {
    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }
    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }

    public function store($gameId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $this->addErrorMessage('Pro přidání komentáře musíte být přihlášeni.');
                header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $gameId);
                exit;
            }

            $content = htmlspecialchars(trim($_POST['content'] ?? ''));
            $rating = (int)($_POST['rating'] ?? 5);
            if ($rating < 1 || $rating > 5) $rating = 5;

            if (empty($content)) {
                $this->addErrorMessage('Komentář nesmí být prázdný.');
                header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $gameId);
                exit;
            }

            require_once '../app/models/Database.php';
            require_once '../app/models/Comment.php';
            $db = (new Database())->getConnection();
            $commentModel = new Comment($db);

            if ($commentModel->hasUserCommented((int)$gameId, $_SESSION['user_id'])) {
                $this->addErrorMessage('Svůj komentář k této hře již můžete pouze upravit.');
                header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $gameId);
                exit;
            }

            if ($commentModel->create((int)$gameId, $_SESSION['user_id'], $content, $rating)) {
                $this->addSuccessMessage('Komentář byl úspěšně přidán.');
            } else {
                $this->addErrorMessage('Nepodařilo se přidat komentář.');
            }

            header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $gameId);
            exit;
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection();
        $commentModel = new Comment($db);

        $comment = $commentModel->getById((int)$id);
        if (!$comment) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola práv: smazat může autor nebo admin
        if ($comment['user_id'] != $_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění smazat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $comment['game_id']);
            exit;
        }

        if ($commentModel->delete((int)$id)) {
            $this->addSuccessMessage('Komentář byl smazán.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $comment['game_id']);
        exit;
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection();
        $commentModel = new Comment($db);

        $comment = $commentModel->getById((int)$id);
        if (!$comment) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola práv
        if ($comment['user_id'] != $_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $comment['game_id']);
            exit;
        }

        require_once '../app/views/games/comment_edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            require_once '../app/models/Database.php';
            require_once '../app/models/Comment.php';
            $db = (new Database())->getConnection();
            $commentModel = new Comment($db);

            $comment = $commentModel->getById((int)$id);
            if (!$comment) {
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            if ($comment['user_id'] != $_SESSION['user_id'] && empty($_SESSION['is_admin'])) {
                $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
                header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $comment['game_id']);
                exit;
            }

            $content = htmlspecialchars(trim($_POST['content'] ?? ''));
            $rating = (int)($_POST['rating'] ?? 5);
            if ($rating < 1 || $rating > 5) $rating = 5;

            if (empty($content)) {
                $this->addErrorMessage('Komentář nesmí být prázdný.');
                header('Location: ' . BASE_URL . '/index.php?url=comment/edit/' . $id);
                exit;
            }

            if ($commentModel->update((int)$id, $content, $rating)) {
                $this->addSuccessMessage('Komentář byl upraven.');
                header('Location: ' . BASE_URL . '/index.php?url=game/show/' . $comment['game_id']);
            } else {
                $this->addErrorMessage('Chyba při úpravě komentáře.');
                header('Location: ' . BASE_URL . '/index.php?url=comment/edit/' . $id);
            }
            exit;
        }
    }
}
