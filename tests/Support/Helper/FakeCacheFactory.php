<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class FakeCacheFactory
{
    private array $storage = [];

    public static function create(): CacheInterface
    {
        return new class implements CacheInterface {
            private array $storage = [];

            public function get(string $key, callable $callback, float $beta = null, array &$metadata = null): mixed
            {
                if (array_key_exists($key, $this->storage)) {
                    return $this->storage[$key];
                }

                $item = new class implements ItemInterface {
                    public function isHit(): bool
                    {
                        return false;
                    }

                    public function set(mixed $value): static
                    {
                        return $this;
                    }

                    public function expiresAfter(int|\DateInterval|null $time): static
                    {
                        return $this;
                    }

                    public function expiresAt(?\DateTimeInterface $expiration): static
                    {
                        return $this;
                    }

                    public function get(): mixed
                    {
                        return null;
                    }

                    public function getKey(): string
                    {
                        return 'dummy';
                    }

                    public function tag(iterable|string $tags): static
                    {
                        return $this;
                    }

                    public function getMetadata(): array
                    {
                        return [];
                    }
                };

                $value = $callback($item);
                $this->storage[$key] = $value;

                return $value;
            }

            public function delete(string $key): bool
            {
                unset($this->storage[$key]);

                return true;
            }
        };
    }
}
