<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="homepage")
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

        return $this->render('home/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
}
