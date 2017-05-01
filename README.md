# RESTFUL API on PHP
This is a very basic RESTful API model to be implemented with PHP.

It has a client side menu with options for GET, POST, PUT and DELETE functions. Those are called with an AJAX app to the server via REST.


## Getting Started

These instructions will explain you by parts the functionality of this project to start applying REST model on your own projects.

### Prerequisites

To try this application I used a LAMP architecture. Don't doubt on using different database or the ones you are more confortable.

### Installing

Create a desired database for the project

```
restful_api for example
```

And table

```
I.e.: "users" with an ID and users_name
```

### CLIENT SIDE
[index.html](index.html)

The following steps explain how is coded the formulary on the client side.

Basically we created three parts:

- A *textbox* where the changes can be applied
- A *select* where the results are displayed
- And four function buttons: **Search**, **Modify**, **Add**, **Delete**

Those elements interact with AJAX API where we can send accordingly 4 methods: **GET**, **PUT**, **POST**, **DELETE**

Please see file's comments for more information.

### SERVER SIDE
[restful api](api.php)

On this part we will manage all the information we gather. This PHP file is divided on five parts.

1. Receive info: **REQUEST METHOD**, **URL**, **JSON format info**
2. Database connection
3. Retrieve table and following info from URL
4. Build SQL sentence depending on request method
5. Send back the results

File's comments explain more in deep information about each part.

## License

This project is licensed under the GNU License - see the [LICENSE.md](LICENSE.md) file for details
