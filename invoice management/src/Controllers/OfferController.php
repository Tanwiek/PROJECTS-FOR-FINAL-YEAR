<?php
// src/Controllers/OfferController.php

namespace Controllers;

use Core\Controller;

class OfferController extends Controller {
    public function index() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $projectId = $_GET['project'] ?? null;
        $data = [
            'title' => 'Offer Management',
            'project_id' => $projectId,
            'offers' => []
        ];

        $this->render('offers.index', $data);
    }

    public function create() {
        if (!isset($_SESSION)) session_start();
        $projectId = $_GET['project'] ?? null;
        $type = $_GET['type'] ?? 'admin';
        
        $data = [
            'title' => "Create Offer " . ucfirst($type),
            'project_id' => $projectId,
            'type' => $type
        ];
        $this->render('offers.create', $data);
    }

    public function store() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $projectId = $_POST['project_id'] ?? null;
        if (!$projectId) {
            $this->redirect('/projects');
            return;
        }

        if (isset($_FILES['offer_file']) && $_FILES['offer_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/offers/' . $projectId . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($_FILES['offer_file']['name']);
            $uniqueName = time() . '_' . $fileName;
            $targetPath = $uploadDir . $uniqueName;
            $dbPath = '/uploads/offers/' . $projectId . '/' . $uniqueName;

            if (move_uploaded_file($_FILES['offer_file']['tmp_name'], $targetPath)) {
                $offerModel = new \Models\Offer();
                $offerModel->create([
                    'project_id' => $projectId,
                    'title' => $_POST['offer_title'] ?? 'Untitled Offer',
                    'type' => $_POST['type'] ?? 'admin',
                    'file_path' => $dbPath,
                    'amount' => $_POST['total_amount'] ?? 0,
                    'status' => 'Pending'
                ]);

                $_SESSION['success'] = "Offer successfully recorded.";
            } else {
                $_SESSION['error'] = "Error saving file.";
            }
        } else {
            $_SESSION['error'] = "No file selected or transfer error.";
        }

        $this->redirect('/projects/show?id=' . $projectId);
    }
}
