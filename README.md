#  datamaxfiles-bookstore
**Step to Install.**

 1. Clone project
 2. Composer Install
 3. php artisan key:generate
 4. Configure .env file DB_CONNECTION=sqlite //to use sqlite Database 
 5. php artisan db:seed /to get demo books data
 
 For technical overview about the BookAPI read through the TEST code: tests/Feature/BookTest.php
 #### Home Page
 Should have a searchbook, where user can search for books from ICE and Fire API
 #### books page
 Should hv list of books if you run the BookSeederClass php artisan db:seed

#### Note
If nothing displayed in the home page or books page, please git checkout to development branch.
Also, to run test open terminal and run vendor/bin/phpunit --filter BookTest in root directory.
 
