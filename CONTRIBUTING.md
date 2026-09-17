## Installation

From the plugin root directory, run the following commands:

```bash
$ make install -e SYLIUS_VERSION=XX SYMFONY_VERSION=YY
```

Default values : XX=2.1 and YY=6.4

To be able to set up the plugin database, remember to configure your database credentials
in `install/Application/.env.local` and `install/Application/.env.test.local`.

To reset test environment:
```bash
$ make reset
```

## Usage

### Running code analyse and tests

- GrumPHP (see configuration [grumphp.yml](grumphp.yml).)

  GrumPHP is executed by the Git pre-commit hook, but you can launch it manually with :

  ```bash
  $ make grumphp
  ```

- PHPUnit

  ```bash
  $ make phpunit
  ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    $ APP_ENV=test symfony server:start -d
    ```

- Using `dev` environment:

    ```bash
    $ symfony server:start -d
    ```
