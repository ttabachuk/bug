# bug-tracker app for server programming
## guide:

#### htaccess setup
- create a `.htaccess` file in the `src/` directory

> note:
> credentials for the `.htaccess` file are attached with the mycourses submission

#### logging in

to log in to the app, feel free to use any of these credentials:

##### admin
- username: `admin` ; password: `password`

##### manager
- username: `manager` ; password: `password`

##### users
- username: `user1` ; password: `password`
- username: `user2` ; password: `password`
- username: `user3` ; password: `password`
- username: `user4` ; password: `password`

## notes:

10.26.25:

- pushed a working implementation of the project
- note: initially when i created a laravel project for this project, I used Herd. I was hitting some issues when trying to build my app on the school server, so i decided to use vanilla php instead. I moved my laravel project to the `archive/` folder.

10.06.25:

- for this applications current page layout, please see [views](./src/resources/views/)
- for this applications models, please see [models](./src/app/Models/)
- for this applications architecture diagram, please see the following [link](./docs/diagrams/mvc-architecture.md)
- i was thinking to use repository classes for database interaction, i can paste the link to those [here](./src/app/Repositories/) as well :)
- also this app is using laravel
