## Subscriber Management

This project is an API service where individuals create subscriptions with credit cards and perform operations related to subscriptions.

### ÖnKoşullar

- [Composer](https://getcomposer.org) must be installed.
- [Docker](https://www.docker.com/) must be installed.

### Installation

1. Clone the project:
   ```sh
   git clone https://github.com/FurkanKuyular/subscriber-management.git

2. Install necessary dependencies with Composer:
    ```sh
   composer install

3. You can copy the env-example file as .env:
    ```sh 
   cp .env-example .env

4. Start the Docker containers with Laravel Sail:
    ```sh
   ./vendor/bin/sail up

5. Run migrations with seeding:
    ```sh
   ./vendor/bin/sail artisan migrate --seed

### Notlar

- Redis queue is used as the queuing structure. Queue operations can be monitored with Laravel Horizon.
- Postman environments and collection files are within the project. If both collections are defined, running the login method will automatically populate the token.
- To initiate the sync operation within the project, use the following command:
    ```sh 
    sail artisan app:sync-subscriber-from-zotlo
  sail artisan queue:work
-  The command written for reporting only prints a dump.
    ```sh 
    sail artisan app:send-daily-report-subscribers
- Zotlo API information needs to be added to the .env file for configuration
