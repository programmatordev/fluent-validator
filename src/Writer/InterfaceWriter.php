<?php

namespace ProgrammatorDev\FluentValidator\Writer;

class InterfaceWriter
{
    private const INDENT = '    ';

    private \SplFileObject $file;

    public function __construct(private readonly string $interfaceName)
    {
        $filename = sprintf('src/%s.php', $this->interfaceName);
        $this->file = new \SplFileObject($filename, 'w');
    }

    public function writeLine(?string $line = null): void
    {
        $this->file->fwrite($line . PHP_EOL);
    }

    /**
     * @param \ReflectionParameter[] $parameters
     * @throws \ReflectionException
     */
    public function writeMethod(string $name, array $parameters = [], bool $isStatic = false): void
    {
        $this->writeLine(self::INDENT . sprintf(
            $isStatic ? 'public static function %s(' : 'public function %s(',
            $name
        ));

        foreach ($parameters as $parameter) {
            if ($parameter->isOptional()) {
                $this->writeLine(self::INDENT . self::INDENT . sprintf(
                    '%s $%s = %s,',
                    $this->formatType((string) $parameter->getType()),
                    $parameter->getName(),
                    $this->formatValue($parameter->getDefaultValue())
                ));
            }
            else {
                $this->writeLine(self::INDENT . self::INDENT . sprintf(
                    '%s $%s,',
                    $this->formatType((string) $parameter->getType()),
                    $parameter->getName()
                ));
            }
        }

        $this->writeLine(self::INDENT . '): ChainedInterface;');
        $this->writeLine();
    }

    public function writeNamespace(): void
    {
        $this->writeLine('namespace ProgrammatorDev\FluentValidator;');
    }

    public function writeUse(string $name): void
    {
        $this->writeLine(sprintf('use %s;', $name));
    }

    public function writeInterfaceStart(): void
    {
        $this->writeLine('<?php');
        $this->writeLine();
        $this->writeNamespace();
        $this->writeLine();
        $this->writeLine(sprintf('interface %s', $this->interfaceName));
        $this->writeLine('{');
    }

    public function writeInterfaceEnd(): void
    {
        $this->writeLine('}');
    }

    private function formatType(string $type): string
    {
        if (str_starts_with($type, 'Symfony')) {
            return sprintf('\%s', $type);
        }

        return $type;
    }

    private function formatValue(mixed $value): string
    {
        if (is_string($value)) {
            return sprintf("'%s'", $value);
        }

        if ($value === []) {
            return '[]';
        }

        if ($value === null) {
            return 'null';
        }

        if ($value === false) {
            return 'false';
        }

        if ($value === true) {
            return 'true';
        }

        return (string) $value;
    }
}