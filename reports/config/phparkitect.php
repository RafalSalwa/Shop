<?php

declare(strict_types=1);

use App\Command\AbstractSymfonyCommand;
use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\ContainDocBlockLike;
use Arkitect\Expression\ForClasses\DependsOnlyOnTheseNamespaces;
use Arkitect\Expression\ForClasses\Extend;
use Arkitect\Expression\ForClasses\HaveAttribute;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\IsEnum;
use Arkitect\Expression\ForClasses\IsFinal;
use Arkitect\Expression\ForClasses\IsInterface;
use Arkitect\Expression\ForClasses\IsNotReadonly;
use Arkitect\Expression\ForClasses\NotDependsOnTheseNamespaces;
use Arkitect\Expression\ForClasses\NotHaveDependencyOutsideNamespace;
use Arkitect\Expression\ForClasses\NotHaveNameMatching;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\RuleBuilders\Architecture\Architecture;
use Arkitect\Rules\Rule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__ . '/../../src');

    $rules = [];

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App'))
        ->should(new IsFinal())
        ->because('all classes should be final by default');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new HaveNameMatching('*Controller'))
        ->because('we want uniform naming')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new Extend(AbstractController::class))
        ->because('we want to be sure that all controllers extend AbstractController')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new HaveAttribute(AsController::class))
        ->because('it configures the service container')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new HaveAttribute(Route::class))
        ->because('it configures the service container')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->should(new IsFinal())
        ->because('it configures the service container')
    ;

    $rules[] = Rule::allClasses()
        ->that(new HaveNameMatching('*Controller'))
        ->should(new ResideInOneOfTheseNamespaces('App\Controller'))
        ->because('we want to be sure that all Controllers are in a specific namespace');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Command'))
        ->should(new Extend(AbstractSymfonyCommand::class))
        ->because('we want to use render functionality in console output')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Command'))
        ->should(new DependsOnlyOnTheseNamespaces('Symfony\Component', 'Doctrine\ORM', 'App', 'Termwind'))
        ->because('we want uniform naming')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Domain'))
        ->should(new NotHaveDependencyOutsideNamespace('App\Domain'))
        ->because('we want protect our domain')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Domain\Events'))
        ->should(new ContainDocBlockLike('@psalm-immutable'))
        ->because('we want to enforce immutability')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Service'))
        ->should(new HaveNameMatching('*Service'))
        ->because('we want uniform naming for services')
    ;
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Application'))
        ->should(new NotDependsOnTheseNamespaces('App\Infrastructure'))
        ->because('we want to avoid coupling between application layer and infrastructure layer')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Enum'))
        ->should(new IsEnum())
        ->because('we want to be sure that all classes are enum');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Domain\Entity'))
        ->should(new IsNotReadonly())
        ->because('we want to be sure that there are no readonly entities')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Entity'))
        ->should(new IsFinal())
        ->because('we want to be sure that aggregates are final classes')
    ;

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App\Interfaces'))
        ->should(new IsInterface())
        ->because('we want to be sure that all interfaces are in one directory');

    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('App'))
        ->should(new NotHaveNameMatching('*Manager'))
        ->because('*Manager is too vague and they suck.')
    ;

    $layeredArchitectureRules = Architecture::withComponents()
        ->component('Controller')->definedBy('App\Controller\*')
        ->component('Service')->definedBy('App\Service\*')
        ->component('Repository')->definedBy('App\Repository\*')
        ->component('Entity')->definedBy('App\Entity\*')
        ->component('Domain')->definedBy('App\Domain\*')
        ->where('Controller')->mayDependOnComponents('Service', 'Entity')
        ->where('Service')->mayDependOnComponents('Repository', 'Entity')
        ->where('Repository')->mayDependOnComponents('Entity')
        ->where('Entity')->shouldNotDependOnAnyComponent()
        ->where('Domain')->shouldOnlyDependOnComponents('Domain')
        ->rules()
    ;

    $config
        ->add($classSet, ...$layeredArchitectureRules, ...$rules);
};
