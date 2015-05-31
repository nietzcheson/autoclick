<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GuzzleHttp\Client;
//Request::setTrustedProxies(array('127.0.0.1'));

$app->match('/', function (Request $request) use ($app) {



    // $uri = 'http://www.vedeviaje.com/examen/rest/index.php';
    // $cliente = new Client(['base_uri' => $uri]);
    // $res = $cliente->post('/login', ['LogIn' => ['0123456789', '0123456789']]);
    //
    // echo $res->getBody();
    // exit();

    // $uri = 'http://www.vedeviaje.com/examen/rest/index.php';
    // $cliente = new Client(['base_uri' => $uri]);
    // $res = $cliente->post($uri, ['LogIn' => ['0123456789', '0123456789']]);
    //
    // echo $res->getBody();
    // exit();

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
        return $app->redirect($app['url_generator']->generate('checkout'));
        // if ($form->isValid()) {
        //     $data = $form->getData();
        //
        //     // do something with the data
        //
        //     // redirect somewhere
        //     exit();
        //     return $app['twig']->render('checkOut.twig', array('form' => $form->createView()));
        // }
    }

    return $app['twig']->render('checkIn.twig', array('form' => $form->createView()));
})
->bind('checking')
;

$app->match('/checkout', function (Request $request) use ($app) {

    return $app['twig']->render('checkOut.twig');
})
->bind('checkout')
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
