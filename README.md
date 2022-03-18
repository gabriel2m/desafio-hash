Hash Challenge
===

Author: <a href="https://github.com/gabriel2m" target="_blank">github.com/gabriel2m</a>

Features
---
  1. A route, with a 10 requests per minute rate limit, that using a input string returns: 
      * A md5 hash starting with 4 zeros
      * The key that, concatenated with the input string, was used to get the hash
      * The number of attempts to get the hash

  2. A command that, with an input string and an input requests number, consults the hash route and store the results,
    using the input string in the first request then the hash of the previous request in the next request, 
    respecting the rate limit
    
  3. A route that provides the stored results, with pagination and filtring by number of attempts,
    that should return the results with less then the input attempts, 
    showing just the "batch", "block", "string" and "key" attributes of each result.


Instructions
---
  **Access Hash Route**:
  * Go to path `/hash/{string}`  
  (<a href="http://127.0.0.1/hash/{string}" target="_blank">http://127.0.0.1/hash/{string}</a> with docker)

  **Run AvatoTestCommand**:
  * Run `$ avato:test {string} --requests={requests}`  
  (`$ docker-compose exec app avato:test {string} --requests={requests}` with docker)

  **Access Results Route**:
  * Go to path `/results?page={page}&attempts={attempts}`  
  (<a href="http://127.0.0.1/results?page={page}&attempts={attempts}" target="_blank">http://127.0.0.1/results?page={page}&attempts={attempts}</a> with docker)

  **Acess Api Doc**:
  * Got to path `/doc/index.html`  
  (<a href="http://127.0.0.1/doc/index.html" target="_blank">http://127.0.0.1/doc/index.html</a> with docker)

  **Run tests**:
  * Run `$ php ./bin/phpunit`  
  (`$ docker-compose exec app php ./bin/phpunit` with docker)
  
Installation
---
  * Clone the repo `$ git clone https://github.com/gabriel2m/desafio-hash.git`
  * Enter created dir `$ cd desafio-hash`
  * Create the .env using .env.example as base  `$ cp .env.example .env`
  * Edit the .env with your configs `$ vim .env`
  * Run the containers `$ docker-compose up`
  * Install the dependencies `$ docker-compose exec composer install`
  * Run the migrations `$ docker-compose exec php bin/console d:m:m`
  * Create the test db `$ docker-compose exec php bin/console --env=test doctrine:database:create`
  * Run the migrations on the test db `$ docker-compose exec php bin/console --env=test d:m:m`
  * All done 🙌 Now just access: <a href="http://127.0.0.1" target="_blank">http://127.0.0.1</a>

Considerations
---
  * I chose to use PHP 8.1 and Symfony 6 to agilize the developing by using her new features
  * All the packages used are the ones indicated in the Symfony documentation
  * Solid principles in Symfony 6 are much way easier since it tries to embrace them in its architecture
