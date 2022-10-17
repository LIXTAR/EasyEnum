<?php

namespace LIXTAR\EasyEnum;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Exception;
use InvalidArgumentException;
use Symfony\Component\String\ByteString;

abstract class EasyEnum extends Type
{
    protected string $enumClassname;

    public function getName(): string
    {
        return (new ByteString(self::class))->snake()->toString();
    }

    /**
     * @throws Exception
     */
    public function getValues(): array
    {
        if (!enum_exists($this->enumClassname)) {
            throw new Exception("'$this->enumClassname' enum doesn't exist");
        }

        return array_map(
            fn($type) => $type->value,
            call_user_func([$this->enumClassname, 'cases'])
        );
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return sprintf("ENUM('%s')", implode("', '", $this->getValues()));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BackedEnum
    {
        return $this->enumClassname::from($value);
    }

    /**
     * @throws Exception
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!in_array($value, $this->getValues())) {
            throw new InvalidArgumentException("Invalid '" . $this->getName() . "' value.");
        }

        return $value;
    }
}
