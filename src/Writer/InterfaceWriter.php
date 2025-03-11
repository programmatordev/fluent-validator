<?php

namespace ProgrammatorDev\FluentValidator\Writer;

class InterfaceWriter
{
    private \SplFileObject $file;

    public function __construct(private readonly string $interfaceName)
    {
        $filename = sprintf('src/%s.php', $this->interfaceName);
        $this->file = new \SplFileObject($filename, 'w');
    }

    public function writeLine(string $line = ''): void
    {
        $this->file->fwrite($line . PHP_EOL);
    }

    public function writeIndent(int $num = 1): void
    {
        $this->file->fwrite(str_repeat('    ', $num));
    }

    /**
     * @param \ReflectionParameter[] $parameters
     * @throws \ReflectionException
     */
    public function writeMethod(string $name, string $returnType, array $parameters = [], bool $isStatic = false): void
    {
        $this->writeIndent();
        $this->writeLine(
            sprintf(
                $isStatic ? 'public static function %s(' : 'public function %s(',
                $name
            )
        );

        foreach ($parameters as $parameter) {
            $parameterType = $this->formatType((string) $parameter->getType());

            $this->writeIndent(2);

            if ($parameter->isOptional()) {
                $this->writeLine(
                    sprintf(
                        '%s $%s = %s,',
                        $parameterType,
                        $parameter->getName(),
                        $this->formatValue($parameter->getDefaultValue())
                    )
                );
            }
            else {
                $this->writeLine(
                    sprintf(
                        '%s $%s,',
                        $parameterType,
                        $parameter->getName()
                    )
                );
            }
        }

        $this->writeIndent();
        $this->writeLine(sprintf('): %s;', $this->formatType($returnType)));
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