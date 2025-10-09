<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function affiche()
    {
        $testModel = model('TestModel');
        $data = $testModel->findall();
        return view("test/listeTest",$data); //+,variable de la requête
    }

    public function delete()
    {
        //ajout de la classe model
        //requête delete avec l'id recupérer en post d'un test dans une variable
        redirect("liste-test");
    }

    public function modif()
    {
        //ajout de la classe model
        //requête select d'un id d'un test de la liste dans une variable
        return view("modif-test");
    }
    public function update()
    {
        //ajout de la classe model
        //requête update avec l'id récup en post d'un test dans une variable ainsi que le nouveau libéllé
        redirect("liste-test");
    }

    public function ajout()
    {
        return view("ajout-test");
    }

    public function create()
    {
        //ajout de la classe model
        //requête insert des infos du form
        redirect("liste-test");
    }
}
