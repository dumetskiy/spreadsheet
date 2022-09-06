<?php

declare(strict_types=1);

namespace Spreadsheet\Tests\Unit\Parser;

use PHPUnit\Framework\TestCase;
use Spreadsheet\Enum\FileSource;
use Spreadsheet\Parser\FileSourceParser;

class FileSourceParserTest extends TestCase
{
    private FileSourceParser $testClass;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testClass = new FileSourceParser();
    }

    /**
     * @dataProvider getSourceToSourceTypeMap
     */
    public function testValidSourceEntries(string $source, FileSource $sourceType): void
    {
        $this->assertEquals($sourceType, $this->testClass->getSourceTypeFromSource($source));
    }

    /**
     * @dataProvider getInvalidSources
     */
    public function testInvalidSourceEntries(string $source): void
    {
        $this->assertNull($this->testClass->getSourceTypeFromSource($source));
    }

    public function getSourceToSourceTypeMap(): array
    {
        return [
            ['/some/file/dir', FileSource::FILE],
            ['/some/file/dir.extension', FileSource::FILE],
            ['file:///dev/null', FileSource::FILE],
            ['file://relative/file/path.extension.extension', FileSource::FILE],
            ['ftp://user:password@host:port/directory/file.extension', FileSource::FTP],
            ['ftp://host:port/file.extension', FileSource::FTP],
            ['http://www.hostname:com/file.extension', FileSource::HTTP],
            ['https://www.hostname:com/file.extension', FileSource::HTTPS],
            ['https://hostname:com/file.extension', FileSource::HTTPS],
        ];
    }

    public function getInvalidSources(): array
    {
        return [
            ['protocol://some/file/dir'],
            ['udp://file.extension'],
            ['protocol:///file.extension'],
            ['smtp:///file.extension'],
        ];
    }
}
