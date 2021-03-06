# N2F v2
This (currently) experimental version of N2F is aimed at decoupling as much functionality as possible from the core without defeating the purpose of having a pre-built system.

## Documentation
We're working on building out our documentation:

* [Documentation](https://github.com/n2framework/n2framework/wiki)
* [phpDocs](http://n2framework.com/phpDoc/v2.0.1/)

## Design
The core of N2F is built to facilitate the creation of chains and nodes.  Chains link together one or more nodes and provide them with information to process in the form of dispatches.

## Our Plans
One part of the experiment is to see how providing a similar programming experience across multiple languages fits with professional development.  As such, the immediate steps next are to implement the functionality of the PHP version as closely as possible in C#, followed later by other languages.

One feature currently missing from the PHP version is the ability to have dependencies for extensions.  The base functionality to build this is already in place, but the system still needs fleshed out and implemented.

## Requirements
The system will run on PHP 5.3 and up without any requirements for non-default extensions.

## Discussion
Want to chat with us?  Join us on [IRC](http://widget01.mibbit.com/?settings=522f69525a0c38ae917e5c5a66d6bab3&server=irc.zibings.net&channel=%23N2Framework)