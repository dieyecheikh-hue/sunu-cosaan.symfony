<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_admin_settings', methods: ['GET', 'POST'])]
    public function index(SettingsRepository $settingsRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $settingsMap = [];
        foreach ($settingsRepository->findAll() as $setting) {
            $settingsMap[$setting->getName()] = $setting;
        }

        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            foreach ($data as $name => $value) {
                if ('_token' === $name) {
                    continue;
                }

                if (isset($settingsMap[$name])) {
                    $settingsMap[$name]->setValue($value);
                } else {
                    $setting = new Settings();
                    $setting->setName($name);
                    $setting->setValue($value);
                    $entityManager->persist($setting);
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Paramètres mis à jour !');

            return $this->redirectToRoute('app_admin_settings');
        }

        return $this->render('pages/admin/settings/index.html.twig', [
            'settings' => $settingsMap,
        ]);
    }
}
