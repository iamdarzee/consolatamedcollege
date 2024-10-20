# Consolata Medical College - Online Courses Application System

## Project Description
This project is a web-based application system for Consolata Medical College. It allows prospective students to browse available courses, apply for a maximum of three courses, and view their application history. 
Admins can manage courses, staff, and student accounts through a dedicated admin portal.

## Features
- **Frontend**: Built using HTML, CSS, TailwindCSS, JavaScript, and jQuery to create a user-friendly and responsive interface.
  - Course application dashboard with a shopping cart-style interface for selecting courses.
  - Applicants can view all available courses with pagination and search through courses by name or description.
  - User authentication is implemented to restrict access to any dashboard.
  
- **Backend**: Powered by PHP and MySQL.
  - RESTful API endpoints handle CRUD operations to manage applicants, intakes, courses, and applications.
  - Data validation and error handling are enforced to ensure smooth application flow.

## Running the Project

### Option 1: Access the Live Site
You can access the live version of the system using the following link:  
[Live Site](https://consolatamedcollege.iamdarzee.com/)

### Option 2: Set Up and Run Locally with XAMPP
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/iamdarzee/consolatamedcollege.git
   ```

2. **Start XAMPP** and ensure Apache and MySQL are running.

3. **Create a Database**:
   - Open phpMyAdmin.
   - Create a new database (e.g., `sister_leonella_college_db`).
   - Import the SQL file located in the repository to set up the database structure.

4. **Configure the Project**:
   - In the project folder, locate the `config.php` file.
   - Update the database credentials to match your local setup.

5. **Run the Application**:
   - Place the project files inside the `htdocs` folder of your XAMPP installation.
   - Access the project through your browser at `http://localhost/consolatamedcollege`.

## Admin Portal
The admin side of the application is accessible via the following URLs:

- Localhost: [Admin Login](http://localhost/consolatamedcollege/adminSide/StaffLogin/login.php)
- Live: [Admin Login](https://consolatamedcollege.iamdarzee.com/adminSide/StaffLogin/login.php)

### Admin Credentials:
- **Admin ID**: 1  
- **Password**: syr2Y!@hZBkR9b

### Admin Features:
- Add and edit courses.
- Add new staff members.
- View all student accounts.
