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

        Flight::render('user/objet/index', [
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

    public function explore(): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        try {
            $objetModel = new Objet(Flight::db());
            $objets = $objetModel->getAllObjetExceptUser($idUser);
        } catch (\Throwable $e) {
            $objets = [];
        }

        Flight::render('user/objet/accueil', [
            'objets' => $objets,
        ]);
    }

    public function detail($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjet = (int)$id;

        try {
            $objetModel = new Objet(Flight::db());
            $stmt = Flight::db()->prepare(
                'SELECT o.*, c.libele AS categorie 
                 FROM objet o
                 JOIN categorie c ON c.id = o.idCateg
                 WHERE o.id = ? AND o.idProprio != ?'
            );
            $stmt->execute([$idObjet, $idUser]);
            $objet = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$objet) {
                $_SESSION['objet_error'] = 'Objet non trouvé.';
                Flight::redirect('/objet/accueil');
                return;
            }

            $images = $objetModel->getImagesByObjet($idObjet);
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur lors du chargement de l\'objet.';
            Flight::redirect('/objet/accueil');
            return;
        }

        Flight::render('user/objet/detail', [
            'objet' => $objet,
            'images' => $images,
        ]);
    }

    public function proposeExchange($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjetTarget = (int)$id;

        try {
            // Vérifier que l'objet cible existe et n'appartient pas à l'utilisateur
            $stmt = Flight::db()->prepare('SELECT id FROM objet WHERE id = ? AND idProprio != ?');
            $stmt->execute([$idObjetTarget, $idUser]);
            if (!$stmt->fetch(\PDO::FETCH_ASSOC)) {
                $_SESSION['objet_error'] = 'Objet non trouvé.';
                Flight::redirect('/objet/accueil');
                return;
            }

            // Récupérer les objets de l'utilisateur
            $objetModel = new Objet(Flight::db());
            $myObjets = $objetModel->getAllByUser($idUser);
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur lors du chargement.';
            Flight::redirect('/objet/accueil');
            return;
        }

        Flight::render('user/objet/propose', [
            'idObjetTarget' => $idObjetTarget,
            'myObjets' => $myObjets,
        ]);
    }

    public function handleProposal($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idObjetTarget = (int)$id;
        $idObjetSelected = (int)($_POST['idObjet'] ?? 0);

        if ($idObjetSelected <= 0) {
            $_SESSION['objet_error'] = 'Veuillez sélectionner un objet.';
            Flight::redirect("/objet/{$idObjetTarget}/propose");
            return;
        }

        try {
            // Vérifier que l'objet cible existe et n'appartient pas à l'utilisateur
            $stmt = Flight::db()->prepare('SELECT idProprio FROM objet WHERE id = ?');
            $stmt->execute([$idObjetTarget]);
            $targetRow = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$targetRow) {
                $_SESSION['objet_error'] = 'Objet cible non trouvé.';
                Flight::redirect('/objet/accueil');
                return;
            }

            $idProprioTarget = (int)($targetRow['idProprio'] ?? 0);

            // Vérifier que l'objet sélectionné appartient bien à l'utilisateur
            $stmt = Flight::db()->prepare('SELECT id FROM objet WHERE id = ? AND idProprio = ?');
            $stmt->execute([$idObjetSelected, $idUser]);
            if (!$stmt->fetch(\PDO::FETCH_ASSOC)) {
                $_SESSION['objet_error'] = 'Objet sélectionné invalide.';
                Flight::redirect("/objet/{$idObjetTarget}/propose");
                return;
            }

            // Créer la proposition d'échange
            $insertStmt = Flight::db()->prepare(
                'INSERT INTO echange (idObjet1, idObjet2, idProposeur, idDestinataire, idStatutEchange, dateEchange)
                 VALUES (?, ?, ?, ?, ?, NOW())'
            );
            // idStatut 3 = "En cours"
            $insertStmt->execute([$idObjetSelected, $idObjetTarget, $idUser, $idProprioTarget, 3]);

            $_SESSION['objet_success'] = 'Proposition d\'échange envoyée avec succès !';
            Flight::redirect('/objet/accueil');
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur lors de la création de la proposition.';
            Flight::redirect("/objet/{$idObjetTarget}/propose");
        }
    }

    public function showMyProposals(): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        try {
            $echangeModel = new \app\models\Echange(Flight::db());
            
            // Récupère toutes les propositions (reçues et envoyées)
            $allPropositions = $echangeModel->getEchangesByUser($idUser);
            
            // Séparer les propositions reçues et envoyées
            $propositionsRecues = [];
            $propositionsEnvoyees = [];
            
            foreach ($allPropositions as $prop) {
                if ($prop['idDestinataire'] == $idUser) {
                    $propositionsRecues[] = $prop;
                } else {
                    $propositionsEnvoyees[] = $prop;
                }
            }
            
            // Compter les propositions reçues en attente
            $nbPropositionsRecues = count(array_filter($propositionsRecues, function($p) {
                return $p['statut'] === 'En attente';
            }));
            
        } catch (\Throwable $e) {
            $propositionsRecues = [];
            $propositionsEnvoyees = [];
            $nbPropositionsRecues = 0;
        }

        $success = $_SESSION['objet_success'] ?? null;
        $error = $_SESSION['objet_error'] ?? null;
        unset($_SESSION['objet_success'], $_SESSION['objet_error']);

        Flight::render('objet/proposition', [
            'propositionsRecues' => $propositionsRecues,
            'propositionsEnvoyees' => $propositionsEnvoyees,
            'nbPropositionsRecues' => $nbPropositionsRecues,
            'success' => $success,
            'error' => $error,
        ]);
    }

    public function accepterEchange($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idEchange = (int)$id;

        try {
            $echangeModel = new \app\models\Echange(Flight::db());
            
            // Vérifier que l'utilisateur est bien le destinataire de la proposition
            if (!$echangeModel->estUtilisateurImplique($idEchange, $idUser)) {
                $_SESSION['objet_error'] = 'Action non autorisée.';
                Flight::redirect('/propositions');
                return;
            }
            
            // Accepter l'échange (statut 2 = Accepté)
            if ($echangeModel->mettreAJourStatut($idEchange, 2)) {
                $_SESSION['objet_success'] = 'Proposition d\'échange acceptée avec succès !';
            } else {
                $_SESSION['objet_error'] = 'Erreur lors de l\'acceptation de la proposition.';
            }
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur serveur lors de l\'acceptation.';
        }

        Flight::redirect('/propositions');
    }

    public function refuserEchange($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idEchange = (int)$id;

        try {
            $echangeModel = new \app\models\Echange(Flight::db());
            
            // Vérifier que l'utilisateur est bien le destinataire de la proposition
            if (!$echangeModel->estUtilisateurImplique($idEchange, $idUser)) {
                $_SESSION['objet_error'] = 'Action non autorisée.';
                Flight::redirect('/propositions');
                return;
            }
            
            // Refuser l'échange (statut 4 = Refusé)
            if ($echangeModel->mettreAJourStatut($idEchange, 4)) {
                $_SESSION['objet_success'] = 'Proposition d\'échange refusée.';
            } else {
                $_SESSION['objet_error'] = 'Erreur lors du refus de la proposition.';
            }
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur serveur lors du refus.';
        }

        Flight::redirect('/propositions');
    }

    public function annulerEchange($id): void
    {
        $idUser = $this->ensureUserAuthenticated();
        if ($idUser === null) {
            return;
        }

        $idEchange = (int)$id;

        try {
            $echangeModel = new \app\models\Echange(Flight::db());
            
            // Vérifier que l'utilisateur est bien le proposeur de la proposition
            $echange = $echangeModel->getEchangeById($idEchange);
            if (!$echange || $echange['idProposeur'] != $idUser) {
                $_SESSION['objet_error'] = 'Action non autorisée.';
                Flight::redirect('/propositions');
                return;
            }
            
            // Annuler l'échange (statut 5 = Annulé)
            if ($echangeModel->mettreAJourStatut($idEchange, 5)) {
                $_SESSION['objet_success'] = 'Proposition d\'échange annulée.';
            } else {
                $_SESSION['objet_error'] = 'Erreur lors de l\'annulation de la proposition.';
            }
        } catch (\Throwable $e) {
            $_SESSION['objet_error'] = 'Erreur serveur lors de l\'annulation.';
        }

        Flight::redirect('/propositions');
    }
}
