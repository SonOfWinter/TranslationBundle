Installation
============


Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/translation-bundle
```

Usage
=====

Define tranlated properties in your entity

```php
    /**
     * @var string
     * @Translate(key="firstname")
     */
    private $firstname;

    /**
     * @var string
     * @Translate(key="lastname", setter="setOtherName")
     */
    private $lastname;
```

You must defined the key property. 

The setter property is used if you want to use another setter.

A TranslatableConfigurationException is throws if the setter doens't exist.

Use Translator service for translate entity

```php
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    function translate(Translatable $entity, string $lang): Translatable
    {
        ...
        $this->translator->translate($entity, $lang);
        ...
    }

    function setTranslations(Translatable $entity, array $lang): Translatable
    {
        ...
        $translationsArray = ['firstname' => 'FirstName', 'lastname' => 'LastName'];
        $translationGroup = $translator->setTranslationForLangAndValues(
            entity,
            $lang,
            $translationsArray
        );
        ...
    }
```
