<?php

    namespace App\EventListener;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
    use Twig\Environment;
    use Psr\Log\LoggerInterface;

    class ExceptionListener
    {

        private $_engine;
        private $logger;

        public function __construct(Environment $engine, LoggerInterface $logger)
        {
            $this->_engine = $engine;
            $this->logger = $logger;
        }

        public function onKernelException(ExceptionEvent $event)
        {
            // You get the exception object from the received event
            $exception = $event->getThrowable();

            // Customize your response object to display the exception details
            $response = new Response();
            $this->logger->error("Logger => " .$exception->getMessage() .' code => '.$exception->getCode());


            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                if($exception->getStatusCode() == 404){
                    $response->setContent($this->_engine->render('error/404.html.twig'));
                } else {
                    $response->setContent($this->_engine->render('error/error.html.twig', ['code' => $exception->getStatusCode(), 'msg' => $exception->getMessage()]));
                }
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setContent($this->_engine->render('error/error.html.twig', ['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'msg' => $exception->getMessage()]));
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }