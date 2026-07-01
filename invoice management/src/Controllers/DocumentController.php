<?php
// src/Controllers/DocumentController.php

namespace Controllers;

use Core\Controller;
use Models\Document;
use Helpers\Logger;

class DocumentController extends Controller {
    public function upload() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) $this->redirect('/');

        $projectId = $_POST['project_id'] ?? null;
        if (!$projectId) {
            $this->redirect('/projects');
            return;
        }

        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/projects/' . $projectId . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($_FILES['document']['name']);
            $targetPath = $uploadDir . time() . '_' . $fileName;
            $dbPath = '/uploads/projects/' . $projectId . '/' . time() . '_' . $fileName;

            if (move_uploaded_file($_FILES['document']['tmp_name'], $targetPath)) {
                $docModel = new Document();
                $docModel->create([
                    'project_id' => $projectId,
                    'file_name' => $fileName,
                    'file_path' => $dbPath,
                    'file_type' => $_POST['file_type'] ?? 'Other',
                    'uploaded_by' => $_SESSION['user_id']
                ]);

                Logger::log("Document Upload", "File '$fileName' added to project #$projectId");
                $_SESSION['success'] = "Document uploaded successfully.";
            } else {
                $_SESSION['error'] = "Error saving file.";
            }
        } else {
            $_SESSION['error'] = "No file selected or transfer error.";
        }

        $this->redirect('/projects/show?id=' . $projectId);
    }
}
