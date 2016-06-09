<?php
namespace GC\ConcertManagerBundle\Controller;

use GC\ConcertManagerBundle\Entity\Concert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ConcertController extends Controller
{
	public function indexAction()
	{
		$repository = $this
		->getDoctrine()
		->getManager()
		->getRepository('GCConcertManagerBundle:Concert')
		;
		
		$listConcert = $repository->findAll();
		
		return $this->render('GCConcertManagerBundle:Concert:index.html.twig', array('listConcert' => $listConcert));
	}
	
	public function viewAction($id)
	{
		$repository = $this->getDoctrine()
		->getManager()
		->getRepository('GCConcertManagerBundle:Concert')
		;
		
		$concert = $repository->find($id);
		
		if (null === $concert) {
			throw new NotFoundHttpException("Le concert d'id ".$id." n'existe pas.");
		}
		
		return $this->render('GCConcertManagerBundle:Concert:view.html.twig', array(
				'concert' => $concert
		));
		
	}
	public function editAction(Request $request,$id)
	{

			$concert = $this->getDoctrine()
			 ->getManager()
			 ->getRepository('GCConcertManagerBundle:Concert')
			 ->find($id);
			
			 $form = $this->get('form.factory')->createBuilder(FormType::class, $concert)
			 ->add('date',      DateType::class)
			 ->add('titre',     TextType::class)
			 ->add('adresse',   TextareaType::class)
			 ->add('description',    TextareaType::class)
			 ->add('latitude', HiddenType::class)
			 ->add('longitude',      HiddenType::class)
			 ->add('save',      SubmitType::class)
			 ->getForm();
			 ;
			 
			 if ($request->isMethod('POST')) {

			 	$form->handleRequest($request);
			 

			 	if ($form->isValid()) {

			 		$em = $this->getDoctrine()->getManager();
			 		$em->persist($concert);
			 		$em->flush();
			 
			 		$request->getSession()->getFlashBag()->add('notice', 'Concert bien modifié.');
			 

			 		return $this->redirectToRoute('concert_manager_view', array('id' => $concert->getId()));
			 	}
			 }
			 
			 return $this->render('GCConcertManagerBundle:Concert:edit.html.twig', array(
			 		'form' => $form->createView(),
			 ));
	}
	public function addAction(Request $request)
	{
	$concert = new Concert();
	
		$form = $this->get('form.factory')->createBuilder(FormType::class, $concert)
		->add('date',      DateType::class)
		->add('titre',     TextType::class)
		->add('adresse',   TextareaType::class)
		->add('description',    TextareaType::class)
		->add('latitude', HiddenType::class)
		->add('longitude',      HiddenType::class)
		->add('enregistrer',      SubmitType::class)
		->getForm();
		;
		
    if ($request->isMethod('POST')) {
  
      $form->handleRequest($request);

      if ($form->isValid()) {

        $em = $this->getDoctrine()->getManager();
        $em->persist($concert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'concert bien enregistré.');

        return $this->redirectToRoute('concert_manager_view', array('id' => $concert->getId()));
      }
    }
		
		return $this->render('GCConcertManagerBundle:Concert:add.html.twig', array(
				'form' => $form->createView(),
		));
	}
	
	public function deleteAction(Request $request,$id)
	{
		
		$concert = $this->getDoctrine()
			 ->getManager()
			 ->getRepository('GCConcertManagerBundle:Concert')
			 ->find($id);
		
		$em = $this->getDoctrine()->getManager();
		
		$em->remove($concert);
		
		$em->flush();
		
		$request->getSession()->getFlashBag()->add('notice', 'concert supprimé.');
		
		return $this->redirectToRoute('concert_manager_homepage');
	}
	
}