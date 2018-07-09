<?php
namespace Ebb;

use \Twig_Environment;

class Generator
{
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function init($templateDefinitions, $outputDir)
    {
        $this->outputDir = $outputDir;
        foreach ($templateDefinitions as $key => $def) {
            $this->definitions[$key]['filename'] = $this->twig->createTemplate($def['filename']);
            $this->definitions[$key]['template'] = $this->twig->load($def['template']);
        }
    }

    public function generate($key, $params)
    {
        $filename = $this->getTemplateFilename($key, $params);
        $output = $this->getTemplateOutput($key, $params);
        file_put_contents($filename, $output);
    }

    protected function getTemplateFilename($key, $params)
    {
        return $this->outputDir . '/' .  $this->definitions[$key]['filename']->render($params);
    }

    protected function getTemplateOutput($key, $params)
    {
        return $this->definitions[$key]['template']->render($params);
    }
}
