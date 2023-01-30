### Mini-Aspire - Project setup

1) git clone https://github.com/program-dvl/mini-aspire.git
2) create .env file and set database name and credentials
3) composer install
4) php artisan migrate
5) php artisan db:seed

- Seeder will add 2 records.

- 1 customer record will be added to user table


  **Email**: dhaval@miniaspire.com

  **Password**: password

- 1 admin user record will be added to admin table


  **Email**: admin@miniaspire.com

  **Password**: password

So, We can use above credetials to test authenticated API.

**Please click below to run the API through postman**

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/22363958-cf780732-36e6-4790-82d9-2f7a0a67a2d9?action=collection%2Ffork&collection-url=entityId%3D22363958-cf780732-36e6-4790-82d9-2f7a0a67a2d9%26entityType%3Dcollection%26workspaceId%3Da5bdd0b5-1912-4e90-b24a-15bcbb14de5a)
