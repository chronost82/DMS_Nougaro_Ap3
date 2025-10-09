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
        return view("test/listeTest",["listeTest"=>$listeTest]); 
    }

    public function delete()
    {
         $testModel = model('TestTechniqueModel');
        $testModel->delete();
        redirect("test/liste-test");
    }

    public function modif($idTest)
    {
        $testModel = model('TestTechniqueModel');
        $testAModif = $testModel->find($idTest);
        dd($idTest);
        return view("test/modifTest",["testAModif"=>$testAModif]);
    }
    public function update()
    {
        $testModel = model('TestTechniqueModel');
        $testModel->update($_POST['id']);
        redirect("test/liste-test");
    }

    public function ajout()
    {
        return view("test/ajout-test");
    }

    public function create()
    {
        $testModel = model('TestTechniqueModel');
        $testModel->create();
        redirect("test/liste-test");
    }
}
