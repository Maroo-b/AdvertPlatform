<?php

namespace MB\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
  public function indexAction($page)
  {
    if($page < 1){
      throw new NotFoundHttpException('Page"'. $page. '" inexistant.');
    }

    $listAdverts = array(
          array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => 1,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()),
          array(
            'title'   => 'Mission de webmaster',
            'id'      => 2,
            'author'  => 'Hugo',
            'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
            'date'    => new \Datetime()),
          array(
            'title'   => 'Offre de stage webdesigner',
            'id'      => 3,
            'author'  => 'Mathieu',
            'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
            'date'    => new \Datetime())
        );
    return $this->render('MBPlatformBundle:Advert:index.html.twig',
    array( 'listAdverts' => $listAdverts ));
  }

  public function viewAction($id)
  {

    $advert = array(
          'title'   => 'Recherche développpeur Symfony2',
          'id'      => $id,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()
        );
    return $this->render('MBPlatformBundle:Advert:view.html.twig',
    array( 'advert' => $advert ));
  }

  public function addAction(Request $request)
  {
    if ($request->isMethod('POST')){
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistré');
      return $this->redirectToRoute('mb_platform_view', 
      array( 'id' => $id ));
    }

    return $this->render('MBPlatformBundle:Advert:add.html.twig');
  }

  public function editAction($id, Request $request)
  {
    if($request->isMethod('POST')){
      $request->getSession()->getFlashBag()->add('notice','annonce bien modifié');
      return $this->redirectToRoute('mb_platform_view',
      array( 'id' => $id ));
    }
    
     $advert = array(
          'title'   => 'Recherche développpeur Symfony2',
          'id'      => $id,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()
        );
    return $this->render('MBPlatformBundle:Advert:edit.html.twig',
    array( 'advert' => $advert ));
  }

  public function delete($id)
  {
    return $this->render('MBPlatformBundle:Advert:delete.html.twig');
  }

  public function menuAction($limit)
  {
    $listAdverts = array(
      array('id' => 2 , 'title' => 'Recherche développeru Symfony 2'),
      array('id' => 4, 'title' => 'Mission RoR'),
      array('id' => 5, 'title' => 'Offre stage')
    );
    return $this->render('MBPlatformBundle:Advert:menu.html.twig',
    array('listAdverts' => $listAdverts));
  }
}
