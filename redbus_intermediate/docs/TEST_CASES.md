# Test Cases

| No. | Test | Expected result |
|---|---|---|
| 1 | Open home page | Search form appears |
| 2 | Search Bangalore to Mysore | Sample buses appear |
| 3 | Search an unknown route | No buses found |
| 4 | Sort by price | Prices reorder |
| 5 | Register with invalid email | Validation error |
| 6 | Register without a student ID field | Account registration succeeds using name, email and password |
| 7 | Register with valid details | Account is created |
| 8 | Login with wrong password | Login rejected |
| 9 | Login with correct password | Dashboard opens |
| 10 | Select available seats | Seats turn green and total updates |
| 11 | Select a booked seat | Seat is disabled |
| 12 | Confirm booking | Simulated booking is saved |
| 13 | Try booking same seat/date again | Unique database key rejects duplicate |
| 14 | Open booking history | Current user's bookings appear |
| 15 | Add/update/delete feedback | Text file changes |
| 16 | Move mouse on interaction page | Client and screen coordinates appear |
| 17 | Press a keyboard key | Key name appears |
| 18 | Filter bus list | Non-matching items hide |
| 19 | Focus help input | Contextual help appears |
| 20 | Hover overlapping panel | Panel moves to front |

## Live testing note
Run these tests in XAMPP with Apache and MySQL started. The project files have not been tested against your local XAMPP installation.
