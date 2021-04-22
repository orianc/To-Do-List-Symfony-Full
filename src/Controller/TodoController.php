<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     */
    public function index(): Response
    {   
        $all_todo = $this->getDoctrine()->getRepository(Todo::class)->findAll();
 
        return $this->render('todo/index.html.twig', [
            'todolist' => $all_todo,
        ]);

    }
    
}
