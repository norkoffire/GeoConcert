<?php
namespace GC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GC\ConcertManagerBundle\GCConcertManagerBundle;

class CoreController extends Controller
{
	public function indexAction()
	{
		return $this->render('GCConcertManagerBundle:Concert:index.html.twig');
	}
	
}