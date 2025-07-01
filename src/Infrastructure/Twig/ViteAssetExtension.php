<?php
declare(strict_types=1);

namespace App\Infrastructure\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private array $manifest;

    public function __construct(private KernelInterface $kernel)
    {
        $manifestPath = $this->kernel->getProjectDir() . '/public/build/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            throw new \RuntimeException('Vite manifest.json not found at ' . $manifestPath);
        }

        $this->manifest = json_decode(file_get_contents($manifestPath), true, flags: JSON_THROW_ON_ERROR);
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
        if (!isset($this->manifest[$entry]['file'])) {
            throw new \InvalidArgumentException("Vite entry '$entry' not found in manifest.");
        }

        return '/build/' . $this->manifest[$entry]['file'];
    }

    /**
     * @return string[]
     */
    public function getCssAssetPaths(string $entry): array
    {
        if (!isset($this->manifest[$entry]['css'])) {
            return [];
        }

        return array_map(
            fn(string $path) => '/build/' . $path,
            $this->manifest[$entry]['css']
        );
    }
}
