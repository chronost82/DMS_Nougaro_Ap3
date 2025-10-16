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
        return view("test/listeTest", ["listeTest" => $listeTest]);
    }

    public function delete($idTest)
    {
        $testModel = model('TestTechniqueModel');
        $testModel->delete($idTest);
       return redirect("test-liste");
    }

    public function modif($idTest)
    {
        $testModel = model('TestTechniqueModel');
        $testAModif = $testModel->find($idTest);
        return view("test/modifTest", ["testAModif" => $testAModif]);
    }

     public function update()
    {
        $testModel = model('TestTechniqueModel');

        $testAModif = [
            'IDTESTTECHNIQUE' => $this->request->getPost('id'),
            'LIBELLE' => $this->request->getPost('libelle')
        ];
        $testModel->save($testAModif);
        return redirect('test-liste');
    }

    public function ajout()
    {
        return view("test/ajoutTest");
    }

    public function create()
    {
        $testModel = model('TestTechniqueModel');
        $ajoutTest =[
            'LIBELLE'=> $this->request->getPost('libelle')
        ];
        $testModel->save($ajoutTest);

        return redirect('test-liste');
    }
}
