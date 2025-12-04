# API: users

## Installation

1. Clone repository:
    ```bash
    git clone https://github.com/serbynskyi/users.git
    cd users
    ```

2. Run Docker container:
    ```bash
    docker compose up -d --build
    ```

## Usage

Preinstalled admin user:
- login: admin
- password: 12345678

The API supports authentication via tokens. To get a token:

1. Make POST request to `v1/api/login` :
    ```json
    {
      "username": "your_login",
      "password": "your_password"
    }
    ```

2. In the response, you will receive a token that must be added to the `Authorization` header to access secure routes.


# Available Endpoints

### GET /v1/api/users/{id}
Returns user by ID.
Requires authorization.

### POST /v1/api/users
Creates a new user.
Requires admin authorization.

### PUT /v1/api/users/{id}
Updates an existing user.
Allowed for:
- Admin  
- The user themselves

### DELETE /v1/api/users/{id}
Deletes a user.
Requires admin authorization.
