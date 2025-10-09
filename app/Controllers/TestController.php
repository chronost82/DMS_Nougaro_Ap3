<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PHPUnit\Event\Code\TestMethod;

class TestController extends BaseController
{
    public function affiche()
    {
        $testModel = model('TestTechniqueModel');
        $listeTest = $testModel->findall();
        return view("test/listeTest",$listeTest); 
    }

    public function delete()
    {
         $testModel = model('TestTechniqueModel');
        $testModel->delete($_POST['id']);
        redirect("liste-test");
    }

    public function modif()
    {
         $testModel = model('TestTechniqueModel');
        $testAModif = $testModel->find();
        return view("modif-test",$testAModif);
    }
    public function update()
    {
        $testModel = model('TestTechniqueModel');
        $testModel->update($_POST['id']);
        redirect("liste-test");
    }

    public function ajout()
    {
        return view("ajout-test");
    }

    public function create()
    {
        $testModel = model('TestTechniqueModel');
        $testModel->create();
        redirect("liste-test");
    }
}
