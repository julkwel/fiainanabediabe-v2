<?php
/**
 * @author Bocasay jul
 * Date : 14/10/2023
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard()
    {
        return $this->render('admin/dashboard/index.html.twig', ['']);
    }
}