Installation
============

Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/translation-bundle
```

Usage
=====

You can create a Translation entity class who extends AbstractTranslation
set sow_translation.translation_class_name parameter to

```xml
<parameter key="sow_translation.translation_class_name">App\Entity\Translation</parameter>
```

Define translated properties in your entity

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

You can defined the key property for matching another name, if it's not, the property name is taken by default.

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

Next
====

> multilang setter/getter

just lang

~~~json
    [
        "fr": [ #or object
            "prop1": "...",
            "prop2": "..."
        ],
        "en": [
            "prop1": "...",
            "prop2": "..."
        ]
    ]
~~~

or in object

~~~json
    {
        "id": 1,
        "prop0": "...",
        "fr": [ #or object
            "prop1": "...",
            "prop2": "..."
        ],
        "en": [
            "prop1": "...",
            "prop2": "..."
        ],
        "prop3": "..."
    }
~~~

with default language and list of available languages (parameter)