# Residential College Management System (RCMS)

A web application to help the students at residential college to register new
account, view and register for the activities organised by the residential college,
report an issue found at residential college, order food and apply for accommodation.

Note: admin dashboard to be developed. Data management for this MVP user application has to be done via database operations.

## Getting started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.
The instructions below could be biased to the LAMP stack as it is being used in development. However, it should be configurable with other stacks.  

1. Clone the repository.

    ```
    git clone https://github.com/kphilemon/RCMS.git
    ```
2. Set the [public](https://github.com/kphilemon/RCMS/tree/master/public) directory as your document root. Guide for 
   [Apache](https://www.digitalocean.com/community/tutorials/how-to-move-an-apache-web-root-to-a-new-location-on-ubuntu-16-04)

3. Execute [setup.sql](https://github.com/kphilemon/RCMS/blob/master/mysql/setup.sql). This will give you a 
   fully set up mysql database with dummy data.
    ```
    mysql> source mysql/setup.sql
    ```
4. Tweak configurations in [config.php](https://github.com/kphilemon/RCMS/blob/master/config/config.php) as needed. Eg: 
    database credentials, SMTP server configurations.

5. Start your server and you are now good to go. 

## Important Note

**TLDR: Use the tester account credentials to log in if you do not want to go through the hassles.**
```
Email     : test@siswa.um.edu.my
Password  : test123
```
One of the requirements/features of this project is email domain whitelisting. Currently, only email with `@siswa.um.edu.my`
domain has been whitelisted and thus can be used for registration. Email with other domains will *not* be able to bypass 
the email validations. 

Besides, newly-registered user have to activate their account via an account activation link that is sent to their email.
This feature is implemented to verify the validity of the email addresses of new users. 

Due to these restrictions, you will *not* be able to register nor sign in unless you own a valid email with the specified 
domain OR you manually disable the email validation across the frontend and backend scripts. (Very inefficient, could be refactored using flags).

Well, if you have a valid email OR you have disabled all the validations, you have one final step to go, that is to whitelist
yourself in a database table. Well, bear with me :)

Since this is an application for students, we wanted to make sure that only valid students can register. This is mocked by
checking if the email exists in our "fake" student record table named `um_database`. To whitelist your email, simply insert 
a record with your valid email into the `um_database` table and you are all set to test out the full features of this system.

## Contributors
- [kphilemon](https://github.com/kphilemon)
- [zhikiat62](https://github.com/zhikiat62)
- [jwlim77](https://github.com/jwlim77)
- [xinyilau](https://github.com/xinyilau)
- [JihShian](https://github.com/JihShian)
- [Aericsee](https://github.com/Aericsee)