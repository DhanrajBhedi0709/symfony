<?php

    namespace App\EventListener;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Event\ExceptionEvent;
    use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
    use Twig\Environment;
    use Psr\Log\LoggerInterface;

    /**
     * Class ExceptionListener
     * @package App\EventListener
     */
class ExceptionListener
{
    /**
     * @var Environment
     */
    private $_engine;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ExceptionListener constructor.
     * @param Environment $engine
     * @param LoggerInterface $logger
     */
    public function __construct(Environment $engine, LoggerInterface $logger)
    {
        $this->_engine = $engine;
        $this->logger = $logger;
    }

    /**
     * onKernelException method is used for handling the exception generated in portal.
     *
     * @param ExceptionEvent $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new Response();
        $this->logger->error("Logger => " . $exception->getMessage() . ' code => ' . $exception->getCode());


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getStatusCode() == 404) {
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
