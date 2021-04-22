<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="app_todo")
     */
    public function index(): Response
    {
        $all_todo = $this->getDoctrine()->getRepository(Todo::class)->findAll();

        return $this->render('todo/index.html.twig', [
            'todolist' => $all_todo,
        ]);
    }

    /**
     * @Route("/todo/{id}", name="app_todo_show")
     */
    public function show($id, TodoRepository $todoRepository): Response
    {
        //dd($id);
        $todo = $todoRepository->find($id);
        return $this->render('todo/show.html.twig', [
            'todo' => $todo
        ]);

        
    }

    /**
     * @Route("/test/{v}", name="app_test")
     */
    public function test($v){
        dd($v);
    }
}
