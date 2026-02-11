<?php

namespace app\controllers;

use Flight;
use app\models\Objet;
use app\models\Categorie;

class ObjetController
{
    private function ensureSessionStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function ensureUserAuthenticated(): ?int
    {
        $this->ensureSessionStarted();

        if (empty($_SESSION['user_authenticated'])) {
            Flight::redirect('/login');
            return null;
        }

        return (int)($_SESSION['user_id'] ?? 0);
    }

    public function index(): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        try {
            $objetModel = new Objet(Flight::db());
            $objets = $objetModel->getAllByUser($idUser);
        } catch (\Throwable $e) {
            $objets = [];
        }

        $success = $_SESSION['objet_success'] ?? null;
        $error = $_SESSION['objet_error'] ?? null;
        unset($_SESSION['objet_success'], $_SESSION['objet_error']);

        Flight::render('user/objet/objet', [
            'objets' => $objets,
            'success' => $success,
            'error' => $error,
        ]);
    }

    public function showCreate(): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        try {
            $categModel = new Categorie(Flight::db());
            $categories = $categModel->getAll();
        } catch (\Throwable $e) {
            $categories = [];
        }

        $error = $_SESSION['objet_create_error'] ?? null;
        $old = $_SESSION['objet_create_old'] ?? [];
        unset($_SESSION['objet_create_error'], $_SESSION['objet_create_old']);

        Flight::render('user/objet/create', [
            'categories' => $categories,
            'error' => $error,
            'old' => $old,
        ]);
    }

    public function handleCreate(): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $titre = (string)($_POST['titre'] ?? '');
        $prix = (string)($_POST['prix'] ?? '0');
        $description = (string)($_POST['description'] ?? '');
        $idCateg = (int)($_POST['idCateg'] ?? 0);

        $_SESSION['objet_create_old'] = [
            'titre' => $titre,
            'prix' => $prix,
            'description' => $description,
            'idCateg' => $idCateg,
        ];

        $uploadDir = __DIR__ . '/../../public/data';

        try {
            $objetModel = new Objet(Flight::db());
            $objetId = $objetModel->createWithImages(
                $titre,
                (float)$prix,
                $description,
                $idUser,
                $idCateg,
                $_FILES['images'] ?? null,
                $uploadDir
            );

            unset($_SESSION['objet_create_old']);
            $_SESSION['objet_success'] = 'Objet créé avec succès.';
            Flight::redirect('/objet');
        } catch (\Throwable $e) {
            $_SESSION['objet_create_error'] = 'Création impossible. Vérifiez les champs et les images.';
            Flight::redirect('/objet/create');
        }
    }

    public function showEdit($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjet = (int)$id;

        try {
            $objetModel = new Objet(Flight::db());
            $objet = $objetModel->getByIdAndUser($idObjet, $idUser);
            
            if (!$objet) {
                $_SESSION['objet_error'] = 'Objet non trouvé.';
                Flight::redirect('/objet');
                return;
            }

            $images = $objetModel->getImagesByObjet($idObjet);

            $categModel = new Categorie(Flight::db());
            $categories = $categModel->getAll();
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur lors du chargement de l\'objet.';
            Flight::redirect('/objet');
            return;
        }

        $error = $_SESSION['objet_edit_error'] ?? null;
        $old = $_SESSION['objet_edit_old'] ?? $objet;
        unset($_SESSION['objet_edit_error'], $_SESSION['objet_edit_old']);

        Flight::render('user/objet/edit', [
            'objet' => $old,
            'images' => $images,
            'categories' => $categories,
            'error' => $error,
        ]);
    }

    public function handleEdit($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjet = (int)$id;
        $titre = (string)($_POST['titre'] ?? '');
        $prix = (string)($_POST['prix'] ?? '0');
        $description = (string)($_POST['description'] ?? '');
        $idCateg = (int)($_POST['idCateg'] ?? 0);

        $_SESSION['objet_edit_old'] = [
            'id' => $idObjet,
            'titre' => $titre,
            'prix' => $prix,
            'description' => $description,
            'idCateg' => $idCateg,
        ];

        $uploadDir = __DIR__ . '/../../public/data';

        try {
            $objetModel = new Objet(Flight::db());
            
            // Vérifier que l'objet appartient bien à l'utilisateur
            $existingObjet = $objetModel->getByIdAndUser($idObjet, $idUser);
            if (!$existingObjet) {
                $_SESSION['objet_error'] = 'Objet non trouvé.';
                Flight::redirect('/objet');
                return;
            }

            $ok = $objetModel->updateWithImages(
                $idObjet,
                $titre,
                (float)$prix,
                $description,
                $idCateg,
                $_FILES['images'] ?? null,
                $uploadDir,
                $_POST['delete_images'] ?? []
            );

            if ($ok) {
                unset($_SESSION['objet_edit_old']);
                $_SESSION['objet_success'] = 'Objet mis à jour avec succès.';
                Flight::redirect('/objet');
            } else {
                $_SESSION['objet_edit_error'] = 'Mise à jour impossible. Vérifiez les champs et les images.';
                Flight::redirect("/objet/{$idObjet}/edit");
            }
        } catch (\Throwable $e) {
            $_SESSION['objet_edit_error'] = 'Erreur serveur lors de la mise à jour.';
            Flight::redirect("/objet/{$idObjet}/edit");
        }
    }

    public function handleDelete($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjet = (int)$id;
        $uploadDir = __DIR__ . '/../../public/data';

        try {
            $objetModel = new Objet(Flight::db());
            $ok = $objetModel->deleteByIdAndUser($idObjet, $idUser, $uploadDir);

            if ($ok) {
                $_SESSION['objet_success'] = 'Objet supprimé avec succès.';
            } else {
                $_SESSION['objet_error'] = 'Suppression impossible.';
            }
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur serveur lors de la suppression.';
        }

        Flight::redirect('/objet');
    }
}
