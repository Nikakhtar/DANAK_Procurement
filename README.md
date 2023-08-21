# DANAK_Procurement
# ProcessMaker DB Operator Class

## Introduction

The `DB_Operator` class is designed for integration within ProcessMaker triggers, providing utilities for database operations and SMS notifications using Kavenegar API. It acts as an intermediary to perform CRUD operations and ensures efficient communication with the database.

## Features

- **Database Interactions**: The class offers methods to insert, update, and select data from various tables, abstracting the underlying SQL queries.
- **Kavenegar SMS Notifications**: The class interfaces with the Kavenegar API to send SMS notifications, streamlining user communication.
- **Dynamic Table and Field Handling**: The class can interact with various tables and fields, making it versatile for different scenarios.
  
## Usage

1. **Initialization**: Create an instance of the `DB_Operator` class by providing the appropriate database name.

    ```php
    $db = "YOUR_DATABASE_NAME";
    $dbOperator = new DB_Operator($db);
    ```

2. **Send Notification**: Use the `send_notify` method to send SMS notifications through Kavenegar.

    ```php
    $dbOperator->send_notify($db2, $receptor, $invoice = 0);
    ```

3. **Database Operations**: Use methods like `insert`, `update`, and `select` to interact with the database.

    ```php
    $dbOperator->insert($tableName, $arr_fields, $arr_values);
    ```

4. **Case Logging**: Log case-related data using the `insert_case_log` and `update_case_log_Time2` methods.
