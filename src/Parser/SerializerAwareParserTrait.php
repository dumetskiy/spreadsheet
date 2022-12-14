<?php

declare(strict_types=1);

namespace Spreadsheet\Parser;

use Spreadsheet\Exception\Parser\ParserException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SerializerAwareParserTrait
{
    /**
     * @phpstan-ignore-next-line
     *
     * @var SerializerInterface|DecoderInterface
     */
    protected SerializerInterface $serializer;

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    private function decodeContents(string $contents, string $format): mixed
    {
        try {
            if (!$this->serializer->supportsDecoding($format)) {
                throw ParserException::create(sprintf(
                    'Unable to decode the data of "%s" format using serializer',
                    $format
                ));
            }

            return $this->serializer->decode($contents, $format);
        } catch (UnexpectedValueException|\RuntimeException) {
            throw ParserException::create(sprintf(
                'Failed to decode spreadsheet file contents with format "%s"',
                $format
            ));
        }
    }
}
