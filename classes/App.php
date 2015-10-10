<?php

class App {

    private static $slim;

    public static function instance() {

        if (self::$slim === null) {

            $app = new Slim\App();

            $container = $app->getContainer();

            $container['view'] = function ($c) {
                $view = new \Slim\Views\Twig(
                    DOCROOT . 'resources/views', 
                    [
                        'cache' => DOCROOT . 'resources/cache'
                    ]
                );

                $view->addExtension(new Slim\Views\TwigExtension(
                    $c['router'],
                    $c['request']->getUri()
                ));

                return $view;
            };

            self::$slim = $app;

        }

        return self::$slim;
    }

}