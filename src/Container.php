<?php
namespace Ebb;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Container extends ContainerBuilder
{
    public function __construct()
    {
        parent::__construct();

        $this->setParameter('api.base_url', getenv('CIVI_REST_API_BASE_URL'));

        $this->register('api', 'Ebb\RestApi')
            ->setArguments(['%api.base_url%', new Reference('cache.api')]);

        $this->register('log.stream_handler', 'Monolog\Handler\StreamHandler')
            ->addArgument('php://stdout');

        $this->register('log', 'Monolog\Logger')
            ->addArgument('log')
            ->addMethodCall('pushHandler', array(new Reference('log.stream_handler')));

        $this->register('twig.loader_filesystem', 'Twig_Loader_Filesystem')
            ->addArgument('templates');

        $this->register('twig', 'Twig_Environment')
            ->addArgument(new Reference('twig.loader_filesystem'));

        $this->register('generator', 'Ebb\Generator')
            ->addArgument(new Reference('twig'));


        $this->register('angular_factory', 'Ebb\Angular\AngularFactory')
            ->addArgument(new Reference('api'))
            ->addArgument(new Reference('generator'))
            ->addArgument(getenv('INTERFACE_DIR'))
            ->addArgument(new Reference('log'));

        $this->register('cache.api', 'Symfony\Component\Cache\Simple\FilesystemCache')
            ->setArguments(['', 0, Kernel::getRootDir() . '/cache/api']);


        return $this;
    }
}
