<?php

declare(strict_types=1);

namespace Blue\Core\Http;

use Blue\Core\Application\AbstractSnapp;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FaviconHandler implements RequestHandlerInterface
{
    private string $imagePath;
    private int $width;
    private int $height;
    private string $format;

    public function __construct(string $imagePath, int $width = 32, int $height = 32, string $format = 'ico')
    {
        $this->imagePath = $imagePath;
        $this->width = $width;
        $this->height = $height;
        $this->format = $format;
    }

    public static function addRoutes(AbstractSnapp $application, string $imagePath)
    {
        $application->get('/favicon.ico', new FaviconHandler($imagePath));
        $application->get('/apple-touch-icon.png', new FaviconHandler($imagePath, 180, 180, 'png'));
        $application->get('/icon-192.png', new FaviconHandler($imagePath, 192, 192, 'png'));
        $application->get('/icon-512.png', new FaviconHandler($imagePath, 512, 512, 'png'));
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $manager = new ImageManager(['driver' => 'imagick']);
        $image = $manager->make($this->imagePath);
        $image->fit($this->width, $this->height);
        return $image->psrResponse($this->format);
    }
}
