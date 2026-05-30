<?php

namespace ProgrammatorDev\FluentValidator\Test\Writer;

use PHPUnit\Framework\TestCase;
use ProgrammatorDev\FluentValidator\Writer\InterfaceWriter;

class InterfaceWriterTest extends TestCase
{
    public function testWritesToConfiguredOutputDirectoryAndEscapesDefaultValues(): void
    {
        $outputDirectory = sys_get_temp_dir() . '/fluent-validator-' . bin2hex(random_bytes(8));
        mkdir($outputDirectory);

        $filePath = $outputDirectory . '/ExampleInterface.php';

        try {
            $parameters = (new \ReflectionFunction(
                fn (string $message = "It's valid", array $groups = [], mixed $payload = null) => null
            ))->getParameters();

            $writer = new InterfaceWriter('ExampleInterface', $outputDirectory);
            $writer->writeInterfaceStart();
            $writer->writeMethod('example', 'Validator', $parameters);
            $writer->writeInterfaceEnd();

            $contents = file_get_contents($filePath);

            $this->assertStringContainsString("string \$message = 'It\\'s valid',", $contents);
            $this->assertStringContainsString('array $groups = [],', $contents);
            $this->assertStringContainsString('mixed $payload = null,', $contents);
        }
        finally {
            if (is_file($filePath)) {
                unlink($filePath);
            }

            rmdir($outputDirectory);
        }
    }
}
