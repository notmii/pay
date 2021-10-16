## Tech Stack
 - Docker
 - Laravel 7
 - PHP 7

## How to use
### Requirements
 - Docker (https://docs.docker.com/engine/install/ubuntu/)
 - Docker Compose (https://docs.docker.com/compose/install/)
 - make (sudo apt-get install build-essential) (optional) 

The platform used on this module is Ubuntu.
If you are using a different platform please check the Docker website for steps to install in your platform.

The *Makefile* created on this repository is just made to wrap the docker commands. You may still execute the commands without using **make** please refer to the *Makefile* to get the docker commands that has been wrapped.

### Setup
After installing the requirements please do the following
 - Copy **<project_root>/app/.env.example** to **<project_root>/app/.env**
 - Open & edit **<project_root>/app/.env** input your Exchange Rate API on the corresponding config variable.
 - Execute the following commands
 -- make build
 -- make up
 -- make phpunit
 -- make compute

**NOTE**:
Update the content of *<project_root>/app/input.csv* to test your own data
You could also provide a different file but it needs to be copied to the *<project_root>/app/* and update the *Makefile* accordingly.

### Commands
 - make build - builds the docker containers
 - make up - put up the containers
 - make phpunit - executes the unit-test
 - make compute - runs the commission computation

### Files & Directory Structure
There might be more files that can be found on this project but not all of the files are necessary for this exam. Many files in here is part of Laravel's project scaffolding.

Only the following files & directory is created specific for this exam.
- app/app/Console/Commands
-- this contains the new Laravel CLI program that computes & outputs the commissions.
- app/app/Library/Core/Entities
 -- this contains the Model or Domain object of this application
 - app/app/Providers/ExchangeRate
 -- this contains our wrapper for http://api.exchangeratesapi.io/ API
 - app/app/Repositories
 -- this contains the abstract for the storage (for this case everything is in memory)
 - app/app/Services
 -- this contains the different classes for computing commissions base on rules
 - app/tests/App
 -- thie contains all the unit-test created related to the classes
 
### Important Classes
 - **BusinessWithdrawal** - responsible for computing commission for withdrawal of a business user.
 - **PrivateWithdrawal** - responsible for computing commission for withdrawal of a private user.
 - **Deposit** - responsible for computing commission for deposits.
 - **ExchangeRateApi** - wrapper for communicating with http://api.exchangeratesapi.io/

![Alt Text](https://github.com/notmii/pay/blob/master/docs/ClassDiagram.png)


### Sample
![Alt Text](https://github.com/notmii/pay/blob/master/docs/sample.gif)
