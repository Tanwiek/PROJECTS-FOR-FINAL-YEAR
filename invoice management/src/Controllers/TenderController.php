<?php
// src/Controllers/TenderController.php

namespace Controllers;

use Core\Controller;

class TenderController extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $data = [
            'title' => "Tenders",
            'tenders' => [] // Sera récupéré du modèle plus tard
        ];

        $this->render('tenders.index', $data);
    }

    public function create() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $data = [
            'title' => "New Tender"
        ];

        $this->render('tenders.create', $data);
    }

    public function store() {
        // Gérer la soumission du formulaire et le téléversement de documents
        session_start();
        // Rediriger pour l'instant
        $this->redirect('/tenders');
    }
}
