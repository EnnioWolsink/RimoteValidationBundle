# RimoteValidationBundle
Extends and simplifies Symfony's Validator by providing a flat array with error messages.

## Installation
### Modify your composer.json
Add the following:

    "repositories": [
        {
            "type": "vcs",
            "url": "git@gitlab.rimotecloud.com:rimote-platform/validation-bundle.git"
        }
    ],
    
As well as this line to the sub-array `require`:

    "rimote/rimote-validation-bundle": "*@dev"

### Update your AppKernel.php
Edit `/app/AppKernel.php` and add the following bundle in the AppKernel::registerBundles() method:

    new Rimote\ValidationBundle\RimoteValidationBundle()