<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Todo;
use App\Form\TodoFormType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/", "/todo" ,"/todo/list", name="app_todo")
     */
    public function index(): Response
    {
        $all_todo = $this->getDoctrine()->getRepository(Todo::class)->findAll();

        return $this->render('todo/index.html.twig', [
            'todolist' => $all_todo,
        ]);
    }

    /**
     * @Route("/todo/create", name="app_todo_create", methods={"GET", "POST"})
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        # Etape d'affichage (GET)
        $todo = new Todo;
        $form = $this->createForm(TodoFormType::class, $todo);

        # Etape submit (POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            // $this->getDoctrine()->getManager()->persist($todo);

            $em->persist($todo);
            $em->flush();
            return $this->redirectToRoute('app_todo');
        }

        return $this->render('todo/create.html.twig', [
            'newTodoForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/todo/show/{id}", name="app_todo_show")
     */
    public function show($id, TodoRepository $todoRepository): Response
    {
        $todo = $todoRepository->find($id);
        return $this->render('todo/show.html.twig', [
            'todo' => $todo
        ]);
    }


    /** Paramconverter => correspondance entre un id dans la route et un objet du type de ToDo.
     * 
     * 
     * @Route("/todo/update/{id}", name="app_todo_update", methods={"GET", "POST"})
     */
    public function update(Todo $todo, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoFormType::class, $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('app_todo');
        }
        return $this->render('todo/update.html.twig', [
            'formTodo' => $form->createView()
        ]);
    }
}
