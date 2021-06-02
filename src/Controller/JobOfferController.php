<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Form\JobOfferType;
use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class JobOfferController extends AbstractController
{
	/**
	 * @Route("/", name="job_offer_index", methods={"GET"})
	 * @param Request $request
	 * @param JobOfferRepository $jobOfferRepository
	 * @param PaginatorInterface $paginator
	 * @return Response
	 */
	public function index(Request $request ,JobOfferRepository $jobOfferRepository, PaginatorInterface $paginator): Response
	{
		$pagination = $paginator->paginate(
			$jobOfferRepository->findBy([], ['created_at' => 'ASC']),
			$request->query->getInt('page', 1),
			8
		);

		return $this->render('job_offer/index.html.twig', [
			'pagination' => $pagination
		]);
	}

    /**
     * @Route("/joboffer/new", name="job_offer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jobOffer);
            $entityManager->flush();

            return $this->redirectToRoute('job_offer_index');
        }

        return $this->render('job_offer/new.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/joboffer/{id}", name="job_offer_show", methods={"GET"})
	 * @param JobOffer $jobOffer
	 * @param Request $request
	 * @return Response
	 */
    public function show(JobOffer $jobOffer, Request $request): Response
    {
        return $this->render('job_offer/show.html.twig', [
            'job_offer' => $jobOffer,
	        'previous_page' => $request->get('previous_page')
        ]);
    }

    /**
     * @Route("/joboffer/{id}/edit", name="job_offer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, JobOffer $jobOffer): Response
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_offer_index');
        }

        return $this->render('job_offer/edit.html.twig', [
            'job_offer' => $jobOffer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/joboffer/{id}", name="job_offer_delete", methods={"POST"})
     */
    public function delete(Request $request, JobOffer $jobOffer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobOffer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jobOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_offer_index');
    }
}
