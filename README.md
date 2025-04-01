
This is a shopping cart application.

## Table of Contents

- [CS602 Term Project](#cs602-term-project)
  - [Table of Contents](#table-of-contents)
  - [Introduction](#introduction)
  - [Features](#features)
  - [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Database Setup](#database-setup)
    - [Run the PHP server](#run-the-php-server)
  - [Contact](#contact)

## Introduction

This shopping cart application allows customers to search and browse available
products, add products to their shopping cart, and place orders.Meanwhile,
administrators have access to features such as managing product inventory and
customer orders.

## Features

- User Authentication: Login and registration functionality for both customers
  and administrators.
- Product Management: View, add, update, and delete products from the inventory.
- Order Placement: Users can place orders for selected products.
- Order Management: Administrators can view and manage user orders.
- User Roles: Distinct roles for customers and administrators with different
  privileges.

## Getting Started

### Prerequisites

- PHP (7.0 or higher)
- MySQL (5.6 or higher)

### Database Setup

Use the provided SQL script (database.sql) (You need to manually run those
command into your mysql database):

- Create a new user 'cs602_user' with password 'cs602_secret'.
- Grant all privileges on the database to this user.
- Create the database and tables.
- Insert some initial data to the tables.

### Run the PHP server

Run command `php -S localhost:8000` in the root directory of this project to
start a built-in web server for serving PHP files on your local machine.

Then you can access it in your web browser by navigating to
`http://localhost:8000`.

## Contact

For any inquiries, please contact Yuqi Lin (<ylin12@bu.edu>).
