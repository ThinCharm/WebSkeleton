<?php
declare(strict_types=1);
/**
 * WebSkeleton - A fast and secure web skeleton powered by Symfony.
 * @author WebSkeleton Contributors <https://github.com/ThinCharm/WebSkeleton/graphs/contributors>
 * @link <https://github.com/ThinCharm/WebSkeleton> WebSkeleton.
 */

namespace ThinCharm\Engine;

use Illuminate\Hashing\Argon2IdHasher;
use Illuminate\Hashing\ArgonHasher;
use Illuminate\Hashing\BcryptHasher;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UnexpectedValueException;

use const PASSWORD_ARGON2_DEFAULT_MEMORY_COST;
use const PASSWORD_ARGON2_DEFAULT_TIME_COST;
use const PASSWORD_ARGON2_DEFAULT_THREADS;

/**
 * The hasher.
 */
class Hasher implements HasherInterface
{

    /** @var $options The hasher options. */
    private $options = [];

    /** @var $instance The hasher instance. */
    private $instance;

    /**
     * Create a new hasher instance.
     *
     * @param array $options The hasher options.
     *
     * @return void Returns nothing.
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        $this->instance = $this->getHasherInstance();
    }

    /**
     * Configure the options.
     *
     * @param OptionsResolver The symfony options resolver.
     *
     * @return void Returns nothing.
     */
    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'algo' => 'bcrypt',
            'cost' => 10,
            'memory_cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
        ]);
    }

    /**
     * Create a hash.
     *
     * @param string $text The text to hash.
     *
     * @return string The hashed text.
     */
    public function create(string $text): string
    {
        return $this->instance->make($text);
    }

    /**
     * Verify the text matches the hash.
     *
     * @param string $text The text to check.
     * @param string $hash the hash to check against.
     *
     * @return bool Returns true if the match and false if not.
     */
    public function verify(string $text, string $hash): bool
    {
        return $this->instance->check($text, $hash);
    }

    /**
     * Check to see if the hash needs a rehash.
     *
     * @param string $hash The hash to check.
     *
     * @return bool Returns true if the hash needs a rehash and false if not.
     */
    public function needsRehash(string $hash): bool
    {
        return $this->instance->needsRehash($hash);
    }

    /**
     * Get the hasher instance.
     *
     * @throws UnexpectedValueException If the algo could not be determined.
     *
     * @return mixed The hasher instance.
     */
    private function getHasherInstance()
    {
        if ($this->options['algo'] == 'bcrypt') {
            return new BcryptHasher([
                'rounds' => $this->options['cost'],
                'verify' => true
            ]);
        } elseif ($this->options['algo'] == 'argon2i') {
            return new ArgonHasher([
                'memory_cost' => $this->options['memory_cost'],
                'time_cost' => $this->options['time_cost'],
                'threads' => $this->options['threads'],
                'verify' => true
            ]);
        } elseif ($this->options['algo'] == 'argon2id') {
            return new Argon2IdHasher([
                'memory_cost' => $this->options['memory_cost'],
                'time_cost' => $this->options['time_cost'],
                'threads' => $this->options['threads'],
                'verify' => true
            ]);
        } else {
            throw new UnexpectedValueException('Could not determine the hash algo.');
        }
    }
}
