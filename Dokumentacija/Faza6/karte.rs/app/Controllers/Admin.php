<?php namespace App\Controllers;

use App\Models\User;
use App\Models\News;
use App\Models\Role;
use App\Models\Roles;

class Admin extends Moderator{

    protected function prikaz($page, $data)
    {
        $data['controller']='Admin';
        $data['user']=$this->session->get('user');
        echo view('Prototip/header_admin', $data);
        echo view("Prototip/$page", $data);
        echo view("Prototip/footer", $data);

    }
    
     public function pretragaOglasa()
    {
        $ime = $this->request->getVar('pretraziKorisnike');
        $this->method = 'dodajOglas';
        if($ime==null)
            $ime="";
        $newsDB = new News();
        $news = $newsDB->pretragaOglasa(5,$ime);
        $data = [
            'news' => $news,
            'pager' => $newsDB->pager
        ];

        $this->prikaz("adminOglasi", ['data'=>$data,'method' =>  $this->method ,'admin'=>  $korime = $this->session->get('user')->KorIme, 'oglasi'=>$news]);
    }
    

    public function pretragaKorisnika()
    {
        $ime = $this->request->getVar('pretraziKorisnike');
        if($ime==null)
            $ime="";

         
            $userDB = new User();
            $uloga = $userDB->paginateUsers(5, $ime);
        
            $data = [
                'news' => $uloga,
                'pager' => $userDB->pager
            ];

        $this->prikaz("adminMode", ['data'=>$data,'method' =>  $this->method ,'admin'=>  $korime = $this->session->get('user')->KorIme, 'korisnici'=>$uloga]);

    }
    public function adminMode()
    {
        $this->method = 'dodajOglas';
        $userDB = new User();
        /*$db= \Config\Database::connect();
        $builder = $db->table('Ima_ulogu');
        $builder->select('*')
            ->join('Korisnik', 'Korisnik.KorIme=Ima_ulogu.KorIme', 'left')
            ->join('Uloga', 'Uloga.Idu=Ima_ulogu.Idu', 'left')
            ->where('Uloga.Opis',"Korisnik")->orWhere("Uloga.Opis" , "Moderator");
        $uloga = $builder->get()->getResult();
        $newsDB = new News();*/
        $uloga = $userDB->paginateUsers(5);
        
        $data = [
            'news' => $uloga,
            'pager' => $userDB->pager
        ];

        $this->prikaz("adminMode", ['data'=>$data,'method' =>  $this->method ,'admin'=>  $korime = $this->session->get('user')->KorIme, 'korisnici'=>$uloga]);
    }

    public function adminOglasi()
    {
        $this->method = 'dodajOglas';
        $newsDB = new News();
        $news = $newsDB->oglasi(5);
        $data = [
            'news' => $news,
            'pager' => $newsDB->pager
        ];

        //$news = $newsDB->where("Tip" , "O")->findAll();
        $this->prikaz("adminOglasi", ['data'=>$data,'method' =>  $this->method ,'admin'=>  $korime = $this->session->get('user')->KorIme, 'oglasi'=>$news]);


    }
  
   
    public function dodajModeratora()
    {
        $site = $_POST['favorite'];
        $role = new Role();
        foreach ($site as $moderator) {
            $role->where("KorIme", $moderator);
            $role->set(
                [
                    "Idu"=>2
                ]
            );
               $role->update();
               }
        //$this->session->set_userdata('site', $site);
        $response['favorite'] = $site;

        echo json_encode($response);
    }
    public function oduzmiModeratora()
    {
        $site = $_POST['favorite'];
        $role = new Role();
        foreach ($site as $moderator) {
            $role->where("KorIme", $moderator);
            $role->set(
                [
                    "Idu"=>3
                ]
            );
            $role->update();
        }
        //$this->session->set_userdata('site', $site);
        $response['favorite'] = $site;

        echo json_encode($response);
    }

    public function ukloni()
    {
        $site = $_POST['favorite'];
        $role = new Role();
        $user = new User();
        $news = new News();
        foreach ($site as $moderator) {
            $role->where("KorIme", $moderator)->delete();
            $news->where("KorIme", $moderator)->delete();
             /*$user*/$role->where("KorIme", $moderator)->delete();
            //Brisanje transakcija korisnika ili setovanje NULL-ova u bazi :D
        }
        //$this->session->set_userdata('site', $site);
        $response['favorite'] = $site;

        echo json_encode($response);
    }
}

?>