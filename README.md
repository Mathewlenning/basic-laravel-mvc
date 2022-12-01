# Welcome to BookFace #
This is a coding assignment I created for a project interview. 
This was my first Laravel app and I have to say I was impressed. 
I honed my programming skills in the insanity that is the Joomla Framework. 
So it was nice to use a framework that worked with me rather than against me. 
Laravel documentation is awesome too.

### How to deploy the app ###
Since I only used native Laravel and CDN resources for the front-end, you can follow the
instructions from https://laravel.com/docs/9.x/deployment page to get it up and running.

There is a copy of the database with some test data in the repo root,
but you could run artisan migrate to generate it without the test data.

## Code assignment requirements ##

- Use Laravel for the backend
- Use the persistence layer you prefer (MySQL, SQLite, etc)
- Create a list of books with the following functions,
  - Add a book to the list
  - Delete a book from the list
  - Change an authors name
  - Sort by title or author
  - Search for a book by title or author.
- Export the following in CSV and XML
  - A list of title and author
  - A list with only titles
  - A list with only authors
- Two weeks to complete the assignment

## Tech Stack ##
- Laravel/PHP
- HTML
- JQuery 
- UIKit
- MySQL

## Process ##
I started with a rough mockup of the UI. This helps to guide me during the implementation stage

![bookface mockup](/docs/2022-03-13-booklist.blade.php-mockup.png)

Next I created the first draft of the entity relationship diagram. 
The goal here was to capture all the dynamic data I need to build the UI and map out the relationships between the data.

![erd v1](/docs/2022-03-12-erd.jpg)

Since authors can have more than one book and a book could have many authors, I started with a many-to-many design.

However, after defining the models, controllers and views,
I concluded that the complexity this erd introduced wasn't worth the marginal gains 
of having a 100% accurate definition of the real world relationship between books and authors.

So I simplified the ERD to a single table. If this had been a project that was intended to be released to the public, 
I think the added complexity would have been justified. Because it would allow the two entities to evolve independently.
But this project was more about gauging my process and programing skills. So I opted to adhere to the YAGNI principle.

![erd v1](/docs/2022-03-13-erd.jpg)

Once the groundwork done and I knew what I needed to create. I started the implementation.

## Implementation ##
There isn't much to say about the implementation process. Since I had already created a solid definition of
what needed to be built, it was just a matter of building it.

In total the app took me 3 days to plan and build. I took one extra day to refactor for a total of 4 days.

# [Live Demo](https://mathewlenning.com/bookface/) #
