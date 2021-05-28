<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
	/**
	 * @Route("/", name="company_index", methods={"GET"})
	 * @param CompanyRepository $companyRepository
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
    public function index(CompanyRepository $companyRepository, PaginatorInterface $paginator, Request $request): Response
    {
	    $pagination = $paginator->paginate(
		    $companyRepository->findBy([], ['name' => 'ASC']),
		    $request->query->getInt('page', 1),
		    8
	    );

        return $this->render('company/index.html.twig', [
	        'pagination' => $pagination
        ]);
    }

	/**
	 * @Route("/new", name="company_new", methods={"GET","POST"})
	 * @param Request $request
	 * @return Response
	 */
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/{id}", name="company_show", methods={"GET"})
	 * @param Company $company
	 * @param PaginatorInterface $paginator
	 * @param Request $request
	 * @return Response
	 */
    public function show(Company $company, PaginatorInterface $paginator, Request $request): Response
    {
	    $pagination = $paginator->paginate(
		    $company->getJobOffer(),
		    $request->query->getInt('page', 1),
		    5
	    );

        return $this->render('company/show.html.twig', [
            'company' => $company,
	        'pagination' => $pagination,
	        'previous_page' => $request->get('previous_page')
        ]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="company_delete", methods={"POST"})
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('company_index');
    }
}
