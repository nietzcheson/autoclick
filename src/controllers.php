<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));




$app->get('/', function (Request $request) use ($app) {

  $oficinas = array(
    'Acapulco - Aeropuerto',
    'Aguascalientes - Aeropuerto',
    'Aguascalientes Centro'
  );

  $hora = array();

  for($i = 0; $i < 24; $i++){

    if($i < 10){
      $hora[$i] = '0'.$i.':00';
    }else{
      $hora[$i] = $i.':00';
    }
  }


  $form = $app['form.factory']->createBuilder('form')
      ->add('entrega', 'choice', array(
          'choices' => array($oficinas),
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control selectpicker'
          ),
          'empty_value' => 'Seleccione',
          'label' => 'Oficina Entrega:'
      ))
      ->add('fechaEntrega', 'text', array(
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control datepicker'
          ),
          'label' => 'Entrega:',
          'data' => date_format(new \Datetime('now'), 'd/m/Y')
      ))
      ->add('horaEntrega', 'choice', array(
          'choices' => array($hora),
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control selectpicker'
          ),
          'empty_value' => 'Seleccione',
          'label' => 'Hora:',
          'data' => 10
      ))
      ->add('devolucion', 'choice', array(
          'choices' => array($oficinas),
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control selectpicker'
          ),
          'label' => 'Oficina Devolución:'
      ))
      ->add('fechaDevolucion', 'text', array(
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control datepicker'
          ),
          'label' => 'Devolución:',
          'data' => date_format(new \Datetime('now'), 'd/m/Y')
      ))
      ->add('horaDevolucion', 'choice', array(
          'choices' => array($hora),
          //'expanded' => true,
          'attr' => array(
            'class' => 'form-control selectpicker'
          ),
          'empty_value' => 'Seleccione',
          'label' => 'Hora:',
          'data' => 10
      ))
      ->getForm();

  if ('POST' == $request->getMethod()) {
      $form->bind($request);

      if ($form->isValid()) {
          $data = $form->getData();

          // do something with the data

          // redirect somewhere
          return $app->redirect('...');
      }
  }

    return $app['twig']->render('checkIn.twig', array('form' => $form->createView()));
})
->bind('homepage')
;

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html',
        'errors/'.substr($code, 0, 2).'x.html',
        'errors/'.substr($code, 0, 1).'xx.html',
        'errors/default.html',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
