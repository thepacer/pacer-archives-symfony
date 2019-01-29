<?php

namespace App\Controller\Admin;

use App\Entity\Issue;
use App\Form\IssueType;
use App\Repository\IssueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/issue")
 */
class IssueController extends Controller
{
    /**
     * @Route("/", name="admin_issue_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dql   = "SELECT i FROM App:Issue i";
        $query = $entityManager->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            50 /*limit per page*/
        );

        return $this->render('admin/issue/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/new", name="admin_issue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $issue = new Issue();
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issue);
            $entityManager->flush();

            return $this->redirectToRoute('admin_issue_index');
        }

        return $this->render('admin/issue/new.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_issue_show", methods={"GET"})
     */
    public function show(Issue $issue): Response
    {
        return $this->render('admin/issue/show.html.twig', ['issue' => $issue]);
    }

    /**
     * @Route("/{id}/edit", name="admin_issue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Issue $issue): Response
    {
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_issue_index', ['id' => $issue->getId()]);
        }

        return $this->render('admin/issue/edit.html.twig', [
            'issue' => $issue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_issue_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Issue $issue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$issue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($issue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_issue_index');
    }
}
