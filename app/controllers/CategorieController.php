<?php

namespace app\controllers;

use app\models\Categorie;
use Flight;
use PDO;

class CategorieController {
    private ?Categorie $categorie = null;

    /**
     * Initialise le modèle avec la connexion à la BD
     */
    private function initCategorie(): void {
        if ($this->categorie === null) {
            $db = Flight::db();
            if ($db === null) {
                throw new \Exception('Database connection not available');
            }
            $this->categorie = new Categorie($db);
        }
    }

    /**
     * Affiche la page de gestion des catégories
     */
    public function showCategories() {
        $this->initCategorie();
        $categories = $this->categorie->getAll();
        Flight::render('admin/categorie', ['categories' => $categories]);
    }

    /**
     * Récupère toutes les catégories en JSON
     */
    public function getAll() {
        $this->initCategorie();
        $categories = $this->categorie->getAll();
        Flight::json($categories);
    }

    /**
     * Crée une nouvelle catégorie
     */
    public function create() {
        try {
            $this->initCategorie();
            $request = Flight::request();
            
            // Récupérer les données JSON du body
            $body = json_decode(file_get_contents('php://input'), true);
            
            if (empty($body['libelle'])) {
                Flight::json(['success' => false, 'error' => 'Le libellé est requis'], 400);
                return;
            }

            $result = $this->categorie->insertCateg($body['libelle']);
            
            if ($result) {
                Flight::json([
                    'success' => true,
                    'message' => 'Catégorie créée avec succès'
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => 'Erreur lors de la création'
                ], 500);
            }
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour une catégorie
     */
    public function update() {
        try {
            $this->initCategorie();
            
            // Récupérer les données JSON du body
            $body = json_decode(file_get_contents('php://input'), true);
            
            if (empty($body['id']) || empty($body['libelle'])) {
                Flight::json([
                    'success' => false,
                    'error' => 'L\'ID et le libellé sont requis'
                ], 400);
                return;
            }

            $result = $this->categorie->updateCateg($body['id'], $body['libelle']);
            
            if ($result) {
                Flight::json([
                    'success' => true,
                    'message' => 'Catégorie mise à jour avec succès'
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => 'Erreur lors de la mise à jour'
                ], 500);
            }
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime une catégorie
     */
    public function delete() {
        try {
            $this->initCategorie();
            
            // Récupérer les données JSON du body
            $body = json_decode(file_get_contents('php://input'), true);
            
            if (empty($body['id'])) {
                Flight::json([
                    'success' => false,
                    'error' => 'L\'ID est requis'
                ], 400);
                return;
            }

            $result = $this->categorie->deleteCateg($body['id']);
            
            if ($result) {
                Flight::json([
                    'success' => true,
                    'message' => 'Catégorie supprimée avec succès'
                ]);
            } else {
                Flight::json([
                    'success' => false,
                    'error' => 'Erreur lors de la suppression'
                ], 500);
            }
        } catch (\Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
