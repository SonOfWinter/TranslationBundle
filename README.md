TranslationBundle
-----------------

Installation
============

Open a command console, enter your project directory and execute:

```bash
$ composer require sonofwinter/translation-bundle
```

Configuration
=============

You can override sow_translation.available_locales parameter to a new list for set your available lang list
default is _[ 'en', 'fr', 'es', 'de', 'it' ]_

By default a Translation entity class exists but you can create your translation entity class who extends AbstractTranslation
To use it, set the sow_translation.translation_class_name parameter to
```xml
<parameter key="sow_translation.translation_class_name">App\Entity\YourTranslationClass</parameter>
```

Usage
=====

Your translated entities must implements Translatable interface
Then define translated properties in your entity

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


New n V0.8
==========

This bundle now require php >= 8.0

you can use attribute instead of annotation

```php
use SOW\TranslationBundle\Attribute\Translation;

class MyClasse {

    #[Translation(key: "firstname")]
    private string $firstname = '';

    #[Translation(key: "lastname", setter: "setOtherName")]
    private string $lastname = '';
}
```

By default, the bundle use annotation method, you have to change configuration to use attributes

```yaml
    sow_translation.translation_method: attribute
```

If you want to override attribute class, don't forget to define it in configuration

```yaml
    sow_translation.attribute_class_name: SOW\TranslationBundle\Attribute\Translation
```

Translate
=========

You can use some methods for translate an entity :

* _translate(Translatable $entity, string $lang)_ to translate the entity in $lang
* _translateForLangs(Translatable $entity, array $langs)_ to translate the entity in multiple languages

Set translations
================

These methods is use for set translations :

* _setTranslationForLangAndValue(Translatable $translatable, string $lang, string $key, string $value)_ to set a single translation
* _setTranslationForLangAndValues(Translatable $translatable, string $lang, array $values)_ for set multiple values in one lang
* _setTranslations(Translatable $translatable, array $translations)_ for set multiple translation for multiple languages

Remove translations
===================

These methods is use for remove translations :

* _removeByObjectKeyAndLang(Translatable $object, string $key, string $lang)_ remove a specific translation
* _removeAllForTranslatable(Translatable $object)_ remove all translation for object
* _removeAllByKey(string $key)_ remove all translation for property
