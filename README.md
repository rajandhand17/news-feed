# Laravel Docker Setup

This repository contains a Dockerized setup for a Laravel application.

## Prerequisites
- Docker: [Install Docker](https://www.docker.com/)
- Docker Compose: [Install Docker Compose](https://docs.docker.com/compose/install/)

## Setup Instructions
1. install php 8.1 or 8.2 and composer install
2. wamp or xampp 
3. Clone the repository:
   ```bash
   git clone https://github.com/rajandhand17/news-feed
   cd your-repo


## Project Structure Overview
## API Routes
   API routes are defined in the following custom route files:
      v1/{auth}
      v1/{articles}
      v1/{user-preference}
## Controllers
   Controllers are organized under the Api/ directory, with the following structure:
      Api/Articles
      Api/Auth
      Api/UserPreferences
   Each controller includes associated validation files located under Requests. These validation files define the rules for validating incoming API requests.

## Repositories
   After the controller logic, interactions with the database and external services are handled by repositories. These are responsible for data retrieval, storage, and manipulation.

## Services
   Business logic is encapsulated within service classes, which are based on models. These services contain the core functionality for the application, abstracting and centralizing the logic away from the controllers.

## Messages
   All response messages are stored in language files located at Resources/lang/en.json. These messages are used throughout the application for consistent and localized responses.

## Custom Base Controller
   A custom AppBaseController has been created to standardize and simplify response formatting across the application. This controller helps generate API responses according to project-specific requirements.

## Test Cases
   Test cases have been written for the register and login functionalities to ensure they work as expected. These tests are located in the relevant testing directories.

## Forgot Password Flow
   In the Forgot Password feature, an OTP is sent in the response. Since email sending is currently not activated, the code for sending emails is written but commented out in the service layer. To enable email functionality, simply uncomment the relevant code and add the SMTP credentials.

## Custom Artisan Commands
   Custom console commands for fetching data are defined under the App/Console/Commands directory. These commands are designed to automate certain tasks and can be executed via Laravel’s Artisan CLI.