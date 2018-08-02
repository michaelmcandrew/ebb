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
            ->addArgument('php://stderr');

        $this->register('log', 'Monolog\Logger')
            ->addArgument('log')
            ->addMethodCall('pushHandler', array(new Reference('log.stream_handler')));

        $this->register('twig.loader_filesystem', 'Twig_Loader_Filesystem')
            ->addArgument('templates');

        $this->register('twig', 'Twig_Environment')
            ->addArgument(new Reference('twig.loader_filesystem'));

        $this->register('generator', 'Ebb\Generator')
            ->addArgument(new Reference('twig'));

        /* deprecated in favour of angular schematics */
        $this->register('angular_factory', 'Ebb\Angular\AngularFactory')
            ->addArgument(new Reference('api'))
            ->addArgument(getenv('INTERFACE_DIR'))
            ->addArgument(new Reference('log'))
            ->addArgument(new Reference('generator'));

        $this->register('civicrm_schema_factory', 'Ebb\CiviCRMSchemaFactory')
            ->addArgument(new Reference('api'))
            ->addArgument(Kernel::getRootDir() . '/out')
            ->addArgument(new Reference('log'));

        $this->register('cache.api', 'Symfony\Component\Cache\Simple\FilesystemCache')
            ->setArguments(['', 0, Kernel::getRootDir() . '/cache/api']);


        return $this;
    }
}
