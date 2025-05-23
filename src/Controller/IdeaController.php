<?php 

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\BucketList;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\BucketListType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Repository\CategoryRepository;
use App\Entity\Idea;




class IdeaController extends AbstractController 
{

    #[Route(path: '/list', name: 'list')]
    public function list(ManagerRegistry $doctrine) 
    {
        // Récupère le repository pour l'entité BucketList
        $ideaRepo = $doctrine->getRepository(BucketList::class);
        // Récupère toutes les idées
        $ideas = $ideaRepo->findAll();

        return $this->render("idea/list.html.twig", [
            'ideas' => $ideas,
        ]);
    }

    #[Route(path: '/detail/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, ManagerRegistry $doctrine) 
    {
        // Récupère le repository pour l'entité BucketList
        $ideaRepo = $doctrine->getRepository(BucketList::class);
        // Récupère une idée spécifique par son ID
        $idea = $ideaRepo->find($id);

        if (!$idea) {
            throw $this->createNotFoundException("L'idée avec l'ID $id n'existe pas.");
        }

        return $this->render("idea/detail.html.twig", [
            'idea' => $idea,
        ]);
    }


    #[Route(path: '/add', name: 'idea_add')]
public function add(Request $request, EntityManagerInterface $em): Response
{
    $idea = new BucketList();  // Assurez-vous d'utiliser BucketList ici

    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Créer le formulaire et passer l'utilisateur connecté comme option
    $form = $this->createForm(BucketListType::class, $idea, [
        'user' => $user,  // Passe l'utilisateur comme option au formulaire
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $idea->setIsPublished(true);
        $idea->setDateCreated(new \DateTime());
        $em->persist($idea);
        $em->flush();

        $this->addFlash('success', 'Idée ajoutée avec succès !');
        return $this->redirectToRoute('detail', ['id' => $idea->getId()]);  // Assurez-vous d'utiliser la bonne route
    }

    return $this->render('idea/add.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/category', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('category_crud/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }


    #[Route('/idea/delete/{id}', name: 'idea_delete', methods: ['POST', 'DELETE'])]
public function delete(BucketList $idea, EntityManagerInterface $em, Request $request): Response
{
    if ($this->isCsrfTokenValid('delete'.$idea->getId(), $request->request->get('_token'))) {
        $em->remove($idea);
        $em->flush();
        $this->addFlash('success', 'Idée supprimée avec succès !');
    } else {
        $this->addFlash('error', 'Échec de la validation du token CSRF.');
    }

    return $this->redirectToRoute('list'); // Remplacez par le nom de votre route de liste.
}


#[Route('/idea/edit/{id}', name: 'idea_edit', methods: ['GET', 'POST'])]
public function edit(BucketList $idea, Request $request, EntityManagerInterface $em): Response
{
    // Crée le formulaire avec les données existantes
    $form = $this->createForm(BucketListType::class, $idea);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistre les modifications
        $em->flush();

        $this->addFlash('success', 'Idée mise à jour avec succès !');

        // Redirige vers la page de détails ou de liste
        return $this->redirectToRoute('detail', ['id' => $idea->getId()]);
    }

    return $this->render('idea/edit.html.twig', [
        'form' => $form->createView(),
        'idea' => $idea,
    ]);
}




    

}
