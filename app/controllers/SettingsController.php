<?php

namespace app\controllers;

use app\models\Setting;

class SettingsController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new Setting();
    }

    public function index()
    {
        $settings = $this->settingModel->getSettings();
        require_once __DIR__ . '/../views/admin/settings.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'rt_name' => $_POST['rt_name'] ?? '',
                'rt_number' => $_POST['rt_number'] ?? '',
                'rw_number' => $_POST['rw_number'] ?? '',
                'district' => $_POST['district'] ?? '',
                'city' => $_POST['city'] ?? '',
                'province' => $_POST['province'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'maintenance_mode' => $_POST['maintenance_mode'] ?? 0,
                'registration_enabled' => $_POST['registration_enabled'] ?? 1,
            ];

            if ($this->settingModel->saveSettings($data)) {
                $_SESSION['success'] = 'Settings updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update settings.';
            }

            header('Location: /admin/settings');
            exit;
        }
    }
}