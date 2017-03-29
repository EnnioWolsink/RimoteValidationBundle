# RimoteValidationBundle
Extends and simplifies Symfony's Validator by providing a flat array with error messages.

## Installation
Install using composer:

    composer require rimote/rimote-validation-bundle
    
## How does it work?
Whenever you want to validate a [Doctrine](http://www.doctrine-project.org/projects/orm.html) Entity in your [Symfony](symfony.com) codebase, instead of using Symfony's [Validator component](http://symfony.com/doc/current/validation.html) directly, you can use the RimoteValidationBundle's Validator instead. 

The RimoteValidationBundle works nearly the same as Symfony's native Validator. Meaning you write your constraints straight into your Entity at the property level and then use the validator's `validate()` method to check if all property valyes are in the right format. 

The difference is the format of the exception that will be thrown in case of validation errors. This will contain a `getErrors()` public method you can use to retrieve a flat key/value array of error messages. The keys will correspond with the Entity properties they relate to, the values will be the error messages as defined in your Entity.

## Usage 
WIP