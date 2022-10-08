EasyEnum
=
Easy Doctrine DBAL enum type integration for PHP enum

Usage
-
Assume you have

    #FooBarBaz.php

    namespace SomeNamespace;

    enum FooBarBaz: string
    {
        case FOO = 'foo';
        case BAR = 'bar';
        case BAZ = 'baz';
    }
enum, that holds values that you want for your DB ENUM field.

Just create

    #FooBarBazEnum.php

    namespace SomeOtherNamespace;

    use LIXTAR\EasyEnum\EasyEnum;
    use SomeNamespace\FooBarBaz;

    class FooBarBazEnum extends EasyEnum
    {
        protected string $enumClassname = FooBarBaz::class;
    }
and register doctrine type with `name = <snakecased_classname>`

    doctrine:
        dbal:
            types:
                foo_bar_baz_enum: SomeOtherNamespace\FooBarBazEnum
