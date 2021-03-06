## Heaps and Bounds
#### Heaps and Bounds is for programmers and aspiring programmers to ask programming related questions. Create a profile so you can start asking questions. If you have some ideas to share, create a blog post on your profile for people to read and interact with.

### INSTALLATION INSTRUCTIONS
* Clone the project `git clone https://github.com/ryanpback/heaps-and-bounds.git`
* Copy `.env.example` to `.env`
* Copy `.env.testing.example` to `.env.testing`
* Fill in database name, user, and password fields for both `.env` and `.env.testing`
* If using local DB: `mysql -uroot -p`
* If using Homestead: `mysql -uhomestead -psecret`
* Create **two** databases: `heaps_and_bounds` and `heaps_and_bounds_testing`
    - Run `create database heaps_and_bounds && create database heaps_and_bounds_testing;`
* Migrate the non-testing database: `php artisan migrate`.
* Then seed the DB: `php artisan db:seed`.
* Or migrate and seed at the same time: `php artisan migrate --seed`

#### FUTURE FEATURES
* Tell your friends about a post
* Direct Messaging
* Follow/Be followed
* Custom profile link
* Notifications
* Tagging
* Pick topics that you're interested in
* Upload video turorials to your profile
* More to come
