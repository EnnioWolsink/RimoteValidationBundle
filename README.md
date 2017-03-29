# RimoteValidationBundle
Extends and simplifies Symfony's Validator by providing a flat array with error messages.

## Installation
Install using composer:

    composer require rimote/rimote-validation-bundle

Edit `/app/AppKernel.php` and add the following bundle in the AppKernel::registerBundles() method inside the `$bundles` array:

    new Rimote\ValidationBundle\RimoteValidationBundle()
    
## How does it work?
Whenever you want to validate a [Doctrine](http://www.doctrine-project.org/projects/orm.html) Entity in your [Symfony](symfony.com) codebase, instead of using Symfony's [Validator component](http://symfony.com/doc/current/validation.html) directly, you can use the RimoteValidationBundle's Validator instead. 

The RimoteValidationBundle works nearly the same as Symfony's native Validator. Meaning you write your constraints straight into your Entity at the property level and then use the validator's `validate()` method to check if all property values are in the right format.

The difference is the format of the exception that will be thrown in case of validation errors. This will contain a `getErrors()` public method you can use to retrieve a flat key/value array of error messages. The keys will correspond with the Entity properties they relate to, the values will be the error messages as defined in your Entity.

## Usage
First fetch an instance of the Rimote validator via the service container. Inside a container aware Controller, this can be accomplished like this:

    $validator = $this->get('rimote.validator');

Now you can simply pass an instance of an Entity that needs to be validated to the validator's `validate()` method:

    $validator->validate($cat);

If all is good this returns `true`. Otherwise an exception will be thrown, of the type `Rimote\ValidationBundle\Validator\Exception\ErrorMessagesException`. This exception has a public method called `getErrors()`, which produces a flat array with error messages, like so:

    array(2) {
        'property_1' =>
        string(30) "This value should not be null."
        'property_2' =>
        string(30) "This value should not be null."
    }

In the context of a REST API, this array of error messages can be returned as a JSON response with HTTP code 500. For a detailed example of such an implementation, see below.

### Example
In this example we have an Doctrine Entity called `Cat`, which has three properties that shouldn't be blank: `name`, `fur_type` and `gender`:

```php
<?php
    // AppBundle/Entity/Cat.php
    
    // ...

    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @Doctrine\ORM\Mapping\ORM\Entity(repositoryClass=Repository\CatRepository::class)
     * @Doctrine\ORM\Mapping\ORM\Table(name="cats")
     */
    class Cat
    {
        /**
         * @Assert\Type(type = "int"})
         * 
         * @ORM\Column(type = "integer")
         * @ORM\Id
         * @ORM\GeneratedValue
         */
        protected $id;
        
        /**
         * @Assert\Length(max = 255)
         * @Assert\NotNull
         * @Assert\Type(type = "string")
         * @Assert\NotBlank
         * 
         * @ORM\Column(length = 255)
         */
        protected $name;
        
        /**
         * @Assert\Length(max = 64)
         * @Assert\NotNull
         * @Assert\Type(type = "string")
         * @Assert\NotBlank
         * 
         * @ORM\Column(length = 64)
         */
        protected $fur_type;
        
        /**
         * @Assert\Length(max = 6)
         * @Assert\NotNull
         * @Assert\Type(type = "string")
         * @Assert\NotBlank
         * 
         * @ORM\Column(length = 6)
         */
        protected $gender;
    }
?>
```

Here's how we can validate this inside our CatsController::createAction (which uses the [FOSRestBundle](http://symfony.com/doc/current/bundles/FOSRestBundle/index.html) to set the POST body to an instance of `Cat`):

```php
<?php

// ...

use Rimote\ValidationBundle\Validator\Exception\ErrorMessagesException;

/**
 * @Route("/cats/", name="api_cats_create")
 * @Method("POST")
 * @ParamConverter("cat", converter="fos_rest.request_body")
 */
public function createAction(Request $request, Cat $cat)
{
    // ...

    $validator = $this->get('rimote.validator');

    try {
        $validator->validate($cat);
    } catch (ErrorMessagesException $e) {
        return new JsonResponse(['errors' => $e->getErrors()], 500);
    }
    
    // Run some code to for instance persist the cat in our database
}
?>
```