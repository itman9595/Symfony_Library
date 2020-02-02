<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\Type\BookType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class LibraryController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list() 
    {
        $title = "Список книг";
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findAll();
        
        // $books = [
        //     [
        //         "name" => "Гари Поттер и Узник Азкабана",
        //         "year" => 1999,
        //         "author" => "Джоан Роулинг"
        //     ],
        //     [
        //         "name" => "Маленький принц",
        //         "year" => 1943,
        //         "author" => "Антуан де Сент-Экзюпери"
        //     ],
        //     [
        //         "name" => "Процесс",
        //         "year" => 1925,
        //         "author" => "Франц Кафка"
        //     ]
        // ];

        return $this->render('library/list.html.twig', [
            'title'=>ucwords(str_replace('-', '', $title)),
            'books' => $books,
        ]);
    }

    /**
     * @Route("/add/", name="add")
     */
    public function add(Request $request) {
        $title = "Добавить книгу";
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('list');
        }

        return $this->render('library/add.html.twig', [
           'title'=>ucwords(str_replace('-', '', $title)),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/", name="edit")
     */
    public function edit(Request $request) {
        $title = "Список книг: редактирование";
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findAll();
        return $this->render('library/edit.html.twig', [
            'title'=>ucwords(str_replace('-', '', $title)),
            'books' => $books,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="editBook")
     */
    public function editBook(Request $request, $id) {
        $title = "Редактировать книгу";
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_book = $form->getData();
            $book = $entityManager->getRepository(Book::class)->find($new_book->getId());

            if (!$book) {
                throw $this->createNotFoundException(
                    'Книга с id '.$id.' не найдена.'
                );
            }

            $book = $new_book;
            $entityManager->flush();
            return $this->redirectToRoute('edit');
        }

        return $this->render('library/editBook.html.twig', [
           'title'=>ucwords(str_replace('-', '', $title)),
            'form' => $form->createView(),
        ]);
    }

}