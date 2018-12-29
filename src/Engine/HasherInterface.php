<?php
declare(strict_types=1);
/**
 * WebSkeleton - A fast and secure web skeleton powered by Symfony.
 * @author WebSkeleton Contributors <https://github.com/ThinCharm/WebSkeleton/graphs/contributors>
 * @link <https://github.com/ThinCharm/WebSkeleton> WebSkeleton.
 */

namespace ThinCharm\Engine;

/**
 * The hasher interface.
 */
interface HasherInterface
{

    /**
     * Create a new hasher instance.
     *
     * @param array $options The hasher options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = []);

    /**
     * Create a hash.
     *
     * @param string $text The text to hash.
     *
     * @return string The hashed text.
     */
    public function create(string $text): string;

    /**
     * Verify the text matches the hash.
     *
     * @param string $text The text to check.
     * @param string $hash the hash to check against.
     *
     * @return bool Returns true if the match and false if not.
     */
    public function verify(string $text, string $hash): bool;

    /**
     * Check to see if the hash needs a rehash.
     *
     * @param string $hash The hash to check.
     *
     * @return bool Returns true if the hash needs a rehash and false if not.
     */
    public function needsRehash(string $hash): bool;
}
