<?php

namespace Ebb;

class Kernel
{
    public function getRootDir()
    {
        return realpath(__DIR__.'/../');
    }
}
