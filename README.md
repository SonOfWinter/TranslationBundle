# SOWTranslationBundle

This Bundle provides a translator for Symfony entities.

## Prerequisites

- PHP 8.2 or higher
- Symfony 7.0 or higher
- Composer

## Installation

Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/translation-bundle
```

## Configuration

### Bundle Registration

Register the bundle in your `config/bundles.php`:

```php
return [
    // ...
    SOW\TranslationBundle\SOWTranslationBundle::class => ['all' => true],
];
```

### Available Locales

You can override the default available locales by setting the `sow_translation.available_locales` parameter:

```yaml
parameters:
    sow_translation.available_locales: ['en', 'fr', 'es', 'de', 'it']
```

### Custom Translation Entity

By default, a Translation entity class is provided, but you can create your own translation entity class that extends AbstractTranslation.
To use it, set the `sow_translation.translation_class_name` parameter:

```yaml
parameters:
    sow_translation.translation_class_name: App\Entity\YourTranslationClass
```

## Usage

### Setting Up Translatable Entities

Your translated entities must implement the `Translatable` interface.
Then define translated properties in your entity using either annotations or attributes.

#### Using Attributes (PHP 8.0+)

```php
use SOW\TranslationBundle\Attribute\Translation;

class MyClass {
    #[Translation(key: "firstname")]
    private string $firstname = '';

    #[Translation(key: "lastname", setter: "setOtherName")]
    private string $lastname = '';
}
```

### Configuration Notes

- The `key` property can be used to specify a different name for the translation key. If not provided, the property name is used.
- The `setter` property allows you to specify a custom setter method. If the setter doesn't exist, a `TranslatableConfigurationException` will be thrown.

## Translation Methods

### Translating Entities

```php
// Translate an entity to a specific language
$translator->translate($entity, 'en');

// Translate an entity to multiple languages
$translator->translateForLangs($entity, ['en', 'fr', 'de']);
```

### Setting Translations

```php
// Set a single translation
$translator->setTranslationForLangAndValue($entity, 'en', 'firstname', 'John');

// Set multiple values for one language
$translator->setTranslationForLangAndValues($entity, 'en', [
    'firstname' => 'John',
    'lastname' => 'Doe'
]);

// Set multiple translations for multiple languages
$translator->setTranslations($entity, [
    'en' => [
        'firstname' => 'John',
        'lastname' => 'Doe'
    ],
    'fr' => [
        'firstname' => 'Jean',
        'lastname' => 'Dupont'
    ]
]);
```

### Removing Translations

```php
// Remove a specific translation
$translator->removeByObjectKeyAndLang($entity, 'firstname', 'en');

// Remove all translations for an entity
$translator->removeAllForTranslatable($entity);

// Remove all translations for a specific key
$translator->removeAllByKey('firstname');
```
