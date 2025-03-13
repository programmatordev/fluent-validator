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

    public function writeLine(string $line = '', int $numIndent = 0): void
    {
        $this->writeIndent($numIndent);
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
        $this->writeLine(
            sprintf(
                $isStatic ? 'public static function %s(' : 'public function %s(',
                $name
            ),
            1
        );

        foreach ($parameters as $parameter) {
            $parameterType = $this->formatType((string) $parameter->getType());

            if ($parameter->isOptional()) {
                $this->writeLine(
                    sprintf(
                        '%s $%s = %s,',
                        $parameterType,
                        $parameter->getName(),
                        $this->formatValue($parameter->getDefaultValue())
                    ),
                    2
                );
            }
            else {
                $this->writeLine(
                    sprintf(
                        '%s $%s,',
                        $parameterType,
                        $parameter->getName()
                    ),
                    2
                );
            }
        }

        $this->writeLine(sprintf('): %s;', $this->formatType($returnType)), 1);
        $this->writeLine();
    }

    public function writeNamespace(string $name): void
    {
        $this->writeLine(sprintf('namespace %s;', $name));
    }

    public function writeInterfaceStart(): void
    {
        $this->writeLine('<?php');
        $this->writeLine();

        $this->writeNamespace('ProgrammatorDev\FluentValidator');
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