# redBus Educational Replica – Intermediate Beginner-Friendly Version

## Requirements
- Windows 10/11
- XAMPP with Apache, MySQL and PHP
- A modern browser
- VS Code (recommended)

## Install
1. Extract the `redbus_intermediate` folder into `D:\xampp\htdocs\`.
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Choose Import and import `database/redbus.sql`.
5. Open `http://localhost/redbus_intermediate/`.

If you rename the folder, use that folder name in the browser URL.

## Create an account
Use Register to create a user with name, email and password. Student ID is not collected or stored. Login uses email and password.

## Main workflow
1. Register and log in.
2. On the home page search `Bangalore` to `Mysore`.
3. Choose a journey date (today or a future date).
4. Select seats.
5. Enter passenger details.
6. Confirm the simulated booking.
7. Open My bookings to view the ticket record.

## Extra syllabus demonstrations
- Feedback page: text-file create, read, update and delete.
- Interaction demo: client and screen mouse coordinates, keyboard event, contextual help, dynamic filtering and hover-to-front panels.
- Bus management: logged-in users can demonstrate add, edit and delete operations at `admin_buses.php`. For a classroom demo only; a real application must add administrator roles.
- Last visit cookie is displayed in the common header.

## Database
Five related tables: `users`, `routes`, `buses`, `bookings`, `booking_seats`. There are 10 routes and 10 sample buses. The unique key on `(bus_id, journey_date, seat_number)` prevents duplicate seat allocation.

## Important
This is an educational mock booking system. It does not connect to redBus, issue real tickets, or collect payments. Importing the SQL file drops and recreates these project tables; back up existing data first.

## Suggested viva explanation
- `database.php`: connects PHP to MySQL.
- `header.php` and `footer.php`: common layout used on multiple pages.
- `buses.php`: prepared search query, associative array and `usort()` sorting.
- `seats.php`: displays seats and marks already booked seats.
- `confirmation.php`: uses a transaction and a unique database key to stop duplicate seat booking.
- `register.php` and `login.php`: regex validation, password hashing and verification.
- `feedback.php`: text-file CRUD.
- `interaction.php`: DOM and browser events.

## Database update
The updated SQL schema no longer contains a `student_id` column. If you already imported the older version, the simplest clean setup is to back up any needed project data and re-import the updated `database/redbus.sql` (it recreates the project tables). Alternatively, run `ALTER TABLE users DROP COLUMN student_id;` once in phpMyAdmin if you want to preserve existing records.
