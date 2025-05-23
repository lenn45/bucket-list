<?php 
namespace App\Controller; 
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController 
{ 
    #[Route(path: '/', name: 'home')]

    public function home():Response
    {

        return $this->render("default/home.html.twig");
    }

    #[Route(path: '/test/yo', name: 'test')]
    public function test() 
    { 
        
        return $this->render("default/test.html.twig"); 
    }

    #[Route(path: '/about-us', name: 'about')]
    public function about() 
    { 
        
        return $this->render("default/about-us.html.twig"); 
    }
}
?>