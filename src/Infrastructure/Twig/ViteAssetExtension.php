<?php

declare(strict_types=1);

namespace App\Infrastructure\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    /** @var array<string, array{file: string, css: string[], src: string, isEntry: bool, name: string}> */
    private array $manifest = [];
    private string $manifestPath = '';

    public function __construct(private KernelInterface $kernel)
    {
        $this->manifestPath = $this->kernel->getProjectDir().'/public/build/.vite/manifest.json';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getJsAssetPath']),
            new TwigFunction('vite_asset_css', [$this, 'getCssAssetPaths']),
        ];
    }

    public function getJsAssetPath(string $entry): string
    {
        $manifest = $this->getManifest();
        if (!isset($manifest[$entry]['file'])) {
            throw new \InvalidArgumentException("Vite entry '$entry' not found in manifest.");
        }

        return '/build/'.$manifest[$entry]['file'];
    }

    /**
     * @return string[]
     */
    public function getCssAssetPaths(string $entry): array
    {
        $manifest = $this->getManifest();
        if (!isset($manifest[$entry]['css'])) {
            return [];
        }

        return array_map(
            fn (string $path) => '/build/'.$path,
            $manifest[$entry]['css']
        );
    }

    /**
     * @return array<string, array{file: string, css: string[], src: string, isEntry: bool, name: string}>
     *
     * @throws \JsonException
     */
    private function getManifest(): array
    {
        if (!file_exists($this->manifestPath)) {
            throw new \RuntimeException('Vite manifest.json not found at '.$this->manifestPath);
        }

        $json = file_get_contents($this->manifestPath);
        if (false === $json) {
            throw new \RuntimeException('Vite manifest.json not readable at '.$this->manifestPath);
        }

        if ([] === $this->manifest) {
            /** @var array<string, array{file: string, css: string[], src: string, isEntry: bool, name: string}> $content */
            $content = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
            if (is_array($content)) {
                $this->manifest = $content;
            } else {
                throw new \RuntimeException('Vite manifest.json not valid JSON at '.$this->manifestPath);
            }
        }

        return $this->manifest;
    }
}
