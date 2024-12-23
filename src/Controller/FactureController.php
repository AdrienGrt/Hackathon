<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(
        Request $request,
        FactureRepository $factureRepository,
        PaginatorInterface $paginator
    ): Response {
        $search = $request->query->get('search');
        $itemsPerPage = $request->query->getInt('items_per_page', 10);
        $sort = $request->query->get('sort', 'id');
        $direction = $request->query->get('direction', 'asc');

        // Construction de la requête avec les filtres et tri
        $queryBuilder = $factureRepository->createQueryBuilder('f')
            ->leftJoin('f.client', 'c')
            ->addSelect('c');

        if ($search) {
            $queryBuilder
                ->andWhere('c.nom LIKE :search OR f.statut LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Application du tri
        if ($sort === 'client.nom') {
            $queryBuilder->orderBy('c.nom', $direction);
        } else {
            $queryBuilder->orderBy('f.' . $sort, $direction);
        }

        $query = $queryBuilder->getQuery();

        // Pagination
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $itemsPerPage
        );

        return $this->render('facture/index.html.twig', [
            'pagination' => $pagination,
            'items_per_page' => $itemsPerPage,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType ::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $facture->getId(), $request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
